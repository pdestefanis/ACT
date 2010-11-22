<?php
    //require_once('updateFile.php');
	require_once('db_connect.php');
	require_once('config.php');
	
    $long = "";
    $lat = "";
    $locationId = 0;
    $currDate = date("Y-m-d H:i:s");
    $rawreportId = 0;
    $treatmentId = 0;
    $reportType = "";

    // Get parameters
    // The reports from FLSMS come URL-encoded, we need to undo that
    // otherwise we end 'ABC 100' is reported as 'ABC+100', and other data,
    // like the '+' in the phone number, is encoded
    $report = urldecode($argv[1]);
    $phoneNum = urldecode($argv[2]);

    // Forms are also being processed under the <None> keyword rules
    // This is a hack to skip the message processing if a form is received
    // I would like for this to report nothing back, but FLSMS will still report.
    // Requested to fix that in the forums: http://frontlinesms.ning.com/forum/topics/binary-forms-going-to-the
    if (substr($report,0,10) == "AAI+gCUAAA") {
        exit();
    }

    // Separate the report in variables
    $data_item = explode(" ", strtoupper(trim($report)));
    $drugTreatName = $data_item[0];
    $quantity = $data_item[1];
    $strLength = strlen($drugTreatName);

    // If not complete set of arguments record the raw sms and exit
    // Also, check that the SMS contents only carry one value pair
    if ($drugTreatName == NULL || $quantity == NULL || $phoneNum == NULL || sizeof($data_item) != 2) {
	setRawreport(-1, $phoneNum, $drugTreatName, $quantity, $currDate, "Incorrect argument set.");
	exit("Incorrect report format. Please use Drug-ID or Treatment-ID, a space, and the number to report. Please send one report per SMS.");
    }

    $phoneId = getPhoneId($phoneNum);

    if ($phoneId == -1) {
	setPhone($phoneNum); //insert the not found phone in the database as inactive
	$phoneId = getPhoneId($phoneNum);
	setRawreport($phoneId, $phoneNum, $drugTreatName, $quantity, $currDate, "Phone number " . $phoneNum . " not found in database");
	echo "Error: phone number " . $phoneNum . " not found in database. It has been added but you won't be able to enter data until you request activation\n";
        exit;
    }

    $phoneStatus = getPhoneStatus($phoneId);

	if ($phoneStatus == 0) {
        $phoneId = getPhoneId($phoneNum);
	setRawreport($phoneId, $phoneNum, $drugTreatName, $quantity, $currDate, "Phone number " . $phoneNum . " not active in database");
	echo "Error: phone number " . $phoneNum . " is not active. You won't be able to enter data until you request activation\n";
        exit;
	}

    $locationId = getLocation($phoneNum, $phoneId, $long, $lat);

    if ($locationId == -1) {
	setRawreport($phoneId, $phoneNum, $drugTreatName, $quantity, $currDate, "Phone number " . $phoneNum . " not assigned to a location.");
        echo "This phone number doesn't have a location assigned. The report will not be processed. Please contact the central office.\n";
        exit;
  	}

    //determine drug or treatment
    if ($strLength == 3) {  //we have a drug
       $reportType = "D";
       $drugId = getDrugId($drugTreatName);
       if ($drugId == -1) {
          setRawreport($phoneId, $phoneNum, $drugTreatName, $quantity, $currDate, "Drug ID doesn\'t exist: " . $drugTreatName);
          echo "Cannot find a drug with code '" . $drugTreatName . "'. Drugs are identified by a three-letter code. Please verify and resend\n";
          exit;
       }
    } else  if ($strLength == 4) {
  	       $reportType = "T"; //treatment
               $treatmentId = getTreatmentId($drugTreatName);
               if ($treatmentId == -1) {
                  setRawreport($phoneId, $phoneNum, $drugTreatName, $quantity, $currDate, "Treatment ID doesn\'t exist: " . $drugTreatName);
  	          echo "Cannot find a treatment with code '" . $drugTreatName . "'. Treatments are identified by a four-letter code. Please verify and resend\n";
                  exit;
               }
            } else {
                   setRawreport($phoneId, $phoneNum, $drugTreatName, $quantity, $currDate, "Drug/Treatment ID incorrect.");
                   echo "Received " . $drugTreatName . " as drug or treatment code, but that is an invalid drug or treatment code. Please verify and resend\n";
    		   exit;
              }

       if (!is_numeric($quantity)) {
          setRawreport($phoneId, $phoneNum, $drugTreatName, $quantity, $currDate, "Quantity must be numeric.");
          echo "The quantity for the drug or treatment must be numeric, but received ".$quantity.". Please verify and resend\n";
          exit;
       }

       setRawreport($phoneId, $phoneNum, $drugTreatName, $quantity, $currDate, "OK");
       //insert the sms into stats. First get  drug_id, treatment_id, rawreport_id, phone_id
       $rawreportId = getRawreportId($phoneId, $currDate);
       if ($rawreportId == -1) {
          echo "Couldn't get raw report ID!\n";
          exit;
       }

       setStats($reportType, $quantity, $currDate, $drugId, $treatmentId, $rawreportId, $phoneId, $locationId);

       // If the user reported a drug, let's add the full name of the drug in the report SMS
       if ($reportType == "D") {
          $drugTreatName = getDrugName($drugId);
       }
       echo "Message processed successfully. Drug/Treatment: ".$drugTreatName.", Quantity reported: ".$quantity;

       function getPhoneId($phoneNum) {
                // Returns the phone ID if the phone is found in the database
                // otherwise returns -1.
                // substring the phonenumber last N digist from DB
                $query = "SELECT phones.id as pid FROM phones WHERE substring(phones.phonenumber FROM -" . PHONE_NUMBER_LENGTH . ")=substring('" . $phoneNum ."' FROM -" . PHONE_NUMBER_LENGTH . ") limit 1";
	        $result = runQuery($query);

                while ($row = $result->fetch_assoc()) {
                      $phoneId =  $row['pid'] ;
                }
                if ($result->num_rows != 0) {
                    $result->free();
                    return $phoneId;
		} else
			return -1;
	}
    
    	function getPhoneStatus($phoneId) {
		// Returns 1 if the phone is active in the database
                // otherwise returns -1

		$query = "SELECT phones.active as status FROM phones WHERE phones.id='" . $phoneId ."' limit 1";
		$result = runQuery($query);

		while ($row = $result->fetch_assoc()) {
            $phoneStatus = $row['status'];
		}
		if ($result->num_rows != 0) {
			$result->free();
			return $phoneStatus;
		} else
			return -1;
	}

	function setPhone($phoneNum) {
		//insert the new phone and make it inactive
		$query = "INSERT INTO phones (phonenumber, active, name) VALUES ('" . $phoneNum . "', 0, 'Unknown') ";
		$result = runQuery($query);
	}

	function getLocation($phoneNum, &$phoneId, &$long, &$lat) {
		$query = "SELECT locationLongitude, locationLatitude, locations.id as lid, phones.id as pid " .
				"FROM locations, phones " .
				"WHERE location_id = locations.id and phones.id='" . $phoneId ."' limit 1";

		$result = runQuery($query);

		while ($row = $result->fetch_assoc()) {
			$long =  $row['locationLongitude'] ;
			$lat =  $row['locationLatitude'] ;
			$locationId =  $row['lid'] ;
			$phoneId =  $row['pid'] ;
		}
		if ($result->num_rows != 0) {
			$result->free();
			return $locationId;
		} else return -1;
	}

	function setRawreport($phoneId, $phoneNum, $drugTreatName, $quantity, $currDate, $status) {
		//insert the raw submitted sms to the database
		$query = "INSERT INTO rawreports (raw_message, created,  phone_id, message_code) " .
				 "VALUES ('". $drugTreatName . " " . $quantity . " " . $phoneNum .
				 "', '" . $currDate . "', " . $phoneId . ", '" . $status . "')";
		$result = runQuery($query);
	}

    function getRawreportId($phoneId, $currDate) {
			$query = "SELECT rawreports.id as rid FROM rawreports " .
					"WHERE rawreports.phone_id = " . $phoneId . " " .
					"AND rawreports.created = '" . $currDate . "'  limit 1";
			$result = runQuery($query);

			while ($row = $result->fetch_assoc()) {
				$rawreportId =  $row['rid'] ;
			}
			if ($result->num_rows != 0) {
				$result->free();
				return $rawreportId;
			}
			else return -1;
	}

	function getDrugId($drugTreatName) {
		$query = "SELECT drugs.id as did FROM drugs WHERE drugs.code = UPPER('" . $drugTreatName ."') limit 1";
		$result = runQuery($query);

		while ($row = $result->fetch_assoc()) {
			$drugId =  $row['did'] ;
		}
		if ($result->num_rows != 0) {
			$result->free();
			return $drugId;
		}
		else return -1;
	}

       	function getDrugName($drugTreatId) {
		$query = "SELECT drugs.name as dname FROM drugs WHERE drugs.id = $drugTreatId limit 1";
		$result = runQuery($query);

		while ($row = $result->fetch_assoc()) {
			$drugName =  $row['dname'] ;
		}
		if ($result->num_rows != 0) {
			$result->free();
			return $drugName;
		}
		else return -1;
	}

    function getTreatmentId($drugTreatName) {
			$query = "SELECT treatments.id as tid FROM treatments WHERE treatments.code = UPPER('".$drugTreatName."') limit 1";
			$result = runQuery($query);

			while ($row = $result->fetch_assoc()) {
				$treatmentId =  $row['tid'] ;
			}
			if ($result->num_rows != 0) {
				$result->free();
				return $treatmentId;
			}
			else return -1;
	}

	function setStats($reportType, $quantity, $currDate, $drugId, $treatmentId, $rawreportId, $phoneId, $locationId ) {
			//insert the raw submitted sms to the database
			$query = "INSERT INTO stats " .
				"(quantity, created, drug_id, treatment_id, rawreport_id, phone_id, location_id) ";
			if ($reportType == 'D')
				$query .= "VALUES (" . $quantity . ", '" . $currDate . "', " . $drugId . ", 0 , " . $rawreportId . ", " . $phoneId . ", " . $locationId . ")";
			else if ($reportType == 'T')
				$query .= "VALUES (" . $quantity . ", '" . $currDate . "', 0 , " . $treatmentId . ", " . $rawreportId . ", " . $phoneId . ", " . $locationId . ")";
            //echo $query;
			$result = runQuery($query);
	}

?>


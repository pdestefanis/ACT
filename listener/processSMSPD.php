<?php
    //require_once('updateFile.php');
	require_once('../app/webroot/db_connect.php');
	require_once('../app/webroot/config.php');
	
	class ProcessSMS  {
		private $long = "";
		private $lat = "";
		private $locationId = 0;
		private $currDate = NULL;
		private $rawreportId = 0;
		private $phoneNum = 0; 
		private $patientNumber= NULL;
		private $patientId = NULL;
		private $message = "";
		private $msgAction = "";
		private $action = NULL;
		private $parentLocationId = 'NULL';
		private $validCodes = array();
		

		public function init($msg, $caller){
			$phoneNum = $caller; 
			$message = explode (" ", $msg); 
			$msgAction = strtoupper($message[0]);
			$currDate = date("Y-m-d H:i:s");

			//messages. Add more options for each of the actions here. E.g. 1 => array("A", "ACCEPT", "RECEIVED", "RE")  UPPERCASE ONLY
			//Note that the action code should correspond to the the database status id for that actoin
			//pdestefanis: added some to test
			$validCodes = array(
								1 => array("A", "ACCEPT", "ACEPTAR", "RECIBIR"),
								2 => array("D", "DELIVER"),
								3 => array("E", "EXPIRE"),
								4 => array("R", "RETURN"),
								5 => array("X", "DESTROY"), //I've added this to statuses as well to distiguish between expore and destroy
								10 => array("C", "CONSENT")
								);

			while ($vc = current($validCodes)) {
				if (array_search($msgAction, $vc ) !== FALSE )
					$action = key($validCodes);
				$vc = next($validCodes);
			}
			if ($action == NULL) {
				return "Error: action code " . $msgAction  . " not valid. Please correct and resend \n";
				// pdestefanis: log the reply from the SMS processor
          error_log("[ ".date("Y-m-d H:i:s")." ]:  processSMS: OPID not found: ".$action."\n", 3, "../parser.log");
			}
			
			//pdestefanis
			//Verify we have at least one parameter in addition to the OPID
			//TODO: Have the number of parameters depend on the OPID, maybe moving the OPIDs to a table
			if (count($message) == 1) {
				return "Error: You need more parameters for that operation \n";
				// pdestefanis: log the reply from the SMS processor
          error_log("[ ".date("Y-m-d H:i:s")." ]:  processSMS: Insufficient parameters: ".count($message)."\n", 3, "../parser.log");
			}

			//Process kits
			if ($action != 10) {
				$numberOfKits = NULL;

				if (strlen($message[1]) <= 2 && is_numeric($message[1])) {
					$numberOfKits = $message[1];
					$i=2;
				} else if (strlen($message[1]) == 4 && is_numeric($message[1]) ) {
					$numberOfKits = 1;
					$i=1;
				} else {
							echo "Error: kit " . $message[1] . " not valid. Please correct and resend \n";
							exit;
				}

				$kitCodes = array();
				

				//add kits to the array or set patient
				for ($i; $i <= $numberOfKits+1; $i++) {
					if (strlen($message[$i]) == 4 && is_numeric($message[$i]) ) {
						$kitCodes[] = $message[$i];
					} else if (strlen($message[$i]) == 8 && is_numeric($message[$i]) && ($numberOfKits+1) == $i) { //we have patient as last argument
							$patientNumber = $message[$i];
					} else if (strlen($message[$i]) == 0) { //end of argument list
						continue;
					} else {
						return "Error: kit/patient number " . $message[$i] . " not valid. Please correct and resend \n";
					}
				}
				
				
				// If not complete set of arguments record the raw sms and exit
				// Also, check that the SMS contents only carry one value pair
				if ($action == NULL || isset($kitCodes) === FALSE  //if no action or no kit
							|| (count($kitCodes) == 0 || (count($kitCodes) != $numberOfKits)) //count kits is 0 or count kits doesn't match number provided
							|| (count($kitCodes) != 1 && $patientNumber != NULL) ) { // if count is not 1 but patient ID is provided
					$this->setRawreport(-1, $phoneNum, $message, $currDate, "Incorrect argument set.");
					return "Incorrect report format. Please use Action, a space, number of kits if more then 1, a space, and the kit number(s) separated by space.";
				}

				$phoneId = $this->getPhoneId($phoneNum);

				if ($phoneId == -1) { //TODO Do we have this concept of inactive here?
					$this->setPhone($phoneNum); //insert the not found phone in the database as inactive
					$phoneId = $this->getPhoneId($phoneNum);
					$this->setRawreport($phoneId, $phoneNum, $message, $currDate, "Phone number " . $phoneNum . " not found in database");
					return "Error: phone number " . $phoneNum . " not found in database. It has been added but you won't be able to enter data until you request activation\n";
				}
				
				$phoneStatus = $this->getPhoneStatus($phoneId); 

				if ($phoneStatus == 0) {
					$phoneId = $this->getPhoneId($phoneNum);
					$this->setRawreport($phoneId, $phoneNum, $message, $currDate, "Phone number " . $phoneNum . " not active in database");
					return "Error: phone number " . $phoneNum . " is not active. You won't be able to enter data until you request activation\n";
				}

				$locationId = $this->getLocation($phoneNum, $phoneId, $long, $lat);

				if ($locationId == -1) { 
					$this->setRawreport($phoneId, $phoneNum, $message, $currDate, "Phone number " . $phoneNum . " not assigned to a location.");
					return "This phone number doesn't have a location assigned. The report will not be processed. Please contact the central office.\n";
				}
				
				if ($action == 1 || $action == 3 || $action == 4) { //if kit is accespted, expired or returned use the location id
					$locationId = $locationId;
					$parentLocationId = 'NULL'; //TODO GET THE LAST KNOWN PARENT
				} else { //if kit is delivered or destoyed use the location as parent
					$parentLocationId = $locationId;
					$locationId = 'NULL'; //TODO GET THE LAST KNOW LOCATION
				}
					
				//only process patient if such was sent
				if ($patientNumber != NULL) {
					$this->checkPatient($patientNumber, $patientId, $consent);
					//Check that both patient is registered and consent confirmed
					if ($patientId == -1) {
						return "Patient does not exist. Please register patient and acknowledge consent by sending C space Patient number!\n";
					}
					if ($consent != 1) {
						return "Patient consent missing. Please acknowledge consent by sending C space Patient number!\n";
					}
				}
			   
			   //get each kit id
				$kitCodesIds = array();
				foreach ($kitCodes as $kc) {
					$kId = $this->getKitId($kc);
					if ($kId == -1){
						echo "Kit does not exist!\n";
						exit;
					} else {
						$kitCodesIds[] = array ('id' => $kId, 'code' => $kc);
					}
				}
				
				//everything has been process is now safe to add the record
				$this->setRawreport($phoneId, $phoneNum, $message, $currDate, "OK");
			   
				//get the last id for rawreport
				$rawreportId = $this->getRawreportId($phoneId, $currDate);
				if ($rawreportId == -1) {
				  return "Couldn't get raw report ID!\n";
				}
				
				
				//for each kit sent insert the record
				foreach ($kitCodesIds as $kcid) {
					$this->setTrack($kcid['id'], $locationId, $patientId, $action, $currDate, $rawreportId, $phoneId, $parentLocationId );
				}
			   
				//TODO sent back the kit numbers AND OR PATIENT
			   return "Message processed successfully.  " . implode(",", $kitCodes);
			
			
			} else { //processs consent
			
				//TODO check that patient exists if so update consent sent message that consent was updated
				//If patient doesn't exist add them and set consent to yes. Sent message patient was added and consent was set
			
			}
		}
		
		public function getPhoneId($phoneNum) {
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
		
		public function getPhoneStatus($phoneId) {
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

		public function setPhone($phoneNum) {
			//insert the new phone and make it inactive
			$query = "INSERT INTO phones (phonenumber, active, name) VALUES ('" . $phoneNum . "', 0, 'Unknown') ";
			$result = runQuery($query);
		}

		public function getLocation($phoneNum, &$phoneId, &$long, &$lat) {
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

		public function setRawreport($phoneId, $phoneNum, $message, $currDate, $status) {
			//insert the raw submitted sms to the database
			$query = "INSERT INTO rawreports (raw_message, created,  phone_id, message_code) " .
					 "VALUES ('" . $message . " " . $phoneNum . "', '" . $currDate . "', " . $phoneId . ", '" . $status . "')";
			$result = runQuery($query);
		}

		public function getRawreportId($phoneId, $currDate) {
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

		public function setTrack($kitId, $locationId, $patientId, $statusId, $created, $rawreportId, $phoneId, $parentLocationId ) {
				//insert the raw submitted sms to the database
				$query = "INSERT INTO tracks " .
					"(kit_id, location_id, patient_id, status_id, created, rawreport_id, phone_id, parent_location_id) ";
				if ($patientId !=  NULL)
					$query .= "VALUES (" . $kitId . ", '" . $locationId . "', " . $patientId . "," . $statusId . ", '"  . $created . "', " . $rawreportId . ", " . $phoneId . ", " . $parentLocationId . ")";
				else if ($patientId ==  NULL)
					$query .= "VALUES (" . $kitId . ", '" . $locationId . "', NULL," . $statusId . ", '" . $created . "', " . $rawreportId . ", " . $phoneId . ", " . $parentLocationId . ")";
				//echo $query;
				$result = runQuery($query);
		}
		
		public function checkPatient($pNumber, &$pId, &$consent) {
				$query = "SELECT Patient.id as pid, Patient.consent c FROM patients Patient WHERE Patient.number =  '$pNumber' limit 1";
				$result = runQuery($query);

				while ($row = $result->fetch_assoc()) {
					$pId =  $row['pid'] ;
					$consent =  $row['c'] ;
				}
				if ($result->num_rows != 0) {
					$result->free();
				}
				else return -1;
		}
		
		public function getKitId($kc) {
				$query = "SELECT Kit.id as kid FROM kits Kit WHERE Kit.code =  '$kc' limit 1";
				$result = runQuery($query);

				while ($row = $result->fetch_assoc()) {
					$kitId =  $row['kid'] ;
				}
				if ($result->num_rows != 0) {
					$result->free();
					return $kitId;
				}
				else return -1;
		}
}	

?>


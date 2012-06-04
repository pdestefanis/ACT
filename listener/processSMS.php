<?php
    //require_once('updateFile.php');
	require_once('db_connect.php');
	require_once('../app/webroot/config.php');
	
	class ProcessSMS  {
		private $long = "";
		private $lat = "";
		private $level = NULL;
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
			
			//messages. Add more options for each of the actions here. 
			//Note that the action code should correspond to the the database status id for that actoin
			$validCodes = array(
								//UNIQUE abbrevations only for array values
								1 => array("A", "R", "ACCEPT", "ACEPTAR", "RECIBIR"), //UPPERCASE ONLY
								2 => array("D", "DELIVER"),
								3 => array("E", "EXPIRE"),
								//4 => array("R", "RETURN"), //code removed
								5 => array("X", "DESTROY"), //I;ve added this to statuses as well to distiguish between expore and destroy
								10 => array("C", "CONSENT")
								);
			
			while ($vc = current($validCodes)) {
				if (array_search($msgAction, $vc ) !== FALSE )
					$action = key($validCodes);
				$vc = next($validCodes);
			}
			if ($action == NULL) {
				// pdestefanis: log the reply from the SMS processor
				//I moved this before the return so it gets called
				error_log("[ ".date("Y-m-d H:i:s")." ]:  processSMS: OPID not found: ".$action."\n", 3, "../parser.log");
				$this->setRawreport(-1, $phoneNum, implode(" ", $message), $currDate, "Error: action code " . $msgAction  . " not valid. Please correct and resend");
				return "Error: action code " . $msgAction  . " not valid. Please correct and resend \n";
			}
			
			//moved above so that everything can be logged in rawreports
			$phoneId = $this->getPhoneId($phoneNum);
			
				if ($phoneId == -1) { 
					$this->setPhone($phoneNum); //insert the not found phone in the database as inactive
					$phoneId = $this->getPhoneId($phoneNum);
					
					$this->setRawreport($phoneId, $phoneNum, implode(" ", $message), $currDate, "Phone number " . $phoneNum . " not found in database");
					return "Error: phone number " . $phoneNum . " not found in database. It has been added but you won't be able to enter data until you request activation\n";
				}
				
			$phoneStatus = $this->getPhoneStatus($phoneId); 
			
			//pdestefanis
			//Verify we have at least one parameter in addition to the OPID
			//TODO: Have the number of parameters depend on the OPID, maybe moving the OPIDs to a table
			if (count($message) == 1) {
				// pdestefanis: log the reply from the SMS processor
				$this->setRawreport(-1, $phoneNum, implode(" ", $message), $currDate, "Insufficient parameters");
				error_log("[ ".date("Y-m-d H:i:s")." ]:  processSMS: Insufficient parameters: ".count($message)."\n", 3, "../parser.log");
				return "Error: You need more parameters for that operation \n";
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
							$this->setRawreport(-1, $phoneNum, implode(" ", $message), $currDate, "Error: kit number not valid");
							return "Error: kit " . $message[1] . " not valid. Please correct and resend \n";
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
						$this->setRawreport(-1, $phoneNum, implode(" ", $message), $currDate, "Error: kit/patient number not valid");
						return "Error: kit/patient number " . $message[$i] . " not valid. Please correct and resend \n";
					}
				}
				
				
				// If not complete set of arguments record the raw sms and exit
				// Also, check that the SMS contents only carry one value pair
				if ($action == NULL || isset($kitCodes) === FALSE  //if no action or no kit
							|| (count($kitCodes) == 0 || (count($kitCodes) != $numberOfKits)) //count kits is 0 or count kits doesn't match number provided 
							|| (count($kitCodes) != 1 && $patientNumber != NULL) ) { // if count is not 1 but patient ID is provided
					$this->setRawreport(-1, $phoneNum, implode(" ", $message), $currDate, "Incorrect argument set.");
					return "Incorrect report format. Please use Action, a space, number of kits if more then 1, a space, and the kit number(s) separated by space.";
				}

				if ($phoneStatus == 0) {
					$phoneId = $this->getPhoneId($phoneNum);
					$this->setRawreport($phoneId, $phoneNum, implode(" ", $message), $currDate, "Phone number " . $phoneNum . " not active in database");
					return "Error: phone number " . $phoneNum . " is not active. You won't be able to enter data until you request activation\n";
				}

				$locationId = $this->getLocation($phoneNum, $phoneId, $long, $lat, $level);

				if ($locationId == -1) { 
					$this->setRawreport($phoneId, $phoneNum, implode(" " ,$message), $currDate, "Phone number " . $phoneNum . " not assigned to a location.");
					return "This phone number doesn't have a location assigned. The report will not be processed. Please contact the central office.\n";
				}
				
				//check if the action is allowed from this location
				$valid = false;
				switch ($action) {
					case 1: //receive
						if ($level == 20 || $level == 30) //allowed to receive PHC and CH with or without patient
							$valid = true;
						break;
					case 2: //deliver
						if ($patientNumber != NULL) {
							if ($level == 20 || $level == 30) //allowed to deliver PHC and CH with patient
								$valid = true;
							} else {
								if ($level == 10 || $level == 20 || $level == 30) //allowed to deliver NCC, PHC and CH  without patient
									$valid = true;
							}
						break;
					case 3: //expire
						if ($level == 30) //allowed to expire PHC
							$valid = true;
						break;
					case 5: //destroy
						if ($level == 20) //allowed to expire CH
							$valid = true;
						break;
					default:
						$valid = false;
				}
				if (!$valid) { 
					$this->setRawreport($phoneId, $phoneNum, implode(" " ,$message), $currDate, "This phones assigned location is not allowed to perform action: " . $msgAction);
					return "This phones assigned location is not allowed to perform action: " . $msgAction;
				}
				//TODO GET THE LAST KNOW LOCATION
				//perhaps a predictive appraoch where we select the most probable location
				//if action is R and only one line in track the parent will be NCC
				//if action is R but more recrods then parent should be CH if ther eisn't a patient in last record
				
				if ($action == 2 && $level == 10) {  //kit is delivered from NCC
				//NOTE
				//
				// NEW KIT
				// THIS IS THE ONLY TIME WHEN KITS MUST BE 100% sent to the system. 
				// New kits coming from not NCC location will receive a not existing kit message
				// Otherwise we can't determine between a wrong kit id and a new kit sent from somewhere other then NCC
				//
				//NOTE
				
				//TODO determin forward or backward based on previous location and patient and status sent
					$parentLocationId = $locationId;
					$locationId = 0; 
				} else if ($action == 1 || ($action == 4 && $patientNumber != NULL)) { //if kit is accepted or returned from patient
					$locationId = $locationId;
					$parentLocationId = 0; //set to zero to signify that location is missing
				} else { //if kit is delivered or destoyed use the location as parent
					
					$parentLocationId = $locationId;
					$locationId = 0; //set to zero to signify that location is missing
				}
					
				//only process patient if such was sent
				if ($patientNumber != NULL) {
					$this->checkPatient($patientNumber, $patientId, $consent);
					//Check that both patient is registered and consent confirmed
					if ($patientId == -1) {
						$this->setRawreport($phoneId, $phoneNum, implode(" " ,$message), $currDate, "Patient consent missing. Please acknowledge consent by sending C space Patient number!");
						return "Patient does not exist. Please register patient and acknowledge consent by sending C space Patient number!\n";
					}
					if ($consent != 1) {
						$this->setRawreport($phoneId, $phoneNum, implode(" " ,$message), $currDate, "Patient consent missing. Please acknowledge consent by sending C space Patient number!");
						return "Patient consent missing. Please acknowledge consent by sending C space Patient number!\n";
					}
				}
			   
			   
			   //get each kit id
				$kitCodesIds = array();
				if ($action == 2 && $level == 10) { //register new kits
					foreach ($kitCodes as $kc) {
						$kId = $this->getKitId($kc);
						if ($kId == -1){
							$this->regKitId($kc); //insert kit
							$kId = $this->getKitId($kc); //get id
							$kitCodesIds[] = array ('id' => $kId, 'code' => $kc);
						}  else {
							$this->setRawreport($phoneId, $phoneNum, implode(" " ,$message), $currDate, "Error: Kit " . $kc . " is alredy registered!\n");
							return "Error: Kit " . $kc . " is alredy registered!\n";
						}
					}
				} else { //kit already in db just get id
					foreach ($kitCodes as $kc) {
						$kId = $this->getKitId($kc);
						if ($kId == -1){
							return "Kit does not exist!\n";
						} else {
							$kitCodesIds[] = array ('id' => $kId, 'code' => $kc);
						}
					}
				}
				
				//everything has been process is now safe to add the record
				$this->setRawreport($phoneId, $phoneNum, implode(" ", $message), $currDate, "OK");
			   
				//get the last id for rawreport
				$rawreportId = $this->getRawreportId($phoneId, $currDate);
				if ($rawreportId == -1) {
				  return "Couldn't get raw report ID!\n";
				}
				
				
				//for each kit sent insert the record
				foreach ($kitCodesIds as $kcid) {
					$this->setTrack($kcid['id'], $locationId, $patientId, $action, $currDate, $rawreportId, $phoneId, $parentLocationId );
				}
			   
			   
			   return "Message processed successfully.  " . implode(" ", $message);
			
			
			} else { //processs consent
				if (strlen($message[1]) == 8 && is_numeric($message[1]) ) {//we have patient process this patient
					$this->setRawreport($phoneId, $phoneNum, implode(" " ,$message), $currDate, "Patient registered.");
					return "Patient " . $message[1] . " " . $this->regPatient($message[1], $currDate);
				} else {
					$this->setRawreport($phoneId, $phoneNum, implode(" " ,$message), $currDate, "Error: patient number " . $message[1] . " is invalid. Please correct and resend.");
					return "Error: patient number " . $message[1] . " is invalid. Please correct and resend." ;
				}
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
			$query = "INSERT INTO phones (phonenumber, active, name, deleted) VALUES ('" . $phoneNum . "', 0, 'Unknown', 0) ;";
			$result = runQuery($query);
			
		}

		public function getLocation($phoneNum, &$phoneId, &$long, &$lat, &$level) {
			$query = "SELECT locationLongitude, locationLatitude, locations.id as lid, level_id, phones.id as pid " .
					"FROM locations, phones " .
					"WHERE location_id = locations.id and phones.id='" . $phoneId ."' limit 1";

			$result = runQuery($query);

			while ($row = $result->fetch_assoc()) {
				$long =  $row['locationLongitude'] ;
				$lat =  $row['locationLatitude'] ;
				$locationId =  $row['lid'] ;
				$level =  $row['level_id'] ;
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
					$query .= "VALUES (" . $kitId . ", " . $locationId . ", " . $patientId . "," . $statusId . ", '"  . $created . "', " . $rawreportId . ", " . $phoneId . ", " . $parentLocationId . ")";
				else if ($patientId ==  NULL)
					$query .= "VALUES (" . $kitId . ", " . $locationId . ", NULL," . $statusId . ", '" . $created . "', " . $rawreportId . ", " . $phoneId . ", " . $parentLocationId . ")";
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
		
		public function regPatient($pNumber, $currDate) { 
				$consent = 0;
				$pId = 0;
				$ret = NULL;
				$patient = $this->checkPatient($pNumber, $pId, $consent);
				if ($pId == 0) { //new patient
					$query = "INSERT INTO patients (number, created, consent) VALUES ('" . $pNumber . "', '" . $currDate . "' , 1)";
					$ret = "created";
				} else { //old patient update consent
					$query = "UPDATE patients set consent = 1 WHERE id =  " . $pId;
					$ret = "updated";
				}
				$result = runQuery($query);
				return $ret;
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
		
		public function regKitId($kc) { //This only inserts for the only avaiable kittype. 
			//If other kit types are provided at some point new action should be given with the kittype
				$query = "INSERT INTO kits (code, kittype_id) VALUES ('" . $kc . "', 1)";
				$result = runQuery($query);
		}
}	

?>


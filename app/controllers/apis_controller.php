<?php
class ApisController extends AppController {

	var $name = 'Apis';
	var $uses = array();
	//var $helpers = array('Html', 'Form');
	var $components = array('RequestHandler', 'Access', 'Rest.Rest' => array(
			'catchredir' => true, // Recommended unless you implement something yourself
			'debug' => 0,
			'actions' => array( //expose these actions and those variable to rest
				'findPhone' => array('extract' => array('phone'),),
				'discardUnit' => array('extract' => array('stat'),),
				'receiveUnit' => array('extract' => array('stat'),),
				'assignToFacility' => array('extract' => array('stat'),),
				'assignToPatient' => array('extract' => array('stat'),),
				'findPatient' => array('extract' => array('patient'),),
				'findUnit' => array('extract' => array('unit'),),
				'findFacility' => array('extract' => array('facility'),),
				'createUnit' => array('extract' => array('unit'),),
			),
		),);
		
	function findPhone($phonenumber = null) {
		if ($this->Rest->isActive()) {
			$this->Rest->postData = $this->data;
			//validate phone numbers optional + and max 11 digits after it
			if (!preg_match("/^\+?[0-9]{4,11}$/", $phonenumber)){
				$this->Rest->error(__('Invalid phonenumber', true));
				$this->Rest->abort();
			}
			
			Configure::load('options');
			$length = Configure::read('Phone.length');
			
			$this->loadModel('Phones'); 
			$phoneNum = substr($phonenumber, -$length);
			$conditions = array("Phones.phonenumber LIKE " => "%%" . $phoneNum . "%%");
		    $phone = $this->Phones->find('first', array('conditions' => $conditions, 'callbacks' => false));			
			
			if (empty($phone)) {
				//setup phone array for saving
				$data = array('Phones' => array(
												'phonenumber' => $phonenumber,
												'active' => 0,
												'location_id' => NULL,
												'active' => 0,
												'name' => __('Unknown', true),
												'deleted' => 0,
											) );
				$this->Phones->create();
				if (!$this->Phones->save($data)) {
					$this->Rest->error(__('Phone could not be saved: 10107', true));
					$this->Rest->abort();
				}
				$phone = $this->Phones->findById($this->Phones->id);
				$this->Rest->error(__('Phone number not found. It will be added but you will not be able to report until it is activated.', true));
			}
			//don't allow inactive phones to report
			if (isset($phone['Phones']['active']) && $phone['Phones']['active'] == 0) { 
					$this->Rest->error(__('This phone has not been activated. Please request activation.', true));
			}
			//don't allow deleted phones to report
			if (isset($phone['Phones']['deleted']) && $phone['Phones']['deleted'] == 1) { 
					$this->Rest->error(__('This phone has been removed from the system and cannot report.', true));
			}
			//check that phone is assigned to a facility
			if (!isset($phone['Phones']['location_id']) ) { 
					$this->Rest->error(__('This phone has not been assigned to a facility. Please request assignment.', true));
					//$this->Rest->abort();
			}	
			$this->set(compact('phone'));
			return $phone;
		}
	}
	
	//Discard open unit
	function discardUnit($phoneNumber, $unitNumber, $facilityShortname = null, $date = null) {
		if ($this->Rest->isActive()) {
			$phone = $this->findPhone($phoneNumber);
			$argsList = func_get_args();
			//TODO
			//figure out why setReceived doesn't work when call is nested from checkFeedback
			$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			//find the unit
			$unit = $this->findUnit($unitNumber);
			$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			//if facility is not set use the phone's assigned facility
			if (is_null($facilityShortname)){
				$facility['Locations']['id'] = $phone['Phones']['location_id'];
			} else {
				$facility = $this->findFacility($facilityShortname);
				$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
				$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			}
			//set the date
			if (is_null($date))
				$date = date("Y-m-d H:i:s");
			
			
			//prepare the stats data
			$data = array('Stats' => array(
												'created' => $date,
												'phone_id' => $phone['Phones']['id'],
												'location_id' => $facility['Locations']['id'],
												'unit_id' => $unit['Units']['id'],
												'messagereceived_id' => $messagereceivedId,
												'status_id' => 3, //3 is discard
											) );
			$this->loadModel('Stats');
			$this->Stats->create();
			if (!$this->Stats->save($data)) {
				$this->Rest->error(__('Record could not be saved: 10101', true));
				$this->Rest->abort();
			}
			$this->Rest->info(__('Thank you. Your report was successfuly submitted.', true));
			$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			$stat = $this->Stats->findById($this->Stats->id);	
			$this->set(compact('stat'));
		}
	}
	
	//Confirm reception from upstream
	function receiveUnit($phoneNumber, $unitNumber, $facilityShortname = null, $date = null) {
		if ($this->Rest->isActive()) {
			$phone = $this->findPhone($phoneNumber);
			$argsList = func_get_args();
		
			$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			//find the unit
			$unit = $this->findUnit($unitNumber);
			$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			//if facility is not set use the phone's assigned facility
			if (is_null($facilityShortname)){
				$facility['Locations']['id'] = $phone['Phones']['location_id'];
			} else {
				$facility = $this->findFacility($facilityShortname);
				$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
				$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			}
			//set the date
			if (is_null($date)) {
				$data['Stats']['created']['year'] = date('Y');
				$data['Stats']['created']['month'] = date('m');
				$data['Stats']['created']['day'] = date('d');
				$data['Stats']['created']['hour'] = date('H');
				$data['Stats']['created']['min'] = date('i');
				$data['Stats']['created']['sec'] = date('s');
			} else { //add jsut the time of entry
				$data['Stats']['created']['hour'] = date('H');
				$data['Stats']['created']['min'] = date('i');
				$data['Stats']['created']['sec'] = date('s');
			}
			//prepare the stats data
			$data = array('Stats' => array(
												'created' => $date,
												'phone_id' => $phone['Phones']['id'],
												'location_id' => $facility['Locations']['id'],
												'unit_id' => $unit['Units']['id'],
												'messagereceived_id' => $messagereceivedId,
												'status_id' => 1, //1 is receive
											) );
											
			$this->loadModel('Stats');
			$this->Stats->create();
			if (!$this->Stats->save($data)) {
				$this->Rest->error(__('Record could not be saved: 10102', true));
				$this->Rest->abort();
			}
			$stat = $this->Stats->findById($this->Stats->id);	
			$this->set(compact('stat'));
			$lastFacilityWithKit = $this->findLastUnitFacility($unit['Units']['id'], $this->dateArrayToString($data['Stats']['created']));
			//if assiging the same unit to the same facility don't increment quantity
			$data['Stats']['quantity'] = (($lastFacilityWithKit === $facility['Locations']['id'])?0:1);
			//adjust the quantities only one quantity at a time
			if ($this->data['Stats']['quantity'] != 0 && $lastFacilityWithKit != -1)
				$this->adjustQuantities(
									$data['Stats']['created'],
									$unit['Units']['id'],
									'A', 
									(isset($patient['Patients']['id'])?0:1), //no need for qty when assigning to patient
									$facility['Locations']['id'], 
									$patient['Patients']['id'],
									$phone['Phones']['id'],
									NULL,
									NULL
									);		
			$this->Rest->info(__('Thank you. Your report was successfully submitted.', true));
			$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
		}
	}
	
	//Assign unit to patient
	function assignToPatient($phoneNumber, $unitNumber, $patientNumber, $facilityShortname = null, $date = null) {
		if ($this->Rest->isActive()) {
			$phone = $this->findPhone($phoneNumber);
			$argsList = func_get_args();
			$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			/*TODO
			 * $unit = $this->findUnit($unitNumber);
			var_dump ((bool)$this->isUnusedUnit($unit['Units']['id']));
			exit;*/
			//find the unit
			$unit = $this->findUnit($unitNumber);
			$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			//find patient
			$patient = $this->findPatient($patientNumber);
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			//if facility is not set use the phone's assigned facility
			if (is_null($facilityShortname)){
				$facility['Locations']['id'] = $phone['Phones']['location_id'];
			} else {
				$facility = $this->findFacility($facilityShortname);
				$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
				$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			}
			//set the date
			if (is_null($date)) {
				$data['Stats']['created']['year'] = date('Y');
				$data['Stats']['created']['month'] = date('m');
				$data['Stats']['created']['day'] = date('d');
				$data['Stats']['created']['hour'] = date('H');
				$data['Stats']['created']['min'] = date('i');
				$data['Stats']['created']['sec'] = date('s');
			} else { //add jsut the time of entry
				$data['Stats']['created']['hour'] = date('H');
				$data['Stats']['created']['min'] = date('i');
				$data['Stats']['created']['sec'] = date('s');
			}
			//prepare the stats data
			$data = array('Stats' => array(
												'created' => $data['Stats']['created'],
												'phone_id' => $phone['Phones']['id'],
												'location_id' => $facility['Locations']['id'],
												'unit_id' => $unit['Units']['id'],
												'messagereceived_id' => $messagereceivedId,
												'status_id' => 2, //1 is assign
												'patient_id' => $patient['Patients']['id'],
											) );
			
			$this->loadModel('Stats');
			$this->Stats->create();
			if (!$this->Stats->save($data)) {
				$this->Rest->error(__('Record could not be saved: 10103', true));
				$this->Rest->abort();
			}
			$lastFacilityWithKit = $this->findLastUnitFacility($unit['Units']['id'], $this->dateArrayToString($data['Stats']['created']));
			//if assiging the same unit to the same facility don't increment quantity
			$data['Stats']['quantity'] = (($lastFacilityWithKit === $facility['Locations']['id'])?0:1);
			//adjust the quantities only one quantity at a time
			if ($this->data['Stats']['quantity'] != 0 && $lastFacilityWithKit != -1)
				$this->adjustQuantities(
									$data['Stats']['created'],
									$unit['Units']['id'],
									'A', 
									(isset($patient['Patients']['id'])?0:1), //no need for qty when assigning to patient
									$facility['Locations']['id'], 
									$patient['Patients']['id'],
									$phone['Phones']['id'],
									NULL,
									NULL
									);		
			$this->Rest->info(__('Thank you. Your report was successfuly submitted.', true));
			$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
				
			$this->set(compact('stat'));
		}
	}
	
	//Assign unit to facility
	function assignToFacility($phoneNumber, $unitNumber, $facilityShortname, $date = null) {
		if ($this->Rest->isActive()) {
			$phone = $this->findPhone($phoneNumber);
			$argsList = func_get_args();
			$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			//find the unit
			$unit = $this->findUnit($unitNumber);
			$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			//find facility
			$facility = $this->findFacility($facilityShortname);
			$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			//set the date
			if (is_null($date)) {
				$data['Stats']['created']['year'] = date('Y');
				$data['Stats']['created']['month'] = date('m');
				$data['Stats']['created']['day'] = date('d');
				$data['Stats']['created']['hour'] = date('H');
				$data['Stats']['created']['min'] = date('i');
				$data['Stats']['created']['sec'] = date('s');
			} else { //add jsut the time of entry
				$data['Stats']['created']['hour'] = date('H');
				$data['Stats']['created']['min'] = date('i');
				$data['Stats']['created']['sec'] = date('s');
			}
			//prepare the stats data
			$data = array('Stats' => array(
												'created' => $date,
												'phone_id' => $phone['Phones']['id'],
												'location_id' => $facility['Locations']['id'],
												'unit_id' => $unit['Units']['id'],
												'messagereceived_id' => 0,
												'status_id' => 2, //2 is assign
											) );
			$this->loadModel('Stats');
			$this->Stats->create();
			if (!$this->Stats->save($data)) {
				$this->Rest->error(__('Record could not be saved: 10104', true));
				$this->Rest->abort();
			}
			$lastFacilityWithKit = $this->findLastUnitFacility($unit['Units']['id'], $this->dateArrayToString($data['Stats']['created']));
			//if assiging the same unit to the same facility don't increment quantity
			$data['Stats']['quantity'] = (($lastFacilityWithKit === $facility['Locations']['id'])?0:1);
			echo "LAST" . $lastFacilityWithKit;
			//adjust the quantities only one quantity at a time
			if ($this->data['Stats']['quantity'] != 0 && $lastFacilityWithKit != -1)
				$this->adjustQuantities(
									$data['Stats']['created'],
									$unit['Units']['id'],
									'A', 
									(isset($patient['Patients']['id'])?0:1), //no need for qty when assigning to patient
									$facility['Locations']['id'], 
									$patient['Patients']['id'],
									$phone['Phones']['id'],
									NULL,
									NULL
									);		
			$this->Rest->info(__('Thank you. Your report was successfuly submitted.', true));
			$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			$stat = $this->Stats->findById($this->Stats->id);	
			$this->set(compact('stat'));
		}
	}
	
	//find patient
	function findPatient($patientNumber) {
		if ($this->Rest->isActive()) {
			$this->loadModel('Patients'); 
			$conditions = array("Patients.number " => $patientNumber);
		    $patient = $this->Patients->find('first', array('conditions' => $conditions, 'callbacks' => false));			
		    if (!isset($patient['Patients']['id'])) {
		    	$this->Rest->error(__('Patient does not exist: ' , true) . $patientNumber);
				//$this->Rest->abort();
		    }
		    $this->set(compact('patient'));
		    return $patient;
		}
	}
	
	//find unit
	function findUnit($unitNumber) {
		if ($this->Rest->isActive()) {
			$this->loadModel('Units'); 
			$conditions = array("Units.code " => $unitNumber);
			//TODO
			//unit must be assigned to an upstream facility
			//not be disgarded, not delted
		    $unit = $this->Units->find('first', array('conditions' => $conditions, 'callbacks' => false));			
		    if (!isset($unit['Units']['id'])) {
		    	$this->Rest->error(__('Unit does not exist: ' , true) . $unitNumber);
		    }
		    $this->set(compact('unit'));
		    return $unit;
		}
	}
	
	//find facility/location
	function findFacility($facilityShortname) {
		if ($this->Rest->isActive()) {
			$this->loadModel('Locations'); 
			$conditions = array("Locations.shortname " => strtoupper($facilityShortname) );
		    $facility = $this->Locations->find('first', array('conditions' => $conditions, 'callbacks' => false));			
		    if (!isset($facility['Locations']['id'])) {
				$this->Rest->error(__('Facility does not exist: ' , true) . $facilityShortname);
		    }
		    $this->set(compact('facility'));
		    return $facility;
		}
	}
	
	//create unit
	function createUnit($unitNumber) {
		if ($this->Rest->isActive()) {
			
		}
	}
	
	//insert the received message
	function setReceived($argsList, $phoneId){
		if ($this->Rest->isActive()) {
			$this->loadModel('Messagereceiveds');
			$date = date("Y-m-d H:i:s");
			$data = array('Messagereceiveds' => array(
											'phone_id' => $phoneId,
											'created' => $date,
											'rawmessage' => implode(",", $argsList),
						) );
			$this->Messagereceiveds->create();
			if (!$this->Messagereceiveds->save($data)) {
				$this->Rest->error(__('Received message could not be saved: 10105', true));
				$this->Rest->abort();
			} else {
	 			return $this->Messagereceiveds->id;
			}
	
		}
	}
	//insert the sent message
	function setSent($message, $phoneId, $messagereceivedsId ){
		if ($this->Rest->isActive()) {
			$this->loadModel('Messagesents');
			$date = date("Y-m-d H:i:s");
			$data = array('Messagesents' => array(
											'messagereceived_id' => $messagereceivedsId,
											'phone_id' => $phoneId,
											'created' => $date,
											'rawmessage' => $message,
						) );
			$this->Messagesents->create();
			if (!$this->Messagesents->save($data)) {
				$this->Rest->error(__('Sent message could not be saved: 10106', true));
				$this->Rest->abort();
			} else {
	 			return $this->Messagesents->id; //not needed but for consisteny with message received
			}
		}
	}
	
	function checkFeedback($argsList, $phoneId, &$messagereceivedId ){
		if ($this->Rest->isActive()) {
			$feedback = $this->Rest->getFeedBack();
			if (isset($feedback['info']) || isset($feedback['error']) ) {
				//$messagereceivedId = $this->setReceived($argsList, $phoneId);
				$messagesent = 	$this->setSent(isset($feedback['info'])?$feedback['info'][0]:$feedback['error'][0], $phoneId, $messagereceivedId);
				if (isset($feedback['error']) )
					$this->Rest->abort();
			}
		}
	}
}
?>
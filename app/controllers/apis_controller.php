<?php
class ApisController extends AppController {

	var $name = 'Apis';
	var $uses = array();
	//var $helpers = array('Html', 'Form');
	var $components = array('RequestHandler', 'Access', 'Rest.Rest' => array(
			'catchredir' => true, // Recommended unless you implement something yourself
			'debug' => 0,
			'actions' => array( //expose these actions and those variable to rest
					'discardUnit' => array('extract' => array('stat'),),
					'receiveUnit' => array('extract' => array('stat'),),
					'assignToFacility' => array('extract' => array('stat'),),
					'assignToPatient' => array('extract' => array('stat'),),
					'createUnit' => array('extract' => array('unit'),),
			),
	),);

	/*
	 * Discard open unit
	* required $phoneNumber, $unitNumber allowed nulls only to return
	* a nice message if not supplied instead of breaking the app
	*/
	function discardUnit($phoneNumber = null, $unitNumber = null, $facilityShortname = null, $date = null) {
		if ($this->Rest->isActive()) {
			if (is_null($phoneNumber) || is_null($unitNumber))
				$this->rejectMessage("lessParams");
				
			$phone = $this->findPhone($phoneNumber);
			$argsList = func_get_args();
			//TODO
			//figure out why setReceived doesn't work when call is nested from checkFeedback
			$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			//find the unit
			$unit = $this->findUnit($unitNumber);
			//$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			$isDiscarded = $this->isDiscardedUnit($unit['Units']['id']);
			if (!$isDiscarded) {
				$this->Rest->error(__('This kit is already discarded', true));
				//$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
				$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			}
			//if facility is not set use the phone's assigned facility
			if (is_null($facilityShortname)){
				$facility['Locations']['id'] = $phone['Phones']['location_id'];
			} else {
				$facility = $this->findFacility($facilityShortname);
				//$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
				$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			}
			//set the date
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
				
			$lastFacilityWithKit = $this->findLastUnitFacility($unit['Units']['id'], $this->dateArrayToString($data['Stats']['created']));
			//if assiging the same unit to the same facility don't increment quantity
			
			if ( $lastFacilityWithKit != -1 && $this->isUnusedUnit($unit['Units']['id'])) {
				$this->adjustQuantities(
						$data['Stats']['created'],
						$unit['Units']['id'],
						'A',
						(($lastFacilityWithKit != $facility['Locations']['id'])?-1:0), //no need for qty when same facility
						(($lastFacilityWithKit != $facility['Locations']['id'])?$lastFacilityWithKit:$facility['Locations']['id']),
						NULL,
						(isset($phone['Phones']['id'])?$phone['Phones']['id']:NULL),
						NULL,
						$messagereceivedId
				);
			}

			$data['Stats']['quantity'] = -1;
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
				$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			}
			$this->Rest->info(__('Thank you. Your report was successfuly submitted.', true));
			//$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			$stat = $this->Stats->findById($this->Stats->id);
			$this->set(compact('stat'));
		}
	}

	/*
	 * Receive from facility or patient
	* required $phoneNumber , $unitNumber
	*/
	function receiveUnit($phoneNumber = null, $unitNumber = null, $facilityShortname = null,$date = null) {
		if ($this->Rest->isActive()) {
			if (is_null($phoneNumber) || is_null($unitNumber))
				$this->rejectMessage("lessParams");
				
			$phone = $this->findPhone($phoneNumber);
			$argsList = func_get_args();

			$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			//find the unit
			$unit = $this->findUnit($unitNumber);
			//$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			//if facility is not set use the phone's assigned facility
			if (is_null($facilityShortname))
				$facility['Locations']['id'] = $phone['Phones']['location_id'];
			else
				$facility = $this->findFacility($facilityShortname);
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
			
		$this->loadModel('Stats');
		$currentFacilityPatient = $this->getUnitCurrentFacility($unit['Units']['id']);
		$lastFacilityWithKit = $this->findLastUnitFacility($unit['Units']['id'], $this->dateArrayToString($data['Stats']['created']));
			
		$wasWithPatient = $this->Stats->find('list',  array ('conditions' => array('patient_id is not null',
				'unit_id' => $unit['Units']['id']
		),
				'fields' => array('unit_id'), 'callbacks' => false) );

		//$data['Stats']['quantity'] = (($lastFacilityWithKit === $facility['Locations']['id']  || !empty($wasWithPatient))?0:1);
		//set patient number
		$patientId = null;
		if (empty($wasWithPatient)){
			$patientId = null;
		} else if (isset($currentFacilityPatient[1])) { //patient id suppplied
			$patientId = $currentFacilityPatient[1];
		} 
		//adjust the quantities only one quantity at a time
		if ($lastFacilityWithKit != $facility['Locations']['id'] && $lastFacilityWithKit != -1 && empty($wasWithPatient))
			$this->adjustQuantities(
					$data['Stats']['created'],
					$unit['Units']['id'],
					'R',
					(( empty($wasWithPatient))?0:1), //no need for qty when receiving from patient
					$lastFacilityWithKit,
					$patientId,
					$phone['Phones']['id'],
					NULL,
					$messagereceivedId
			);
		//prepare the stats data
		$data = array('Stats' => array(
				'created' => $date,
				'phone_id' => $phone['Phones']['id'],
				'location_id' => $facility['Locations']['id'],
				'patient_id' => $patientId,
				'unit_id' => $unit['Units']['id'],
				'messagereceived_id' => $messagereceivedId,
				'status_id' => 1, //1 is receive
		) );
		//if receiving the same unit from the same facility don't increment quantity
		$data['Stats']['quantity'] = ((!empty($wasWithPatient) || ($lastFacilityWithKit === $facility['Locations']['id']))?0:1);//;(($lastFacilityWithKit === $facility['Locations']['id'])?0:1);
			
		$this->Stats->create();
		if (!$this->Stats->save($data)) {
			$this->Rest->error(__('Record could not be saved: 10102', true));
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
		}
		$stat = $this->Stats->findById($this->Stats->id);
		$this->set(compact('stat'));
			
		$this->Rest->info(__('Thank you. Your report was successfully submitted.', true));
		//$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
		$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
	}

	/* Assign unit to patient
	 * Required $phoneNumber, $unitNumber, $patientNumber,
	*/
	function assignToPatient($phoneNumber = null, $unitNumber = null, $patientNumber = null, $date = null) {
		if ($this->Rest->isActive()) {
			if (is_null($phoneNumber) || is_null($unitNumber) || is_null($patientNumber))
				$this->rejectMessage("lessParams");

			$phone = $this->findPhone($phoneNumber);
			$argsList = func_get_args();
			$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			//find the unit
			$unit = $this->findUnit($unitNumber);
			//$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			$isUnused = $this->isUnusedUnit($unit['Units']['id']);
			if (!$isUnused) {
				$this->Rest->error(__('This kit is open cannot be assigned to patient', true));
				//$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
				$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			}
			//find patient
			$patient = $this->findPatient($patientNumber);
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			//if facility is not set use the phone's assigned facility
			//if (is_null($facilityShortname)){
			$facility['Locations']['id'] = $phone['Phones']['location_id'];
			/* } else {
				$facility = $this->findFacility($facilityShortname);
			$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			} */
			//compare the user facility and children thereof to the kit current facility
			$this->checkKitFacility($unit['Units']['id'], $facility['Locations']['id']);
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
			//adjust the quantity
			$lastFacilityWithKit = $this->findLastUnitFacility($unit['Units']['id'], $this->dateArrayToString($data['Stats']['created']));

			//adjust the quantities
			if ($lastFacilityWithKit != $facility['Locations']['id'] && $lastFacilityWithKit != -1)
				$this->adjustQuantities(
						$data['Stats']['created'],
						$unit['Units']['id'],
						'A',
						0, //no need for qty when assigning to patient
						NULL,
						$patient['Patients']['id'],
						$phone['Phones']['id'],
						NULL,
						$messagereceivedId
				);
			//prepare the stats data
			$data = array('Stats' => array(
					'created' => $data['Stats']['created'],
					'phone_id' => $phone['Phones']['id'],
					'location_id' => $lastFacilityWithKit,
					'unit_id' => $unit['Units']['id'],
					'messagereceived_id' => $messagereceivedId,
					'status_id' => 2, //2 is assign
					'patient_id' => $patient['Patients']['id'],
			) );
			$data['Stats']['quantity'] = (($lastFacilityWithKit === $facility['Locations']['id'])?-1:0);
			
			$this->loadModel('Stats');
			$this->Stats->create();
			if (!$this->Stats->save($data)) {
				$this->Rest->error(__('Record could not be saved: 10103', true));
				$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
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
			//$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);

			$this->set(compact('stat'));
		}
	}

	/*
	 * Assign unit to facility
	* Required $phoneNumber, $unitNumber, $facilityShortname
	*/
	function assignToFacility($phoneNumber = null, $unitNumber = null, $facilityShortname = null, $date = null) {
		if ($this->Rest->isActive()) {
			if (is_null($phoneNumber) || is_null($unitNumber) || is_null($facilityShortname)) {
				$this->rejectMessage("lessParams");
				//$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
				//$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			}
				
			$phone = $this->findPhone($phoneNumber);
			$argsList = func_get_args();
			$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			//find the unit
			$unit = $this->findUnit($unitNumber);
			//$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			$isUnused = $this->isUnusedUnit($unit['Units']['id']);
			if (!$isUnused) {
				$this->Rest->error(__('This kit is open and cannot be assigne to facility', true));
				//$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
				$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			}
			//find facility
			$facility = $this->findFacility($facilityShortname);
			//$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
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
			//adjust the quantity
			$lastFacilityWithKit = $this->findLastUnitFacility($unit['Units']['id'], $this->dateArrayToString($data['Stats']['created']));
			//if assiging the same unit to the same facility don't increment quantity
			
			//adjust the quantities
			if ($lastFacilityWithKit != $facility['Locations']['id'] && $lastFacilityWithKit != -1)
				$this->adjustQuantities(
						$data['Stats']['created'],
						$unit['Units']['id'],
						'A',
						1, //($lastFacilityWithKit != $facility['Locations']['id']?1:0), //qty always one
						$facility['Locations']['id'],
						NULL,
						$phone['Phones']['id'],
						NULL,
						$messagereceivedId
				);
			//prepare the stats data
			$data = array('Stats' => array(
					'created' => $date,
					'phone_id' => $phone['Phones']['id'],
					'location_id' => $facility['Locations']['id'],
					'unit_id' => $unit['Units']['id'],
					'messagereceived_id' => $messagereceivedId,
					'status_id' => 2, //2 is assign
			) );
			$data['Stats']['quantity'] = (($lastFacilityWithKit === $facility['Locations']['id'])?0:1);
			$this->loadModel('Stats');
			$this->Stats->create();
			if (!$this->Stats->save($data)) {
				$this->Rest->error(__('Record could not be saved: 10104', true));
				$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			}
				
			$this->Rest->info(__('Thank you. Your report was successfuly submitted.', true));
			//$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			$stat = $this->Stats->findById($this->Stats->id);
			$this->set(compact('stat'));
		}
	}

	/*
	 * create unit and first associated assignment record
	 * required $phoneNumber, $unitNumber, $facilityShortname
	*/
	function createUnit($phoneNumber = null, $unitNumber = null, $facilityShortname = null, $date = null) {
		if ($this->Rest->isActive()) {
			if (is_null($phoneNumber) || is_null($unitNumber) || is_null($facilityShortname))
				$this->rejectMessage("lessParams");
			
			$phone = $this->findPhone($phoneNumber);
			$argsList = func_get_args();
			$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			
			//find facility
			$facility = $this->findFacility($facilityShortname);
			//$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			
			//first create the unit
			$this->loadModel('Units');
			$unit = $this->Units->findByCode($unitNumber);			
			if (!empty($unit)) { //reject units that already aexist
				$this->Rest->error(__('This unit already exists: ' . $unitNumber, true));
				//$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
				$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			}
			$unitData = array('Units' => array ('code' => $unitNumber));
			$this->Units->create();
			if (!$this->Units->save($unitData)) {
				$this->Rest->error(__('Record could not be saved: 10301', true));
				$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			}
			$newUnitId = $this->Units->id;
			
			//set the date
			if (is_null($date)) {
				$data['Stats']['created']['year'] = date('Y');
				$data['Stats']['created']['month'] = date('m');
				$data['Stats']['created']['day'] = date('d');
				$data['Stats']['created']['hour'] = date('H');
				$data['Stats']['created']['min'] = date('i');
				$data['Stats']['created']['sec'] = date('s');
			} else { 
				$data['Stats']['created'] = date_parse($date) ;
 				$data['Stats']['created']['hour'] = date('H');
				$data['Stats']['created']['min'] = date('i');
				$data['Stats']['created']['sec'] = date('s');
				/*TODO finihs this up as well as provding date regexp 
				 *  if ($data['Stats']['created']['error_count'] != 0) //date format unrecognized
				$this->Rest->error(__('The date you supplied is not recognized. Please provide date yyyy-mm-dd', true));
				$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
				$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId); */
			}

			
			//prepare the stats data
			$data = array('Stats' => array(
					'created' => $date,
					'phone_id' => $phone['Phones']['id'],
					'location_id' => $facility['Locations']['id'],
					'unit_id' => $newUnitId,
					'messagereceived_id' => $messagereceivedId,
					'status_id' => 2, //2 is assign
			) );
			$data['Stats']['quantity'] = 1;
			$this->loadModel('Stats');
			$this->Stats->create();
			if (!$this->Stats->save($data)) {
				//if stats can't be saved try deleting the unit as well
				$this->Units->delete($newUnitId);
				$this->Rest->error(__('Record could not be saved: 10104', true));
				$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			}
			
			$this->Rest->info(__('Thank you. Your report was successfuly submitted.', true));
			//$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			$stat = $this->Stats->findById($this->Stats->id);
			$this->set(compact('stat'));
		}
	}
	
	/*
	 * Assignment when facility and patient aren't supplied
	* also receive if the same conditions above are valid
	*/
	function assign($phoneNumber, $unitNumber, $created = null){
		//see if kit is opened or closed
		$unit = $this->findUnit($unitNumber);
		if ($this->isUnusedUnit($unit['Units']['id'])){
			//we are assigning it to genereic patient
			$patientNumber = 999999;
			$this->assignToPatient($phoneNumber, $unitNumber, $patientNumber, $created);
		} else {
			//we are receiving the unit
			$this->receiveUnit($phoneNumber, $unitNumber, $created);
		}
	}
	
	private function findPhone($phonenumber = null) {
		if ($this->Rest->isActive()) {
			$this->Rest->postData = $this->data;
			//validate phone numbers optional + and max 11 digits after it
			if (!preg_match("/\+?[0-9]{4,11}/", $phonenumber)){
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
					$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
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

	/*
	 * Insert the received message
	 */
	private function setReceived($argsList, $phoneId){
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
	
	/*
	 * Insert the sent message
	 */
	private function setSent($message, $phoneId, $messagereceivedsId ){
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

	/*
	 * If something went wrong abort 
	 */
	private function checkFeedback($argsList, $phoneId, &$messagereceivedId ){
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

	/*
	 * Check if the user is authorised to dispanse this kit in terms the location
	* on hi phone number assignment
	*/
	private function checkKitFacility($kitId, $facilityId){
		$lastFacilityPatient = $this->getUnitCurrentFacility($kitId);
		if ($lastFacilityPatient[0] != $facilityId){ //different location see if it is child facility
			$children = array();
			$this->findLocationChildren($facilityId, $children);
			if ( !in_array($lastFacilityPatient[0], $children)) {
				$this->Rest->error(__('This unit does not belong to your facility and cannot be dispensed: 10107', true));
				//$this->Rest->abort();
			} else {
				return true;
			}
		}
	}

	/*
	 * Find patient
	*/
	private function findPatient($patientNumber) {
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
	
	/*
	 * Is patient consent
	*/
	function patientConsent($phoneNumber = null, $patientNumber = null) {
		if ($this->Rest->isActive()) {
			if (is_null($phoneNumber) || is_null($patientNumber))
				$this->rejectMessage("lessParams");
				
			$phone = $this->findPhone($phoneNumber);
			$argsList = func_get_args();
			$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			
			$this->loadModel('Patients');
			$conditions = array("Patients.number " => $patientNumber);
			$patient = $this->Patients->find('first', array('conditions' => $conditions, 'callbacks' => false));
			if (isset($patient['Patients']['id'])) {
				//update consent status
				$data = array('Patients' =>
						array ('id' => $patient['Patients']['id'],
								'consent' => 1,
						)
				);
				if (!$this->Patients->save($data)) {
					$this->Rest->error(__('Record could not be saved: 10110', true));
					$this->Rest->abort();
				} else {
					$this->Rest->info(__('Patient has been updated', true));
				}
			} else {
				$data = array('Patients' =>
							array ('number' => $patientNumber,
									'created' => date("Y-m-d H:i:s"),
									'consent' => 1,
									'location_id' =>  $phone['Phones']['location_id'],
									)
						);
				$this->Patients->create();
				if (!$this->Patients->save($data)) {
					$this->Rest->error(__('Record could not be saved: 10110', true));
					$this->Rest->abort();
				} else {
					$this->Rest->info(__('Patient has been created', true));
				}
				
			}
			$this->set(compact('patient'));
		}
	}
	
	/*
	 * Find unit
	 */
	private function findUnit($unitNumber) {
		if ($this->Rest->isActive()) {
			$this->loadModel('Units');
			$this->loadModel('Stats');
			//discarded
			$discarded = $this->Stats->find('list',  array ('conditions' => array('Stats.status_id = 3'
			),
					'fields' => array('unit_id'), 'callbacks' => false) );
			
			$conditions = array("Units.code " => $unitNumber,
						((is_null($discarded) || empty($discarded))?'':'Units.id not in (' . implode(",",$discarded) . ")"),
												);
			
			$unit = $this->Units->find('first', array('conditions' => $conditions, 'callbacks' => false));
			if (!isset($unit['Units']['id'])) {
				$this->Rest->error(__('Unit does not exist: ' , true) . $unitNumber);
			}
			$this->set(compact('unit'));
			return $unit;
		}
	}
	
	/*
	 * Find facility
	 */
	private function findFacility($facilityShortname) {
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
	/*
	 * Errors not caught come here and messages get rejected
	 */
	function rejectMessage($what = null){
		if ($what == 'moreActions') {
			$this->Rest->error(__('Too many keywords were supplied. Message not processed: 10108', true));
		} else if ($what == 'lessParams') {
			$this->Rest->error(__('Missing parameter. Message not processed: 10109', true));
		} else if ($what == 'lessMissParams') {
			$this->Rest->error(__('Missing or incorrect parameter. Message not processed: 10110', true));
		} else {
			$this->Rest->error(__('Something went wrong. Your message was not processed: 10999', true));
		}
		$this->Rest->abort();
	}
}
?>
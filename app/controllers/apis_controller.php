<?php
class ApisController extends AppController {

	var $name = 'Apis';
	var $uses = array();
	//var $helpers = array('Html', 'Form');
	var $components = array('RequestHandler', 'Access', 'Rest.Rest' => array(
			'catchredir' => true, // Recommended unless you implement something yourself
			'debug' => 2,
			'actions' => array( //expose these actions and those variable to rest
					//'discardUnit' => array('extract' => array('stat'),),
				//	'receiveUnit' => array('extract' => array('stat'),),
				//	'assignToFacility' => array('extract' => array('stat'),),
				//	'assignToPatient' => array('extract' => array('stat'),),
				//	'createUnit' => array('extract' => array('unit'),),
			),
	),);

	/*
	 * Discard open unit
	* required $phoneNumber, $unitNumber allowed nulls only to return
	* a nice message if not supplied instead of breaking the app
	*/
	function discardUnit($phoneNumber = null, $unitNumber = null, $facilityShortname = null, $date = null) {
		$argsList = func_get_args();
		$this->log("E " . implode(", ",$argsList), 'api');
		if ($this->Rest->isActive()) {
			if (is_null($phoneNumber) || is_null($unitNumber))
				$this->rejectMessage("lessParams");
			$phone = $this->findPhone($phoneNumber);
			//TODO
			//figure out why setReceived doesn't work when call is nested from checkFeedback
			$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'], __('Discard', true))	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			//find the unit
			$unit = $this->findUnit($unitNumber);
			//$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			//if facility is not set use the phone's assigned facility
			if (is_null($facilityShortname) || $facilityShortname == '_'){
				$facility['Locations']['id'] = $phone['Phones']['location_id'];
			} else {
				$facility = $this->findFacility($facilityShortname)	;
				$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			}
			//set the date
			$matchedDate = NULL;
			$what = "/\b([0-9]{2}|[0-9]{4})[\D]([0-9]{1,2})[\D]([0-9]{1,2})\b/";
			preg_match($what, $date, $matchedDate);
			$data['Stats']['created']['year'] = $matchedDate[1];
			$data['Stats']['created']['month'] = $matchedDate[2];
			$data['Stats']['created']['day'] = $matchedDate[3];
			//timestamp is also needed
 			$data['Stats']['created']['hour'] = date('H');
			$data['Stats']['created']['min'] = date('i');
			$data['Stats']['created']['sec'] = date('s');
			
			$isUnused = $this->isUnusedUnit($unit['Units']['id']);
			if (!$isUnused) {
				$this->Rest->error(__('This kit is already open.', true));
				$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			}
					
			$isDiscarded = $this->isDiscardedUnit($unit['Units']['id'], $this->dateArrayToString($data['Stats']['created']));
			if (!$isDiscarded) {
				$this->Rest->error(__('This kit is already discarded', true));
				//$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
				$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			}
			
			$this->checkValidDate($data['Stats']['created'], $unit['Units']['id']);
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);

			$this->checkKitFacility($unit['Units']['id'], $facility['Locations']['id'], $this->dateArrayToString($data['Stats']['created']));
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			
			$lastFacilityWithKit = $this->findLastUnitFacility($unit['Units']['id'], $this->dateArrayToString($data['Stats']['created']));
			//if assiging the same unit to the same facility don't increment quantity
			
			if ( $lastFacilityWithKit != -1 && $this->isUnusedUnit($unit['Units']['id'], $this->dateArrayToString($data['Stats']['created']))) {
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
					'created' => $data['Stats']['created'],
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
			$this->Rest->info(__('Thank you. Your report was successfully submitted.', true));
			//$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			$stat = $this->Stats->findById($this->Stats->id);
			//$this->set(compact('stat'));
		}
	}

	/*
	 * Receive from facility or patient
	* required $phoneNumber , $unitNumber
	*/
	function receiveUnit($phoneNumber = null, $unitNumber = null, $facilityShortname = null,$date = null) {
		$argsList = func_get_args();
		$this->log("R " . implode(", ",$argsList), 'api');
		if ($this->Rest->isActive()) {
			$this->disableCache();
			if (is_null($phoneNumber) || is_null($unitNumber))
				$this->rejectMessage("lessParams");
				
			$phone = $this->findPhone($phoneNumber);

			$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'], __('Receive', true))	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			//find the unit
			$unit = $this->findUnit($unitNumber);
			//$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			//if facility is not set use the phone's assigned facility
			if (is_null($facilityShortname) || $facilityShortname == '_')
				$facility['Locations']['id'] = $phone['Phones']['location_id'];
			else
				$facility = $this->findFacility($facilityShortname);
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
		
			//set the date
			$matchedDate = NULL;
			$what = "/\b([0-9]{2}|[0-9]{4})[\D]([0-9]{1,2})[\D]([0-9]{1,2})\b/";
			preg_match($what, $date, $matchedDate);
			$data['Stats']['created']['year'] = $matchedDate[1];
			$data['Stats']['created']['month'] = $matchedDate[2];
			$data['Stats']['created']['day'] = $matchedDate[3];
			//timestamp is also needed
	 		$data['Stats']['created']['hour'] = date('H');
			$data['Stats']['created']['min'] = date('i');
			$data['Stats']['created']['sec'] = date('s');
			$this->checkValidDate($data['Stats']['created'], $unit['Units']['id']);
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			
			$isDiscarded = $this->isDiscardedUnit($unit['Units']['id'], $this->dateArrayToString($data['Stats']['created']));
			if (!$isDiscarded) {
				$this->Rest->error(__('This kit is already discarded', true));
				$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			}
			$this->loadModel('Stats');
			//TODO arent this two call identical?
			$currentFacilityPatient = $this->getUnitCurrentFacility($unit['Units']['id'],true, $this->dateArrayToString($data['Stats']['created']));
			$lastFacilityWithKit = $this->findLastUnitFacility($unit['Units']['id'], $this->dateArrayToString($data['Stats']['created']));
				
			$wasWithPatient = $this->Stats->find('list',  array ('conditions' => array('patient_id is not null',
																	'unit_id' => $unit['Units']['id'],
																	'created <\'' . $this->dateArrayToString($data['Stats']['created']) . '\''
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
						$messagereceivedId,
						$facility['Locations']['id']
				);
			//prepare the stats data
			$data = array('Stats' => array(
					'created' => $data['Stats']['created'],
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
			//$stat = $this->Stats->findById($this->Stats->id);
			//$this->set(compact('stat'));
				
			$this->Rest->info(__('Thank you. Your report was successfully submitted.', true));
			//$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
		}
	}

	/* Assign unit to patient
	 * Required $phoneNumber, $unitNumber, $patientNumber,
	*/
	function assignToPatient($phoneNumber = null, $unitNumber = null, $patientNumber = null, $givenDate = null) {
		$argsList = func_get_args();
		$this->log("A " . implode(", ",$argsList), 'api');
		if ($this->Rest->isActive()) {
			if (is_null($phoneNumber) || is_null($unitNumber) || is_null($patientNumber))
				$this->rejectMessage("lessParams");

			$phone = $this->findPhone($phoneNumber);
			
			$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'], __('Assign', true))	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			//find the unit
			$unit = $this->findUnit($unitNumber);
			//$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			
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
		
			$data = NULL;
			//set the date
			$what = "/\b([0-9]{2}|[0-9]{4})[\D]([0-9]{1,2})[\D]([0-9]{1,2})\b/";
			preg_match($what, $givenDate, $matchedDate);
			$data['Stats']['created']['year'] = $matchedDate[1];
			$data['Stats']['created']['month'] = $matchedDate[2];
			$data['Stats']['created']['day'] = $matchedDate[3];
			//timestamp is also needed
 			$data['Stats']['created']['hour'] = date('H');
			$data['Stats']['created']['min'] = date('i');
			$data['Stats']['created']['sec'] = date('s');
			$this->checkValidDate($data['Stats']['created'], $unit['Units']['id']);
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			
			$isUnused = $this->isUnusedUnit($unit['Units']['id'], $this->dateArrayToString($data['Stats']['created']));
			if (!$isUnused) {
				$this->Rest->error(__('This kit is open cannot be assigned to patient', true));
				//$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
				$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			}
			$isDiscarded = $this->isDiscardedUnit($unit['Units']['id']);
			if (!$isDiscarded) {
				$this->Rest->error(__('This kit is already discarded', true));
				$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			}
			//compare the user facility and children thereof to the kit current facility
			$this->checkKitFacility($unit['Units']['id'], $facility['Locations']['id'], $this->dateArrayToString($data['Stats']['created']));
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			//datetime is needed for patient check
			if ($this->isPatientWithKit($patient['Patients']['id'], $this->dateArrayToString($data['Stats']['created']))) {
				$this->Rest->error(__('Patient already with kit. Not processed: 10115', true));
				$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			}
			//adjust the quantity
			$lastFacilityWithKit = $this->findLastUnitFacility($unit['Units']['id'], $this->dateArrayToString($data['Stats']['created']));

			//$this->log($lastFacilityWithKit . " " . $patientNumber . " " . $facility['Locations']['id'] );
			//adjust the quantities
			if ($lastFacilityWithKit != $facility['Locations']['id'] && $lastFacilityWithKit != -1) {
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
			} else if ($lastFacilityWithKit == $facility['Locations']['id']){
				//this is valid only for back patietn assignment from same facility
				$this->updateBackEntry($data['Stats']['created'], $unit['Units']['id'], $facility['Locations']['id'], $patientNumber );
			}
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
			/* 
			 * TODO Why was this here
			 * $lastFacilityWithKit = $this->findLastUnitFacility($unit['Units']['id'], $this->dateArrayToString($data['Stats']['created']));
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
				); */
			$this->Rest->info(__('Thank you. Your report was successfully submitted.', true));
			//$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);

			//$this->set(compact('stat'));
		}
	}

	/*
	 * Assign unit to facility
	* Required $phoneNumber, $unitNumber, $facilityShortname
	*/
	function assignToFacility($phoneNumber = null, $unitNumber = null, $facilityShortname = null, $date = null) {
		$argsList = func_get_args();
		$this->log("A " . implode(", ",$argsList), 'api');
		if ($this->Rest->isActive()) {
			$this->disableCache();
			if (is_null($phoneNumber) || is_null($unitNumber) || is_null($facilityShortname)) {
				$this->rejectMessage("lessParams");
				//$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
				//$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			}
				
			$phone = $this->findPhone($phoneNumber);
			$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'], __('Assign', true))	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			//find the unit
			$unit = $this->findUnit($unitNumber);
			//$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			//find facility
			$facility = $this->findFacility($facilityShortname);
			//$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			/* TODO not sure if this is necessary as kits may travel cross facility tree
			 * $this->checkKitFacility($unit['Units']['id'], $facility['Locations']['id']);
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId); */
			//set the date
			$matchedDate = NULL;
			$what = "/\b([0-9]{2}|[0-9]{4})[\D]([0-9]{1,2})[\D]([0-9]{1,2})\b/";
			preg_match($what, $date, $matchedDate);
			$data['Stats']['created']['year'] = $matchedDate[1];
			$data['Stats']['created']['month'] = $matchedDate[2];
			$data['Stats']['created']['day'] = $matchedDate[3];
			//timestamp is also needed
 			$data['Stats']['created']['hour'] = date('H');
			$data['Stats']['created']['min'] = date('i');
			$data['Stats']['created']['sec'] = date('s');
			$this->checkValidDate($data['Stats']['created'], $unit['Units']['id']);
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			
			$isUnused = $this->isUnusedUnit($unit['Units']['id'], $this->dateArrayToString($data['Stats']['created']));
			if (!$isUnused) {
				$this->Rest->error(__('This kit is open and cannot be assigned to facility', true));
				//$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
				$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			}
			
			$isDiscarded = $this->isDiscardedUnit($unit['Units']['id'], $this->dateArrayToString($data['Stats']['created']));
			if (!$isDiscarded) {
				$this->Rest->error(__('This kit is already discarded', true));
				$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			}
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
					'created' => $data['Stats']['created'],
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
				
			$this->Rest->info(__('Thank you. Your report was successfully submitted.', true));
			//$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			//$stat = $this->Stats->findById($this->Stats->id);
			//$this->set(compact('stat'));
		}
	}

	/*
	 * create unit and first associated assignment record
	 * required $phoneNumber, $unitNumber, $facilityShortname
	*/
	function createUnit($phoneNumber = null, $unitNumber = null, $facilityShortname = null, $date = null) {
		$argsList = func_get_args();
		$this->log("CR " . implode(", ",$argsList), 'api');
		if ($this->Rest->isActive()) {
			$this->disableCache();
			if (is_null($phoneNumber) || is_null($unitNumber) || is_null($facilityShortname))
				$this->rejectMessage("lessParams");
			
			$phone = $this->findPhone($phoneNumber);
			$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'], __('Create', true))	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			
			//find facility
			$facility = $this->findFacility($facilityShortname);
			//$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			
			//first create the unit
			$this->loadModel('Units');
			$unit = $this->Units->find('list', array('conditions' => array('Units.code' => $unitNumber), 
										'callbacks' => 'false'));		
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
			
			$this->loadModel('UnitsItem');
			$unitItems = array('UnitsItem' => array('unit_id'=> $newUnitId, 'item_id' => 1 )); //TODO this should be changed if items chagnes
			$this->UnitsItem->create();
			if (!$this->UnitsItem->save($unitItems)) {
				$this->Rest->error(__('Record could not be saved: 10302', true));
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
			} else { 
				$matchedDate = NULL;
				$what = "/\b([0-9]{2}|[0-9]{4})[\D]([0-9]{1,2})[\D]([0-9]{1,2})\b/";
				preg_match($what, $date, $matchedDate);
				$data['Stats']['created']['year'] = $matchedDate[1];
				$data['Stats']['created']['month'] = $matchedDate[2];
				$data['Stats']['created']['day'] = $matchedDate[3];
				//timestamp is also needed
 				$data['Stats']['created']['hour'] = date('H');
				$data['Stats']['created']['min'] = date('i');
				$data['Stats']['created']['sec'] = date('s');
				$this->checkValidDate($data['Stats']['created']);
				$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			}

			
			//prepare the stats data
			$data = array('Stats' => array(
					'created' => $data['Stats']['created'],
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
			
			$this->Rest->info(__('Thank you. Your report was successfully submitted.', true));
			//$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
			$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
			$stat = $this->Stats->findById($this->Stats->id);
			//$this->set(compact('stat'));
		}
	}
	
	/*
	 * Assignment when facility and patient aren't supplied
	* also receive if the same conditions above are valid
	*/
	function assign($phoneNumber, $unitNumber, $created = null){
		$this->disableCache();
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
			} else 
			//don't allow inactive phones to report
			if (isset($phone['Phones']['active']) && $phone['Phones']['active'] == 0) {
				$this->Rest->error(__('This phone has not been activated. Please request activation.', true));
			} else
			//don't allow deleted phones to report
			if (isset($phone['Phones']['deleted']) && $phone['Phones']['deleted'] == 1) {
				$this->Rest->error(__('This phone has been removed from the system and cannot report.', true));
			} else
			//check that phone is assigned to a facility
			if (!isset($phone['Phones']['location_id']) ) {
				$this->Rest->error(__('This phone has not been assigned to a facility. Please request assignment.', true));
				//$this->Rest->abort();
			}
			//$this->set(compact('phone'));
			return $phone;
		}
	}

	/*
	 * Insert the received message
	 */
	private function setReceived($argsList, $phoneId, $action = null){
		if ($this->Rest->isActive()) {
			$this->loadModel('Messagereceiveds');
			$date = date("Y-m-d H:i:s");
			$data = array('Messagereceiveds' => array(
					'phone_id' => $phoneId,
					'created' => $date,
					'rawmessage' => $action .implode(",", $argsList),
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
	 * Check if the user is authorised to dispanse this kit in terms of the location
	* on the phone number assignment
	*/
	private function checkKitFacility($kitId, $facilityId, $date = null){
		$lastFacilityPatient = $this->getUnitCurrentFacility($kitId, true, $date);
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
			$consent = $this->Patients->find('first', array('conditions' => $conditions, 
						'fields' => array('Patients.id','Patients.consent'), 'callbacks' => false));
			if (isset($patient['Patients']['consent']) && $patient['Patients']['consent'] == 0) {
				$this->Rest->error(__('Patient consent missing: ' , true) . $patientNumber);
				//$this->Rest->abort();
			}
			//$this->set(compact('patient'));
			return $patient;
		}
	}
	
	/*
	 * Is patient consent
	*/
	function patientConsent($phoneNumber = null, $patientNumber = null) {
		$argsList = func_get_args();
		$this->log("C " . implode(", ",$argsList), 'api');
		if ($this->Rest->isActive()) {
			if (is_null($phoneNumber) || is_null($patientNumber))
				$this->rejectMessage("lessParams");
				
			$phone = $this->findPhone($phoneNumber);
			$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'] , __('Consent', true))	;
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
					$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
				} else {
					$this->Rest->info(__('Patient has been created', true));
					$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
				}
				
			}
			//$this->set(compact('patient'));
		}
	}
	
	/*
	 * Find unit
	 */
	private function findUnit($unitNumber, $date=null) {
		if ($this->Rest->isActive()) {
			$this->loadModel('Units');
			$this->loadModel('Stats');
			//discarded
			$discarded = $this->Stats->find('list',  array ('conditions' => array('Stats.status_id = 3',
																				'Stats.created <\'' . $date . '\''
			),
					'fields' => array('unit_id'), 'callbacks' => false) );
			
			$conditions = array("Units.code " => $unitNumber,
						((is_null($discarded) || empty($discarded))?'':'Units.id not in (' . implode(",",$discarded) . ")"),
												);
			
			$unit = $this->Units->find('first', array('conditions' => $conditions, 'callbacks' => false));
			if (!isset($unit['Units']['id'])) {
				$this->Rest->error(__('Unit does not exist: ' , true) . $unitNumber);
			}
			//$this->set(compact('unit'));
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
			//$this->set(compact('facility'));
			return $facility;
		}
	}
	/*
	 * Errors not caught come here and messages get rejected
	 */
	function rejectMessage($phoneNum, $what = null, $msg = null){
		$argsList = func_get_args();
		$this->log("rejectMessage " . $msg ." " . $phoneNum, 'api');
		if ($what == 'moreActions') {
			$this->Rest->error(__('Too many keywords were supplied. Message not processed: 10108', true));
		} else if ($what == 'lessParams') {
			$this->Rest->error(__('Missing parameter. Message not processed: 10109', true));
		} else if ($what == 'msgUnrecognized') {
			$this->Rest->error(__('We couldn not interprete your message. Plese check and resend: 10112', true));
		} else if ($what == 'lessMissParams') {
			$this->Rest->error(__('Missing or incorrect parameter. Message not processed: 10111', true));
		} else if ($what == 'noUnit') {
			$this->Rest->error(__('Missing unit. Message not processed: 10113', true));
		} else {
			$this->Rest->error(__('Something went wrong. Your message was not processed: 10999', true));
		}
		Configure::load('options');
		$length = Configure::read('Phone.length');
		$argsList = array();
		$this->loadModel('Phones');
		$phoneNum = substr($phoneNum, -$length);
		$conditions = array("Phones.phonenumber LIKE " => "%%" . $phoneNum . "%%");
		$phone = $this->Phones->find('first', array('conditions' => $conditions, 'callbacks' => false));
		$messagereceivedId = $this->setReceived($argsList, $phone['Phones']['id'])	;
		$this->checkFeedback($argsList, $phone['Phones']['id'], $messagereceivedId);
	}
	
	/*
	 * Only valid dates accepted
	 */
	private function checkValidDate($currDate, $unitId = null) {
		if ($this->Rest->isActive()) {
			$isValidDate = checkdate( $currDate['month'], $currDate['day'], $currDate['year'] );
			$currDate = date('Y-m-d H:i:s',strtotime($this->dateArrayToString($currDate)));
			if (!$isValidDate) {
				$this->Rest->error(__('Invalid date please use yyyy-mm-dd ' , true) );
			/* } else if ($this->dateArrayToString($currDate) > date("Y-m-d H:i:s") ) {
				$this->Rest->error(__('Date cannot be in the future.' , true) ); */
			} 
			if (!is_null($unitId) && $isValidDate){
				$earlyDate = NULL;
				$isEarlyCreated = FALSE;
				$earlyDate = $this->getUnitFirstDate($unitId);
				if ($earlyDate != -1 && $earlyDate > $currDate){
					$isEarlyCreated = TRUE;
				}
				if ($isEarlyCreated)
					$this->Rest->error(__('Date is prior to kit creation.' , true));
			}
		}
	}
}
?>
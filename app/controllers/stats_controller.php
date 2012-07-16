<?php
class StatsController extends AppController {

	var $name = 'Stats';
	var $helpers = array('Html', 'Crumb', 'Javascript', 'Ajax', 'UpdateFile', 'GoogleChart', 'DatePicker' );
	var $components = array('RequestHandler', 'Access');

   function beforeRender() {
			if ($this->action == 'view'){
					if (!in_array($this->viewVars['stat']['Stat']['location_id'], $this->Session->read("userLocations") )) { //view
						$this->Session->setFlash('You are not allowed to access this record.' . $l, 'flash_failure'); 
						$this->redirect( '/stats/index', null, false);
					} 
			}
			if ($this->action == 'edit'){
					if (!in_array($this->data['Stat']['location_id'], $this->Session->read("userLocations") )) { //edit
						$this->Session->setFlash('You are not allowed to access this record.' . $l, 'flash_failure'); 
						$this->redirect( '/stats/index', null, false);
					} 
			}
   }	
   

	function index() {
		$this->Stat->recursive = 2;
		$this->paginate['Stat'] = array('order' => 'Stat.created DESC', 'conditions' => array ('status_id in (1,2,3)'));
		$search = (empty($this->data['Search']['search'])?(isset($this->passedArgs[0])?$this->passedArgs[0]:$this->data['Search']['search']):$this->data['Search']['search']);
		if (!empty($search) ) {
				$this->paginate['Stat'] = array('order' => 'Stat.created DESC',
										'conditions'=>array( 
											"OR" => array("Location.name LIKE "=>"%".$search."%", 
													//"Unit.name LIKE" => "%".$search."%", 
													"Unit.code LIKE" => "%".$search."%", 
													"Stat.quantity LIKE" => "%".$search."%",
													"User.name LIKE" => "%".$search."%",
													"Phone.name LIKE" => "%".$search."%")
									));
		} 
		
		$this->set('stats', $this->paginate());
		$statuses = $this->Stat->Status->find('list', array('conditions' => array('id in (1,2,3)')));
		$locations = $this->Stat->Location->find('list', array('callbacks' =>false));
		$patients = $this->Stat->Patient->find('list', array('callbacks' =>false));
		$units = $this->Stat->Unit->find('list');
		$this->set(compact( 'statuses', 'locations', 'patients', 'units'));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid stat', true));
			$this->redirect(array('action' => 'index'));
		}
		$stat =  $this->Stat->read(null, $id);
		$this->set('stat', $stat);
	}
	
	private function add() {
		if (!empty($this->data)) {
			//print_r($this->data['Stat']);
			if (isset($this->data['Stat']['patient_id']) && $this->data['Stat']['patient_id'] != '' && $this->data['Stat']['quantity'] != 1) {
				$this->Stat->invalidate('quantity', __('Only one kit can be delivered to patients at a time.', true));
				$this->Session->setFlash(__('The update could not be saved. Please, try again.', true));
			} else if (isset($this->data['Stat']['patient_id']) &&  $this->data['Stat']['patient_id'] != '' && $this->data['Stat']['sent_to'] != '') {
				$this->Stat->invalidate('patient_id', __('You can select only patient or receiving facility', true));
				$this->Stat->invalidate('sent_to', __('You can select receiving facility or patient', true));
				$this->Session->setFlash(__('The update could not be saved. Please, try again.', true));
			} else {
				if (isset($this->data['Stat']['location_id']) && isset($this->data['Stat']['unit_id']) && isset ($this->data['Stat']['status_id'])) {
					$query = 'SELECT quantity_after from stats st ';
					$query .= ' WHERE location_id=' . $this->data['Stat']['location_id'];
					$query .= ' AND unit_id='. $this->data['Stat']['unit_id'];
					$query .= ' AND id = (select max(id) from stats s  WHERE s.location_id=' . $this->data['Stat']['location_id'] . ' AND s.unit_id='. $this->data['Stat']['unit_id'] . ')';
					
					$result = $this->Stat->query($query);
					
				} else {
					$this->Session->setFlash(__('The update could not be saved. Please, try again.', true));
				}
				$this->Stat->create();
				if ($this->Stat->save($this->data)) {
					$this->Session->setFlash('The update has been saved', 'flash_success');
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('The update could not be saved. Please, try again.', true));
				}
			}
		}
		//$items = $this->Stat->Item->find('list');
		
		$messagereceived = $this->Stat->Messagereceived->find('all', 
						array('callbacks' => false, 'conditions' => array('Phone.location_id in (' . implode(",", $this->Session->read("userLocations")) . ')')) ); 
		foreach ($messagereceived as $rr) {
			$messagereceiveds[$rr['Messagereceived']['id']] = $rr['Messagereceived']['rawmessage'];
		}
		//$phones = $this->Stat->Phone->find('list',  array ('conditions' => array('Phone.deleted = 0','Phone.location_id is not null'))); 
		$users = $this->Stat->User->find('list', array ('conditions' => array('User.id = '. $this->Session->read('Auth.User.id') )) );
		$u = $this->AuthExt->user();
		$locations = $this->Stat->Location->find('list', array('callbacks' =>false, 'conditions' => array('Location.id = ' . $u['User']['location_id'])));
		//$locations = $this->Stat->Location->find('list',  array ('conditions' => array('Location.deleted = 0')));
		//$modifiers = $this->Stat->Modifier->find('list'); 
		$statuses = $this->Stat->Status->find('list', array('conditions' => array('id in (1,2,3)')));
		$locationsp = $this->Stat->Location->find('list', array('callbacks' =>false, 'conditions' => array('Location.deleted = 0')));
		$patients = $this->Stat->Patient->find('list', array('conditions' =>array('consent' => 1)));
		$this->set(compact('locationsp', 'messagereceiveds', 'locations', 'statuses', 'users', 'patients'));

	}
	
	function assignUnits($lastUnits=null){ //2
		if (is_null($lastUnits)) {
			$lastUnits =  $this->Session->read("recentlyUsedUnits");
		}
		if (!empty($this->data)) {
			//add hour minutes seconds field
			$this->data['Stat']['created']['hour'] = date('H');
			$this->data['Stat']['created']['min'] = date('i');
			$this->data['Stat']['created']['sec'] = date('s');
			$isEarlyCreated = FALSE;
			$earlyDate = NULL;
			if (!empty($this->data['Stat']['Unit'])) {
				foreach ($this->data['Stat']['Unit'] as $key => $unit_id) {
					$earlyDate = $this->getUnitFirstDate($unit_id);
					if ($earlyDate != -1 && $earlyDate > $this->dateArrayToString($this->data['Stat']['created']) ) {
						$isEarlyCreated = TRUE;
					}
				}
			}	
			if (empty($this->data['Stat']['Unit']) ) {
				$this->Stat->invalidate('Unit', __('Please select unit(s)' , true));
				$this->Session->setFlash(__('Please select a unit.', true));
			} else if ($isEarlyCreated) {
				$this->Stat->invalidate('created', __('Assignment date is prior to unit creation.' , true));
				$this->Session->setFlash(__('Please select a date greater than: ' . $earlyDate, true));
			} else if ($this->dateArrayToString($this->data['Stat']['created']) > date("Y-m-d H:i:s")) {
					$this->Stat->invalidate('created', __('Assignment date is in the future.' , true));
					$this->Session->setFlash(__('Please select a date that is in the present.', true));
			} else if (count($this->data['Stat']['Unit'])>1 &&  !empty($this->data['Stat']['patient_id'])) {
				$this->Stat->invalidate('Unit', __('Please select only one unit' , true));
				$this->Session->setFlash(__('You can only select one unit when assigning to patient', true));
			} else if (empty($this->data['Stat']['patient_id']) && empty($this->data['Stat']['location_id'])) {
				$this->Stat->invalidate('assignSelect', __('Please select facility or patient' , true));
				$this->Session->setFlash(__('Please select facility or patient', true));
			} else {
				$locationId = $this->data['Stat']['location_id'];
				$patientId = $this->data['Stat']['patient_id'];
				$userId = $this->data['Stat']['user_id'];
				$old = array();
				$old = $this->data;
				$this->data = array();
				$i = 0;
				foreach ($old['Stat']['Unit'] as $key => $unit_id) {
					$this->data['Stat'][$i] = $old['Stat'];
					$this->data['Stat'][$i]['unit_id'] = $unit_id;
					$this->data['Stat'][$i]['patient_id'] = $patientId;
					$this->data['Stat'][$i]['location_id'] = $locationId;
					$this->data['Stat'][$i]['user_id'] = $userId;
					
					$lastFacilityWithKit = $this->findLastUnitFacility($unit_id, $this->dateArrayToString($this->data['Stat'][$i]['created']));
					//if assiging the same unit to the same facility don't increment quantity
					$this->data['Stat'][$i]['quantity'] = (($lastFacilityWithKit === $locationId)?0:1);
					//adjust the quantities only one quantity at a time
					if ($this->data['Stat'][$i]['quantity'] != 0 && $lastFacilityWithKit != -1)
						$this->adjustQuantities(
											$this->data['Stat'][$i]['created'],
											$unit_id,
											'A', 
											(isset($patientId)?0:1), //no need for qty when assigning to patient
											$locationId, 
											$patientId,
											(isset($this->data['Stat'][$i]['phone_id'])?$this->data['Stat'][$i]['phone_id']:NULL),
											$userId,
											NULL
											);		
					unset($this->data['Stat'][$i++]['Unit']);
					
				}	
				$this->Stat->create();
				if ($this->Stat->saveAll($this->data['Stat'])) {
					$this->Session->setFlash('The assignment has been saved', 'flash_success');
					$lastUnits .= (((is_null($lastUnits) || empty($lastUnits))? "":",") .  implode(",", $old['Stat']['Unit']));
					//$this->data = $old;
					$this->Session->write("recentlyUsedUnits", $lastUnits);
				} else {
					if (empty($this->data['Stat']['location_id']) )
						$this->Stat->invalidate('location_id', __('Please select a facility.', true));
					$this->Session->setFlash(__('The assignment could not be saved. Please, try again.', true));
					if (isset($old))
						$this->data = $old;
				}
			}
		}
		//discarded and opened
		$discarded = $this->Stat->find('list',  array ('conditions' => array('OR' => array('Stat.status_id = 3',
																					'Stat.patient_id is not null')
													), 
										'fields' => array('unit_id'), 'callbacks' => false) );
		$discarded = array_unique($discarded);	
		$unitsArray = explode(",", $lastUnits);
		$unitsArray = array_diff($unitsArray, $discarded);
		$unitsArray = array_unique($unitsArray);
		$lastUnits = implode(",", $unitsArray);									
		$units = $this->Stat->Unit->find('list', array('conditions' => 
										array(//'Unit.id not in (' . implode(",",$discarded) . ")",
										((is_null($discarded) || empty($discarded))?'':'Unit.id not in (' . implode(",",$discarded) . ")")
										//((is_null($lastUnits) || empty($lastUnits))?'':'Unit.id not in (' . $lastUnits . ")") 
										)
										));	
		
		$allUnits = $this->Stat->Unit->find('list', array('conditions' => 
										array(((is_null($discarded) || empty($discarded))?'':'Unit.id not in (' . implode(",",$discarded) . ")")) ));
		$userId = $this->Session->read('Auth.User.id');
		$locations = $this->Stat->Location->find('list');
		$patients = $this->Stat->Patient->find('list');//, array('conditions' =>array('consent' => 1)));
		//attach current location to unit
		$unitsFacility = array();
		foreach ($units as $unitId => $unit){
			$latestPatFac = $this->getUnitCurrentFacility($unitId);
			if (!is_null($latestPatFac[0]) && $latestPatFac != -1)
				$unitsFacility[$unitId] = $unit . "(" . $locations[$latestPatFac[0]] .")";
			else if (!is_null($latestPatFac[1]) && $latestPatFac != -1)
				$unitsFacility[$unitId] = $unit . "(" . $patients[$latestPatFac[1]] .")";
			else 
				$unitsFacility[$unitId] = $unit;
			
		}
		$units = $unitsFacility;
		
		$this->set(compact('locations', 'userId', 'units', 'patients', 'assigned', 'lastUnits', 'allUnits'));
		
	}
	
	function receiveUnits($lastUnits=null){ //1
		if (is_null($lastUnits)) {
			$lastUnits =  $this->Session->read("recentlyUsedUnits");
		}
		if (!empty($this->data)) {
			if (empty($this->data['Stat']['Unit']) ) {
				$this->Stat->invalidate('Unit', __('Please select unit(s)' , true));
				$this->Session->setFlash(__('Please select a unit.', true));
			} else if (count($this->data['Stat']['Unit'])>1 &&  !empty($this->data['Stat']['patient_id'])) {
				$this->Stat->invalidate('Unit', __('Please select only one unit' , true));
				$this->Session->setFlash(__('You can only select one unit when assigning to patient', true));
			} else {
				$locationId = $this->data['Stat']['location_id'];
				$patientId = $this->data['Stat']['patient_id'];
				$userId = $this->data['Stat']['user_id'];
				$old = array();
				$old = $this->data;
				$this->data = array();
				$i = 0;
				foreach ($old['Stat']['Unit'] as $key => $unit_id) {
					$this->data['Stat'][$i] = $old['Stat'];
					$this->data['Stat'][$i]['unit_id'] = $unit_id;
					$this->data['Stat'][$i]['patient_id'] = $patientId;
					$this->data['Stat'][$i]['user_id'] = $userId;
					//add hour minutes seconds field
					$this->data['Stat'][$i]['created']['hour'] = date('H');
					$this->data['Stat'][$i]['created']['min'] = date('i');
					$this->data['Stat'][$i]['created']['sec'] = date('s');
					$lastFacilityWithKit = $this->findLastUnitFacility($unit_id, $this->dateArrayToString($this->data['Stat'][$i]['created']));
					
					$wasWithPatient = $this->Stat->find('list',  array ('conditions' => array('patient_id is not null',
																						'unit_id' => $unit_id
													), 
										'fields' => array('unit_id'), 'callbacks' => false) );
					//if receiving the same unit from the same facility don't increment quantity
					$this->data['Stat'][$i]['quantity'] = 
							(($lastFacilityWithKit === $locationId || !empty($wasWithPatient))?0:1);
					//adjust the quantities only one quantity at a time
					if ($this->data['Stat'][$i]['quantity'] != 0 && $lastFacilityWithKit != -1)
						$this->adjustQuantities(
											$this->data['Stat'][$i]['created'],
											$unit_id,
											'R', 
											(isset($patientId)?0:1), //no need for qty when receiving from patient
											$locationId, 
											$patientId,
											(isset($this->data['Stat'][$i]['phone_id'])?$this->data['Stat'][$i]['phone_id']:NULL),
											$userId,
											NULL
											);		
					unset($this->data['Stat'][$i++]['Unit']);
					
				}	
				$this->Stat->create();
				if ($this->Stat->saveAll($this->data['Stat'])) {
					$this->Session->setFlash('The assignment has been saved', 'flash_success');
					$lastUnits .= (((is_null($lastUnits) || empty($lastUnits))? "":",") .  implode(",", $old['Stat']['Unit']));
					//$this->data = $old;
					$this->Session->write("recentlyUsedUnits", $lastUnits);
				} else {
					if (empty($this->data['Stat']['location_id']) )
						$this->Stat->invalidate('location_id', __('Please select a facility.', true));
					$this->Session->setFlash(__('The assignment could not be saved. Please, try again.', true));
					if (isset($old))
						$this->data = $old;
				}
			}
		}
		//discarded and opened
		$discarded = $this->Stat->find('list',  array ('conditions' => array('Stat.status_id = 3'
													), 
										'fields' => array('unit_id'), 'callbacks' => false) );
		$opened = $this->Stat->find('list',  array ('conditions' => array('Stat.patient_id IS NOT NULL '
											),
									'fields' => array('unit_id'), 'callbacks' => false) );
		$openedAndReceived = $this->Stat->find('list',  array ('conditions' => array(
				'Stat.status_id = 1', 
				((is_null($opened) || empty($opened))?'':'Stat.unit_id in (' . implode(",",$opened) . ")")
				
				),
				'fields' => array('unit_id'), 'callbacks' => false) );
		$discarded = array_unique($discarded);		
		$unitsArray = explode(",", $lastUnits);
		$unitsArray = array_diff($unitsArray, $discarded);
		$unitsArray = array_unique($unitsArray);
		$lastUnits = implode(",", $unitsArray);									
		$units = $this->Stat->Unit->find('list', array('conditions' => 
										array(//'Unit.id not in (' . implode(",",$discarded) . ")",
										((is_null($discarded) || empty($discarded))?'':'Unit.id not in (' . implode(",",$discarded) . ")"), 
										((is_null($openedAndReceived) || empty($openedAndReceived))?'':'Unit.id not in (' . implode(",",$openedAndReceived) . ")")
										//((is_null($lastUnits) || empty($lastUnits))?'':'Unit.id not in (' . $lastUnits . ")") 
										)
										));	
		$allUnits = $this->Stat->Unit->find('list', array('conditions' => 
										array(((is_null($discarded) || empty($discarded))?'':'Unit.id not in (' . implode(",",$discarded) . ")")) ));
		$userId = $this->Session->read('Auth.User.id');
		$locations = $this->Stat->Location->find('list');
		$patients = $this->Stat->Patient->find('list');//, array('conditions' =>array('consent' => 1)));
		//attach current location to unit
		$unitsFacility = array();
		foreach ($units as $unitId => $unit){
			$latestPatFac = $this->getUnitCurrentFacility($unitId);
			if (!is_null($latestPatFac[0]) && $latestPatFac != -1)
				$unitsFacility[$unitId] = $unit . "(" . $locations[$latestPatFac[0]] .")";
			else if (!is_null($latestPatFac[1]) && $latestPatFac != -1)
				$unitsFacility[$unitId] = $unit . "(" . $patients[$latestPatFac[1]] .")";
			else
				$unitsFacility[$unitId] = $unit;
				
		}
		$units = $unitsFacility;
		
		$this->set(compact('locations', 'userId', 'units', 'patients', 'assigned', 'lastUnits', 'allUnits'));
		
		
	}

	function discardUnits($lastUnits=null){ //3
		if (is_null($lastUnits)) {
			$lastUnits =  $this->Session->read("recentlyUsedUnits");
		}
		if (!empty($this->data)) {
				$locationId = $this->data['Stat']['location_id'];
				//$patientId = $this->data['Stat']['patient_id'];
				$userId = $this->data['Stat']['user_id'];
				$unit_id = $this->data['Stat']['unit_id'];
				//add hour minutes 
				$this->data['Stat']['created']['hour'] = date('H');
				$this->data['Stat']['created']['min'] = date('i');
				$this->data['Stat']['created']['sec'] = date('s');
				$lastFacilityWithKit = $this->findLastUnitFacility($unit_id, $this->dateArrayToString($this->data['Stat']['created']));
				//if assiging the same unit to the same facility don't increment quantity
				$this->data['Stat']['quantity'] = -1;

				if ( $lastFacilityWithKit != -1 && empty($locationId)) {
						$this->adjustQuantities(
											$this->data['Stat']['created'],
											$unit_id,
											'A', 
											(isset($locationId)?-1:0), //no need for qty when assigning to patient
											$locationId, 
											NULL,
											(isset($this->data['Stat']['phone_id'])?$this->data['Stat']['phone_id']:NULL),
											$userId,
											NULL
											);	
				} else if ($lastFacilityWithKit != -1) {
					$this->data['Stat']['location_id'] = $lastFacilityWithKit;
				} 
					
			$this->Stat->create();
			if ($this->Stat->save($this->data)) {
				$this->Session->setFlash('The unit has been discarded', 'flash_success');
				//$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The unit could not be discarded. Please, try again.', true));
			}
		}
		//discarded and opened
		$discarded = $this->Stat->find('list',  array ('conditions' => array('Stat.status_id = 3'
													), 
										'fields' => array('unit_id'), 'callbacks' => false) );
		$discarded = array_unique($discarded);		
		$unitsArray = explode(",", $lastUnits);
		$unitsArray = array_diff($unitsArray, $discarded);
		$unitsArray = array_unique($unitsArray);
		$lastUnits = implode(",", $unitsArray);									
		$units = $this->Stat->Unit->find('list', array('conditions' => 
										array(//'Unit.id not in (' . implode(",",$discarded) . ")",
										((is_null($discarded) || empty($discarded))?'':'Unit.id not in (' . implode(",",$discarded) . ")")
										//((is_null($lastUnits) || empty($lastUnits))?'':'Unit.id not in (' . $lastUnits . ")") 
										)
										));	
		$allUnits = $this->Stat->Unit->find('list', array('conditions' => 
										array(((is_null($discarded) || empty($discarded))?'':'Unit.id not in (' . implode(",",$discarded) . ")")) ));
		$userId = $this->Session->read('Auth.User.id');
		$locations = $this->Stat->Location->find('list');
		$patients = $this->Stat->Patient->find('list');//, array('conditions' =>array('consent' => 1)));
		
		$this->set(compact('locations', 'userId', 'units', 'patients', 'assigned', 'lastUnits', 'allUnits'));
	}
	
	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid update', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->data['Stat']['patient_id'] != '' && $this->data['Stat']['sent_to'] != '') {
				$this->Stat->invalidate('patient_id', __('You can select only patient or receiving facility', true));
				$this->Stat->invalidate('sent_to', __('You can select receiving facility or patient', true));
				$this->Session->setFlash(__('The update could not be saved. Please, try again.', true));
			} else { 
				if ($this->Stat->save($this->data)) {
					$this->Session->setFlash('The update has been saved', 'flash_success');
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('The update could not be saved. Please, try again.', true));
				}
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Stat->read(null, $id);
		}

		$units = $this->Stat->Unit->find('list');
		
		$messagereceived = $this->Stat->Messagereceived->find('all', 
						array('callbacks' => false, 'conditions' => 'Phone.location_id in (' . implode(",", $this->Session->read("userLocations")) . ')' ) ); 
		foreach ($messagereceived as $rr) {
			$messagereceiveds[$rr['Messagereceived']['id']] = $rr['Messagereceived']['rawmessage'];
		}
		$phones = $this->Stat->Phone->find('list',  array ('conditions' => array('Phone.deleted = 0','Phone.location_id is not null'))); 
		if (isset($this->data['Stat']['user_id'] ))
			$users = $this->Stat->User->find('list', array ('conditions' => 
						array('User.id IN ('. $this->Session->read('Auth.User.id') . ", " . $this->data['Stat']['user_id'] .  " )" )) );
		else 
			$users = $this->Stat->User->find('list', array ('conditions' => 
						array('User.id ='. $this->Session->read('Auth.User.id') )) );
		//$locationsp = $this->Stat->Location->find('list', array('callbacks' =>false, 'conditions' => array('Location.deleted = 0')));
		$patients = $this->Stat->Patient->find('list', array('conditions' =>array('consent' => 1)));
		$this->set('locations', $this->Stat->Location->find('list', array('conditions' => array('Location.id' => $this->data['Stat']['location_id'] ))));
		//$modifiers = $this->Stat->Modifier->find('list');
		$statuses = $this->Stat->Status->find('list', array('conditions' => array('id in (1,2,3)')));
		$this->set(compact('units', 'messagereceiveds', 'users',  'phones', 'locationsp', 'patients', 'statuses'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for stat', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Stat->delete($id)) {
			$this->Session->setFlash('Report deleted.' . $this->Session->read("modelStat") , 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Report was not deleted', true));
		$this->redirect(array('action' => 'index'));

	}

	function update_units_select() {

			if (isset($this->data['Stat']) && !isset($this->data['Stat']['action'])) {
				//discarded and opened
				$discarded = $this->Stat->find('list',  array ('conditions' => array('OR' => array('Stat.status_id = 3',
																							'Stat.patient_id is not null')
															), 
												'fields' => array('unit_id'), 'callbacks' => false) );
				$discarded = array_unique($discarded);		
				$units = $this->Stat->Unit->find('list', array('conditions' => 
										array(//'Unit.id not in (' . implode(",",$discarded) . ")",
										((is_null($discarded) || empty($discarded))?'':'Unit.id not in (' . implode(",",$discarded) . ")")
										//((is_null($lastUnits) || empty($lastUnits))?'':'Unit.id not in (' . $lastUnits . ")") 
										)
										));
				$lastUnits =  $this->Session->read("recentlyUsedUnits");
				$lastUnits = array_unique($lastUnits);
				$unitsArray = explode(",", $lastUnits);
				$unitsArray = array_diff($unitsArray, $discarded);
				foreach ($unitsArray as $key => $value){
					$removed = 'remove'.$value;
					if (isset($this->data['Stat'][$removed]) ){
						unset($unitsArray[$key]);
					}
				}
				$this->Session->write("recentlyUsedUnits", implode(",", $unitsArray));
				$this->set('options', $units);
				$this->set(compact( 'units', 'lastUnits'));
			}
			if (isset($this->data['Stat']['action']) ) {
				$action = $this->data['Stat']['action'];
				//discarded and opened
				$discarded = $this->Stat->find('list',  array ('conditions' => array('OR' => array('Stat.status_id = 3',
																							'Stat.patient_id is not null')
															), 
												'fields' => array('unit_id'), 'callbacks' => false) );
				$discarded = array_unique($discarded);		
				$units = $this->Stat->Unit->find('list', array('conditions' => 
										array(
										((is_null($discarded) || empty($discarded))?'':'Unit.id not in (' . implode(",",$discarded) . ")")
										)
										));
				switch ($action){
					case '0':
						$lastUnits =  $this->Session->read("recentlyUsedUnits");
						$unitsArray = explode(",", $lastUnits);
						$units = $this->Stat->Unit->find('list', array('conditions' => 
										array('Unit.id not in (' . implode(",",$discarded) . ")",
										((empty($unitsArray) || is_null($unitsArray))?'':'Unit.id in (' . implode(",",$unitsArray) . ")") 
										)
										));
						$this->set('options', $units);
						$this->set(compact( 'units', 'lastUnits'));
						break;
					case '1':
						$lastUnits =  $this->Session->read("recentlyUsedUnits");
						$unitsArray = explode(",", $lastUnits);
						$units = $this->Stat->Unit->find('list', array('conditions' => 
										array('Unit.id not in (' . implode(",",$discarded) . ")",
										((empty($unitsArray) || is_null($unitsArray))?'':'Unit.id not in (' . implode(",",$unitsArray) . ")") 
										)
										));
						$this->set('options', $units);
						$this->set(compact( 'units', 'lastUnits'));
						break;
					case '2':
						//$unitsArray = explode(",", $lastUnits);
						$units = $this->Stat->Unit->find('list', array('conditions' => 
										array(((is_null($discarded) || empty($discarded))?'':'Unit.id not in (' . implode(",",$discarded) . ")"),
										((empty($unitsArray) || is_null($unitsArray))?'':'Unit.id in (' . implode(",",$unitsArray) . ")") 
										)
										));
						$lastUnits = '';
						$this->Session->write("recentlyUsedUnits", $lastUnits);
						$this->set('options', $units);
						$this->set(compact( 'units', 'lastUnits'));
						break;
					default:
						//$unitsArray = explode(",", $lastUnits);
						$units = $this->Stat->Unit->find('list', array('conditions' => 
										array(((is_null($discarded) || empty($discarded))?'':'Unit.id not in (' . implode(",",$discarded) . ")"),
										((empty($unitsArray) || is_null($unitsArray))?'':'Unit.id in (' . implode(",",$unitsArray) . ")") 
										)
										));
						$this->set('options', $units);
						break;
				}
			}
			$this->render('update_select');
	}
	
	function update_facility_select() {

			if (isset($this->data['Stat']['selection']) && $this->data['Stat']['selection'] == "1" ) {
				$options = $this->Stat->Location->find('list');
				$this->set('options', $options);
				$this->set('select', true);
			} else if (isset($this->data['Stat']['selection']) && $this->data['Stat']['selection'] != "1") { //receive
				$this->set('options', array('' => __('---Not Allowed---', true)));
			} 
			$this->render('update_select_empty');
	}
	
	function update_patient_select() {
			if (isset($this->data['Stat']['selection']) && $this->data['Stat']['selection'] == "0" ) {
				$patient = $this->Stat->Patient->find('list', array('conditions' =>array('consent' => 1)));
				$this->set('options', $patient);
				$this->set('select', true);
			} else if (isset($this->data['Stat']['selection']) && $this->data['Stat']['selection'] != "0") { //receive
				$this->set('options', array('' => __('---Not Allowed---', true)));
			} 
			$this->render('update_select_empty');
	}
	
	function update_sent_to_select() {
			if (isset($this->data['Stat']['status_id']) && $this->data['Stat']['status_id'] == 2) { //receive
				$u = $this->AuthExt->user();
				$phone = $this->Stat->Location->find('list', array('callbacks' => false,
									'conditions' => array('Location.id not IN (' .$u['User']['location_id'] .")", 'Location.deleted = 0' )));
				$this->set('options', $phone);
				$this->set('select', true);
			} else if (isset($this->data['Stat']['status_id']) && ($this->data['Stat']['status_id'] == 3 || $this->data['Stat']['status_id'] == 1)) { //receive
				$this->set('options', array('' => __('---Not Allowed---', true)));
			}
			$this->render('update_select_empty');
	}

	
	function aggregatedInventory($strFilter = null) {
		//print_r($this->Stat->Location->Alert->find('all'));
		$locations = $this->Stat->Location->find('list',  
						array('fields' => array('Location.parent_id', 'Location.name', 'Location.id'), 
											array('conditions' => array('id IN ' => implode(",", $this->Session->read("userLocations"))))
								)
						);
		
		$units = $this->Stat->Unit->find('list');
		$this->set(compact('locations', 'units'));

		$listitems = array();

		$this->getKitReport($listitems, $strFilter);
		
		$newlistitems = array();
		foreach ($locations as $loca=> $locaValue) {
			if ( isset($listitems[$loca][0]['locations']['parent'] ) && $listitems[$loca][0]['locations']['parent'] == 0) {
				$newlistitems[$loca] = $listitems[$loca][0]['locations']['parent'];
			}
		}
		
		$this->set('listitems', $listitems);
		
		$parent = null;
		App::import('Controller', 'Users');
		$app = new UsersController;
		$app->constructClasses();
		$u = $app->AuthExt->user();
		
		$app->findTopParent($u['User']['location_id'], $parent, $u['User']['reach'] );
		$report = NULL;
		$this->processKitItems(1, $parent, $locations, $listitems, $items, $report, $app);
		
	
		$this->set('report', $report);
		// echo "<pre>" . print_r ($report, true). "</pre>";
		Configure::load('options');
		$lev = array( 0=>Configure::read('Facility.level0'),
						1=>Configure::read('Facility.level1'),
						2=>Configure::read('Facility.level2'),
						3=>Configure::read('Facility.level3'),
						4=>Configure::read('Facility.level4'),
			);
		$this->set('lev', $lev);
		return $report;


	}
	
	function aggregatedChart($strFilter = null) {
		$allLocations =  $this->Stat->Location->find('list', array('callbacks' =>false, 'conditions' => array('Location.deleted = 0')));
		$this->set('allLocations', $allLocations);
		$report = $this->aggregatedInventory($strFilter);
		return $report;
	}
	
	function facilityInventory($strFilter = null) {
		$allLocations =  $this->Stat->Location->find('list', array('callbacks' =>false, 'conditions' => array('Location.deleted = 0')));
		$this->set('allLocations', $allLocations);
		$this->aggregatedInventory($this->data['Search']['search']);
	}
	
	 function graphTimeline() {
		$locations = $this->Stat->Location->find('list',  array('fields' => array('Location.parent_id', 'Location.name', 'Location.id'), array('conditions' => array('id IN ' => implode(",", $this->Session->read("userLocations"))))));

		$units = $this->Stat->Unit->find('list');
		$this->set(compact('locations', 'units'));

		$listitems = array();
		
		// foreach ($locations as $loc)
		// {
		$listitems = $this->getGraphTimelineReport();
		// }
		
		$graphURL = $this->buildGraphURL($listitems);
		$this->set('graphURL', $graphURL);
		
		return $graphURL;
	}
	function mismatchedDeliveries($strFilter = null){
		$allLocations =  $this->Stat->Location->find('list', array('callbacks' =>false, 'conditions' => array('Location.deleted = 0')));
		$this->set('allLocations', $allLocations);
		$this->aggregatedInventory($this->data['Search']['search']);
	}
	
	function kitsInTransit($strFilter = null){
		$allLocations =  $this->Stat->Location->find('list', array('callbacks' =>false, 'conditions' => array('Location.deleted = 0')));
		$this->set('allLocations', $allLocations);
		$this->aggregatedInventory($this->data['Search']['search']);
	}
	
	function kitsExpired($strFilter = null){
		$allLocations =  $this->Stat->Location->find('list', array('callbacks' =>false, 'conditions' => array('Location.deleted = 0')));
		$this->set('allLocations', $allLocations);
		$this->aggregatedInventory($this->data['Search']['search']);
	}
	
	function patientsWithKits($strFilter = null) {
		$locations = $this->Stat->Location->find('list');

		$patients = $this->Stat->Patient->find('list');
		$this->set(compact('locations', 'patients'));
		
		$assigned = $this->Stat->query('select id, unit_id, patient_id, created 
					from stats s 
					where  patient_id is not null 
					and status_id = 2 
					order by created asc');
					//and location_id in (' . implode(",", $this->Session->read("userLocations")) .')
		$open = $this->Stat->query('select unit_id, patient_id, created 
					from stats s 
					where status_id = 3 
					
					order by created asc'); //and location_id in (' . implode(",", $this->Session->read("userLocations")) .') 
		$popped = false;
		//loop trhough opened units and remove all patient ids which unit was open
		//patients with more than one assigned will remain only one send will be removed
		foreach ($open as $o) { 
			foreach ($assigned as $key=>$a) {
				if ($o['s']['unit_id'] == $a['s']['unit_id'] && !$popped
							&& $o['s']['created'] > $a['s']['created']){
					unset($assigned[$key]);
					$popped = true;
				}
			}
			$popped = false;
		}
		$statIds = array();
		$statIdLoc = array();
		foreach ($assigned as $c) {
			//attach the location to this patient
			/* $kitLocation = $this->Stat->query(' select location_id
				from stats s
				where  patient_id = ' . $c['s']['patient_id'] .'
				and status_id = 6
				and created = \'' . $c['s']['created'] .'\' 
				and unit_id = ' . $c['s']['unit_id'] .'
				order by created asc;'); */
			$statIds[] = $c['s']['id'];
			$currFacility = $this->getUnitCurrentFacility($c['s']['unit_id'], false);
			$statIdLoc[$c['s']['id']] = $currFacility[0];
		}
		if (empty($statIds))
			$statIds[] = -1;
			//$this->set('send', $this->Stat->query('SELECT * from stats Stat where id in(' . implode(', ', $statIds) .')'));
			
		$this->paginate['Stat'] = array('order' => 'Stat.created DESC');
		$search = (empty($this->data['Search']['search'])?(isset($this->passedArgs[0])?$this->passedArgs[0]:$this->data['Search']['search']):$this->data['Search']['search']);
		//if (!empty($search) ) {
				$this->paginate['Stat'] = array('order' => 'Stat.created DESC',
										'conditions' => array("Stat.id" => $statIds ,
														//"Stat.patient_id is not null", 
														"Stat.status_id" => 2,  
											"OR" => array("Location.name LIKE "=>"%".$search."%", 
													"Location.shortname LIKE" => "%".$search."%", 
													"Patient.number LIKE" => "%".$search."%")
											
											)
										);
		//} 
		$this->set('statIdLoc', $statIdLoc);
		$this->set('stats', $this->paginate());
	}
	
	//options action to cater for the last n digits
	function  options() {
		if (!($this->data['Stat']['ndigits'])) {
			Configure::load('options');
			$length = Configure::read('Phone.length');
			$limit = Configure::read('Graph.limit');
			$appName = Configure::read('App.name');
			$level0 = Configure::read('Facility.level0');
			$level1 = Configure::read('Facility.level1');
			$level2 = Configure::read('Facility.level2');
			$level3 = Configure::read('Facility.level3');
			$level4 = Configure::read('Facility.level4');
			//set the form
			$this->data['Stat']['ndigits'] = $length;
			$this->data['Stat']['ndigitsOld'] = $length;
			$this->data['Stat']['limit'] = $limit;
			$this->data['Stat']['appName'] = $appName;
			$this->data['Facility']['level0'] = $level0;
			$this->data['Facility']['level1'] = $level1;
			$this->data['Facility']['level2'] = $level2;
			$this->data['Facility']['level3'] = $level3;
			$this->data['Facility']['level4'] = $level4;

		} else {
			if (($this->data['Stat']['ndigitsOld'] < $this->data['Stat']['ndigits']) 
				|| $this->data['Stat']['ndigits'] == '' || $this->data['Stat']['ndigits'] <=6
				|| !is_numeric($this->data['Stat']['ndigits'])){
				$this->Session->setFlash(__('Last n digits cannot be empty  or less then the previous value.', true));
				$this->Stat->invalidate('ndigits', 'Please enter numeric value > 6 and less than the previous value used: <= '. $this->data['Stat']['ndigitsOld']);
			} else if ($this->data['Stat']['limit'] == ''  || !is_numeric($this->data['Stat']['limit']) 
					|| $this->data['Stat']['limit'] <=0 || $this->data['Stat']['limit'] > 25){
				$this->Session->setFlash(__('Number of months must be numeric', true));
				$this->Stat->invalidate('limit', 'Please enter numeric value between 1 and 24 for number of months');
			} else {
				$options = array(	'Phone' => 	
										array('length' => $this->data['Stat']['ndigits'] ),
									'Graph' =>
										array('limit' => $this->data['Stat']['limit'] ),
									'App' =>
										array('name' => "'". addslashes($this->data['Stat']['appName']) ."'" ),
									'Facility' =>
										array('level0' =>"'". addslashes($this->data['Stat']['level0']) ."'",
										'level1' =>"'". addslashes($this->data['Stat']['level1']) ."'",
										'level2' =>"'". addslashes($this->data['Stat']['level2']) ."'",
										'level3' =>"'". addslashes($this->data['Stat']['level3']) ."'",
										'level4' =>"'". addslashes($this->data['Stat']['level4']) ."'"),
		
								);
				$this->storeConfig('options', $options );

				$this->Session->setFlash('Options updated successfully', 'flash_success');
				$this->redirect( '/' );
			}
		}
    }
}
?>
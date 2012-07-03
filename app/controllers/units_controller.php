<?php
class UnitsController extends AppController {

	var $name = 'Units';
	var $helpers = array('Html', 'Crumb', 'Javascript', 'Ajax');
	var $components = array('RequestHandler', 'Access');
	
	function beforeFilter () {
		parent::beforeFilter();
		$this->Unit->Stat->Location->data["authUser"] =  $this->Session->read("Auth.User") ;
		$this->Unit->Stat->Location->data["authLocations"] =  $this->Session->read("userLocations") ;
		
   }
   
	function index() {
		$this->Unit->recursive = 0;
		$this->paginate['Unit'] = array('order' => 'Unit.id DESC');
		$search = (empty($this->data['Search']['search'])?(isset($this->passedArgs[0])?$this->passedArgs[0]:$this->data['Search']['search']):$this->data['Search']['search']);
		if (!empty($search) ) {
				$this->paginate['Unit'] = array('order' => 'Unit.code Asc',
										'conditions'=>array( 
											"OR" => array("Unit.code LIKE" => "%".$search."%", 
														"Batch.batch_number LIKE" => "%".$search."%", 
													)
									));
		} 
		$this->set('units', $this->paginate());
		$this->set('batches', $this->Unit->Batch->find('list'));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid unit', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('unit', $this->Unit->read(null, $id));
		$this->set('batches', $this->Unit->Batch->find('list'));
	}

	function add() {
		$lastUnits =  $this->Session->read("recentlyUsedUnits");
		if (!empty($this->data)) {
			if (empty($this->data['Unit']['location_id'])  ) {
				$this->Unit->invalidate('location_id', __('Please select facility' , true));
				$this->Session->setFlash(__('Facility is required. Please select a facility', true));
			} else {
				$this->Unit->create();
				if ($this->Unit->save($this->data)) {
					//create stats assignment data for the new unit
					$statData['Stats']['created']['year'] = date('Y');
					$statData['Stats']['created']['month'] = date('m');
					$statData['Stats']['created']['day'] = date('d');
					$statData['Stats']['created']['hour'] = date('H');
					$statData['Stats']['created']['min'] = date('i');
					$statData['Stats']['created']['sec'] = date('s');

					//prepare the stats data
					$statData = array('Stats' => array(
														'created' => $statData['Stats']['created'],
														'location_id' => $this->data['Unit']['location_id'],
														'unit_id' => $this->Unit->id,
														'status_id' => 2, //2 is assign
														'user_id' => $this->Unit->Stat->Location->data["authUser"]['id'] ,
													) );
					$this->loadModel('Stats');
					$this->Stats->create();
					$this->Stats->save($statData);
					
					$this->Session->setFlash(__('The unit has been saved', true), 'flash_success');
					if ($lastUnits !='')
						$unitsArray = explode(",", $lastUnits);
					else 	
						$unitsArray = array();
					array_push($unitsArray, $this->Unit->id);
					$lastUnits = implode(",", $unitsArray);
					//save related records
					$this->Unit->UnitsItem->save(array('UnitsItem' => array ( 'unit_id' => $this->Unit->id, 
											'item_id' => $this->data['Unit']['item_id'] ) ));
					//$this->redirect(array('action' => 'index'));
					
				} else {
					$this->Session->setFlash(__('The unit could not be saved. Please, try again.', true));
				}
			}
		}
		$this->Session->write("recentlyUsedUnits", $lastUnits);
		$items = $this->Unit->Item->find('list');
		$locations = $this->Unit->Stat->Location->find('list');
		$batches = $this->Unit->Batch->find('list');
		$allUnits = $this->Unit->find('list');
		$this->set(compact('items', 'batches', 'lastUnits', 'allUnits', 'locations'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid unit', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			print_r($this->data);
			if ($this->Unit->save($this->data)) {
				$this->Session->setFlash(__('The unit has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The unit could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Unit->read(null, $id);
		}
		$items = $this->Unit->Item->find('list');
		$batches = $this->Unit->Batch->find('list');
		$unitsItems = $this->Unit->UnitsItem->find('list');
		$this->set(compact('items', 'batches', 'unitsItems'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for unit', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Unit->delete($id)) {
			$this->Session->setFlash(__('Unit deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Unit was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}

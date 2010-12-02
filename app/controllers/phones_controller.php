<?php
class PhonesController extends AppController {

	var $name = 'Phones';
	var $helpers = array('Html', 'Crumb', 'Javascript', 'Ajax');


		function beforeFilter() {
			   		parent::beforeFilter();
			    	//$this->Auth->allow(array('*'));
			    	$this->Auth->allowedActions = array('');
   }

	function index() {
		$this->Phone->recursive = 0;
		$this->set('phones', $this->paginate(array( 'Phone.deleted =' => 0)));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid phone', true));
			$this->redirect(array('action' => 'index'));
		}
		if (empty($this->data['Config']['limit'])) {
			$this->Phone->hasMany['Rawreport']['limit'] = 20;
			$this->Phone->hasMany['Stat']['limit'] = 20;
			$this->Phone->hasMany['Rawreport']['order'] = 'created desc';
			$this->Phone->hasMany['Stat']['order'] = 'created desc';
		}else {
			$this->layout = 'ajax';
			$this->Phone->hasMany['Rawreport']['limit'] = $this->data['Config']['limit'];
			$this->Phone->hasMany['Rawreport']['order'] = 'created desc';
			$this->Phone->hasMany['Stat']['limit'] = $this->data['Config']['limit'];
			$this->Phone->hasMany['Stat']['order'] = 'created desc';
		}
		$this->set('phone', $this->Phone->read(null, $id));
		$this->set('drug', $this->Phone->Location->Stat->find('list'));
		$this->set('locations', $this->Phone->Location->find('list', array ('conditions' => 'Location.deleted = 0')));
		$this->set('phones', $this->Phone->find('list', array ('conditions' => 'Phone.deleted = 0')));
	}

	function add() {
		if (!empty($this->data)) {
			$phoneDeleted = $this->Phone->findByPhonenumber($this->data['Phone']['phonenumber']);
			if (!empty($phoneDeleted)) {
				//phone has been delted reactive
				$this->Session->setFlash('This phone has been deleted prevously. Please confirm to re-activate.', 'flash_success');
				$this->redirect(array('action' => 'edit', $phoneDeleted['Phone']['id'], 1));
			}
			$this->Phone->create();
			if ($this->Phone->save($this->data)) {
				$this->Session->setFlash('The phone has been saved', 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('The phone could not be saved. Please, try again.', 'flash_failure');
			}
		}
		$locations =  $this->Phone->Location->find('list', array ('conditions' => 'Location.deleted = 0'));
		$this->set(compact('locations'));
	}

	function edit($id = null) {
		
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid phone', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			
			if ($this->Phone->save($this->data)) {
				$this->Session->setFlash('The phone has been saved', 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('The phone could not be saved. Please, try again.', 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Phone->read(null, $id);
			if (isset($this->passedArgs[1]) && $this->passedArgs[1] == 1)
				$this->data['Phone']['deleted'] = 0;
		}
		
		$locations = $this->Phone->Location->find('list', array ('conditions' => 'Location.deleted = 0'));
		$this->set(compact('locations'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for phone', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($id) { //$this->Phone->delete($id)
			$this->Phone->query('UPDATE phones set deleted = 1 WHERE id = ' . $id);
			$this->Session->setFlash('Phone deleted', 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Phone was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>
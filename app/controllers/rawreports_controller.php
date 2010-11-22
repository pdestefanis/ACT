<?php
class RawreportsController extends AppController {

	var $name = 'Rawreports';
	var $helpers = array('Html', 'Crumb', 'Javascript');

	function beforeFilter() {
		   		parent::beforeFilter();
		    	//$this->Auth->allow(array('*'));
		    	$this->Auth->allowedActions = array('');
   }

	function index() {
		$this->Rawreport->recursive = 0;
		$this->paginate['Rawreport'] = array('order' => 'Rawreport.created DESC');
		$this->set('rawreports', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid rawreport', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('rawreport', $this->Rawreport->read(null, $id));
		$this->set('locations', $this->Rawreport->Stat->Location->find('list', array ('conditions' => 'Location.deleted = 0')));
		$this->set('phones', $this->Rawreport->Stat->Phone->find('list', array ('conditions' => 'Phone.deleted = 0')));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Rawreport->create();
			if ($this->Rawreport->save($this->data)) {
				$this->Session->setFlash('The rawreport has been saved', 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The rawreport could not be saved. Please, try again.', true));
			}
		}
		$phones = $this->Rawreport->Phone->find('list', array ('conditions' => 'Phone.deleted = 0')); 
		$this->set(compact('phones'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid rawreport', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Rawreport->save($this->data)) {
				$this->Session->setFlash('The rawreport has been saved', 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The rawreport could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Rawreport->read(null, $id);
		}
		$phones = $this->Rawreport->Phone->find('list', array ('conditions' => 'Phone.deleted = 0')); 
		$this->set(compact('phones'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for rawreport', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Rawreport->delete($id)) {
			$this->Session->setFlash('Rawreport deleted', 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Rawreport was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>
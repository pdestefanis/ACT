<?php
class LocationsController extends AppController {

	var $name = 'Locations';
	var $helpers = array('Html', 'Crumb'); // 'Javascript', 'Ajax');
	//var $components = array('RequestHandler');

	function beforeFilter() {
	   		parent::beforeFilter();
	    	//$this->Auth->allow(array('*'));
	    	$this->Auth->allowedActions = array('');
   }

	function index() {
		$this->Location->recursive = 0;
		$this->set('locations', $this->paginate('Location', array('deleted' => 0)));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid location', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('location', $this->Location->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Location->create();
			if ($this->Location->save($this->data)) {
				$this->Session->setFlash('The location has been saved', 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The location could not be saved. Please, try again.', true));
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid location', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Location->save($this->data)) {
				$this->Session->setFlash('The location has been saved', 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The location could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Location->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for location', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($id) { //$this->Location->delete($id)
			$this->Location->query('UPDATE locations set deleted = 1 WHERE id = ' . $id);
			$this->Session->setFlash('Location deleted', 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Location was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}

}
?>
<?php
class PatientsController extends AppController {

	var $name = 'Patients';
	var $helpers = array('Html', 'Javascript', 'Crumb');

	function index() {
		$this->Patient->recursive = 1;
		$this->set('patients', $this->paginate());
		$locations = $this->Patient->Location->find('list', array('conditions' => array('deleted' => 0)));
		$this->set(compact('locations'));
		$stats = $this->Patient->Stat->find('all', array('conditions' => array('patient_id is not null')));
		$this->set(compact('stats'));
	}
	
	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid patient', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('patient', $this->Patient->read(null, $id));
		$units = $this->getPatientCurrentKit($id);
		$locations = $this->Patient->Location->find('list', array('conditions' => array('deleted' => 0)));
		$allUnits = $this->Patient->Stat->Unit->find('list', array('conditions' => array(	
								 (($units != -1)?'id IN ('. implode(',', $units) . ')':'') )));
		$this->set(compact('locations', 'units', 'allUnits'));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Patient->create();
			$this->data['Patient']['number'] = strtoupper($this->data['Patient']['number']);
			if ($this->Patient->save($this->data)) {
				$this->Session->setFlash('The patient has been saved', 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The patient could not be saved. Please, try again.', true));
			}
		}
		$locations = $this->Patient->Location->find('list', array('conditions' => array('deleted' => 0)));
		$this->set(compact('locations'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid patient', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->data['Patient']['number'] = strtoupper($this->data['Patient']['number']);
			if ($this->Patient->save($this->data)) {
				$this->Session->setFlash('The patient has been saved', 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The patient could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Patient->read(null, $id);
		}
		$locations = $this->Patient->Location->find('list', array('conditions' => array('deleted' => 0)));
		$this->set(compact('locations'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for patient', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Patient->delete($id)) {
			$this->Session->setFlash('Patient deleted' , 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Patient was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>
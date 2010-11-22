<?php
class DrugsController extends AppController {

	var $name = 'Drugs';
	var $helpers = array('Html', 'Crumb', 'Javascript', 'Ajax');

	function beforeFilter() {
	   		parent::beforeFilter();
	    	//$this->Auth->allow(array('*'));
	    	$this->Auth->allowedActions = array();
   }
	function index() {
		$this->Drug->recursive = 0;
		$this->set('drugs', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid drug', true));
			$this->redirect(array('action' => 'index'));
		}
		if (empty($this->data['Config']['limit'])) {
			$this->Drug->hasMany['Stat']['limit'] = 20;
			$this->Drug->hasMany['Stat']['order'] = 'created desc';
		}else {
			$this->layout = 'ajax';
			$this->Drug->hasMany['Stat']['limit'] = $this->data['Config']['limit'];
			$this->Drug->hasMany['Stat']['order'] = 'created desc';
		}
		$this->set('drug', $this->Drug->read(null, $id));
		$this->set('locations', $this->Drug->Stat->Location->find('list', array ('conditions' => 'Location.deleted = 0')));
		$this->set('phones', $this->Drug->Stat->Phone->find('list', array ('conditions' => 'Phone.deleted = 0')));
	}

	function add() {
		if (!empty($this->data)) {
			$this->data['Drug']['code'] = strtoupper($this->data['Drug']['code']);
			$this->Drug->create();
			if ($this->Drug->save($this->data)) {
				$this->Session->setFlash('The drug has been saved', 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('The drug could not be saved. Please, try again.','flash_failure');
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid drug', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->data['Drug']['code'] = strtoupper($this->data['Drug']['code']);
			if ($this->Drug->save($this->data)) {
				$this->Session->setFlash('The drug has been saved', 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('The drug could not be saved. Please, try again.', 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Drug->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for drug', true));
			$this->redirect(array('action'=>'index'));
		}
		$treatmentid = $this->Drug->DrugsTreatment->query('SELECT treatment_id from drugs_treatments as dt where drug_id=' . $id);
		//Don't allow deletion is drud is part of a report
		//$statsid = $this->Drug->Stat->find('list', array('conditions' =>  array('Stat.drug_id' => $id) ));
			
		if ($treatmentid[0]['dt']['treatment_id'] != null){
			$this->Session->setFlash(__('This drug is part of a treatment and cannot be deleted. Please remove it from the treatment first', true));
			$this->Drug->invalidate('drug_id', 'Please first remove this drug from the treatment');
			$this->redirect(array('controller' => 'treatments' ,'action'=>'edit/' . $treatmentid[0]['dt']['treatment_id'] ));
		/*} else if (!empty($statsid)) {
			$this->Session->setFlash(__('This drug is part of a report and cannot be deleted.', true));
			$this->Drug->invalidate('drug_id', 'Please first remove this drug from the treatment');
			$this->redirect(array('controller' => 'drugs' ,'action'=>'view/' . $id)); */
		} else {	
			if ($this->Drug->delete($id)) {
				$this->Session->setFlash('Drug deleted', 'flash_success');
				$this->redirect(array('action'=>'index'));
			}
			$this->Session->setFlash(__('Drug was not deleted', true));
			$this->redirect(array('action' => 'index'));
		
		}
	}
}
?>
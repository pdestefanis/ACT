<?php
class DrugsTreatmentsController extends AppController {

	var $name = 'DrugsTreatments';
	var $helpers = array('Html', 'Crumb', 'Javascript', 'Ajax');
	var $uses = array('Treatment', 'Drug', 'DrugsTreatment', );

	function beforeFilter() {
	   		parent::beforeFilter();
	    	//$this->Auth->allow(array('*'));
	    	$this->Auth->allowedActions = array('');
			$db =& ConnectionManager::getDataSource('default');
        $db->fullDebug = false; 
   }
	function index($id = null) {
		$this->DrugsTreatment->recursive = 0;
		$this->set('drugstreatments', $this->DrugsTreatment->query('SELECT * FROM drugs_treatments as DrugsTreatment, drugs as Drug, treatments as Treatment WHERE Drug.id = DrugsTreatment.drug_id AND Treatment.id = DrugsTreatment.treatment_id and DrugsTreatment.treatment_id = ' . $id));
	}
	
	function add($id = null) {
		
		if (!empty($this->data)) {
			$count = $this->Drug->query('SELECT COUNT(*) as cnt FROM drugs_treatments as DT WHERE treatment_id=' . 
							$this->data['DrugsTreatment']['treatment_id'] . ' and drug_id=' .  $this->data['DrugsTreatment']['drug_id']);
			
			if ($count[0][0]['cnt'] != 0 ) {
				$this->Session->setFlash(__('Drug is alreaady present in this treatment', true));
				$this->DrugsTreatment->invalidate('drug_id', 'Please select another drug' );
			//} else if (empty($this->data['DrugsTreatment']['quantity'])) {
			//	$this->Session->setFlash(__('Quantity cannot be empty', true));
			//	$this->DrugsTreatment->invalidate('quantity', 'Please enter quantity');
			} else {
				$this->DrugsTreatment->create();
				if ($this->DrugsTreatment->saveAll($this->data)) {
					$this->Session->setFlash('The drug/quantity has been saved', 'flash_success');
					//$this->redirect(array('action' => 'index'));
					$this->redirect(array('action' => 'index/'. $id));
				} else {
					$this->Session->setFlash(__('The drug/quantity could not be saved. Please, try again.', true));
				}
			}
		}
		$drugs = $this->Drug->find('list');
		$this->set(compact('drugs'));
		$treatments = $this->Treatment->find('list');
		$this->set(compact('treatments'));
		
		
	}
	function edit($id= null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid drug/quantity', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			
			if ($this->DrugsTreatment->saveAll($this->data)) {
				$this->Session->setFlash('The drug/quantity has been saved', 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The drug/quantity could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->DrugsTreatment->read(null, $id);
		}

		$drugs = $this->DrugsTreatment->find('list');
		$this->set(compact('drugs'));
	}

	function delete($id = null) {
		//echo print_r($this->DrugsTreatment);
		
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for drug/quantity', true));
			$this->redirect(array('action'=>'index'));
		}
		$treatmentid = $this->DrugsTreatment->query('SELECT treatment_id from drugs_treatments as dt where id=' . $id);
		//print_r ($treatmentid);//['dt']['treatment_id'];
		if ($this->DrugsTreatment->delete($id)) {
			$this->Session->setFlash('Drug/quantity deleted', 'flash_success');
			$this->redirect(array('action'=>'index/'. $treatmentid[0]['dt']['treatment_id']));
		}
		$this->Session->setFlash(__('Drug/quantity was not deleted', true));
		$this->redirect(array('action'=>'index/'. $treatmentid[0]['dt']['treatment_id']));
		//$this->redirect(array('action' => 'index'));
	}
}
?>

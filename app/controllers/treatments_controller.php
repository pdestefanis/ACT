<?php
class TreatmentsController extends AppController {

	var $name = 'Treatments';
	var $helpers = array('Html', 'Crumb', 'Javascript', 'Ajax');
	//var $uses = array('Treatment', 'Drug', 'DrugsTreatment', );
	var $components = array( 'RequestHandler' );

	function beforeFilter() {
	   		parent::beforeFilter();
	    	//$this->Auth->allow(array('*'));
	    	$this->Auth->allowedActions = array('');
   }

	function index() {
		$this->Treatment->recursive = 1;
		$this->set('treatments', $this->paginate());
		//$drugs = $this->Treatment->DrugsTreatment->find('all');
		//$this->set(compact('drugs'));
		//$relateddrugs = array();
		//$tr = $this->Treatment->find('all');
		// foreach (array_keys($tr) as $t){
			// $drugs_treatments = $this->Treatment->query('SELECT * FROM drugs_treatments as dt, drugs WHERE drugs.id = dt.drug_id AND treatment_id=' . $t);
			// $i = 0;
			// foreach ($drugs_treatments as $dt){
				// $relateddrugs[$t][$dt['dt']['drug_id']] = $dt['drugs']['name'];
			// }
		// }
		//print_r($this->Treatment);
		//$this->set('relateddrugs', $relateddrugs);
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid treatment', true));
			$this->redirect(array('action' => 'index'));
		}
		if (empty($this->data['Config']['limit'])) {
			$this->Treatment->hasMany['Stat']['limit'] = 20;
			$this->Treatment->hasMany['Stat']['order'] = 'created desc';
		}else {
			$this->layout = 'ajax';
			$this->Treatment->hasMany['Stat']['limit'] = $this->data['Config']['limit'];
			$this->Treatment->hasMany['Stat']['order'] = 'created desc';
		}
		
		$this->set('treatment', $this->Treatment->read(null, $id));
		$this->set('locations', $this->Treatment->Stat->Location->find('list', array ('conditions' => 'Location.deleted = 0')));
		$this->set('phones', $this->Treatment->Stat->Phone->find('list', array ('conditions' => 'Phone.deleted = 0')));
		$this->set('drug', $this->Treatment->Drug->find('list'));
	}

	function add() {
		if (!empty($this->data)) {
			$this->data['Treatment']['code'] = strtoupper($this->data['Treatment']['code']);
			$this->Treatment->create();
			//print_r($this->data['Treatments']['id']);
			
			if ($this->Treatment->saveAll($this->data)) {
				$this->Session->setFlash('The treatment has been saved', 'flash_success');
				$this->redirect(array('action' => 'edit/' . $this->Treatment->getInsertId() ));
			} else {
				$this->Session->setFlash(__('The treatment could not be saved. Please, try again.', true));
			}
		}
		$drugs = $this->Treatment->Drug->find('list');
		//$drugs = $this->Treatment->Drug->find('list', array('fields'=>array('id','name')) );
		$this->set(compact('drugs'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid treatment', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->data['Treatment']['code'] = strtoupper($this->data['Treatment']['code']);
			//$this->Treatment->DrugsTreatment->quantity = $this->data['DrugsTreatment']['quantity']; 
			if ($this->Treatment->save($this->data)) {
				//$this->Treatment->DrugsTreatment->saveAll($this->data);
				$this->Session->setFlash('The treatment has been saved', 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The treatment could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Treatment->read(null, $id);
		}
		//$drugs = $this->Treatment->Drug->find('list', array('fields'=>array('id','name')) );
		//$this->set('drugs', $this->Treatment->Drug->find('list'));
		//$drugs = $this->Treatment->Drug->find('list');
		//print_r($this->Treatment);
		$drugs = $this->Treatment->Drug->find('list');
		$drugsTreatments = $this->Treatment->DrugsTreatment->query('SELECT * FROM drugs_treatments as DrugsTreatment, drugs as Drug, treatments as Treatment WHERE Drug.id = DrugsTreatment.drug_id AND Treatment.id = DrugsTreatment.treatment_id and DrugsTreatment.treatment_id = ' . $id);
		$this->set('drugstreatments', $drugsTreatments);
		$this->set(compact('drugs'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for treatment', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Treatment->delete($id)) {
			$this->Session->setFlash('Treatment deleted', 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Treatment was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>
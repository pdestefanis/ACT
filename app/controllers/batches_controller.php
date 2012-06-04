<?php
class BatchesController extends AppController {

	var $name = 'Batches';
	var $helpers = array('Html', 'Crumb', 'Javascript', 'Ajax');
	var $components = array('RequestHandler', 'Access');

	function index() {
		$this->Batch->recursive = 0;
		$this->set('batches', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid batch', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('batch', $this->Batch->read(null, $id));
	}

	function add() { //if you modify this modify addAjax as well
		if (!empty($this->data)) {
			$this->Batch->create();
			if ($this->Batch->save($this->data)) {
				$this->Session->setFlash(__('The batch has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The batch could not be saved. Please, try again.', true));
			}
		}
	}
	
	//only for adding batches thorugh Ajax
	function addAjax() {
		if (!empty($this->data)) {
			$this->Batch->create();
			if ($this->Batch->save($this->data)) {
				$this->Session->setFlash(__('The batch has been saved', true), 'flash_success');
				//$this->redirect(array('action' => 'index'));
			} else {
				//$this->Session->setFlash(__('The batch could not be saved. Please, try again.', true));
			}
		}
		$batches = $this->Batch->find('list');
		$this->set(compact('batches'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid batch', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Batch->save($this->data)) {
				$this->Session->setFlash(__('The batch has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The batch could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Batch->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for batch', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Batch->delete($id)) {
			$this->Session->setFlash(__('Batch deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Batch was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}

<?php
class UnitsBatchesController extends AppController {

	var $name = 'UnitsBatches';

	function index() {
		$this->UnitsBatch->recursive = 0;
		$this->set('unitsBatches', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid units batch', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('unitsBatch', $this->UnitsBatch->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->UnitsBatch->create();
			if ($this->UnitsBatch->save($this->data)) {
				$this->Session->setFlash(__('The units batch has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The units batch could not be saved. Please, try again.', true));
			}
		}
		$units = $this->UnitsBatch->Unit->find('list');
		$batches = $this->UnitsBatch->Batch->find('list');
		$this->set(compact('units', 'batches'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid units batch', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->UnitsBatch->save($this->data)) {
				$this->Session->setFlash(__('The units batch has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The units batch could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->UnitsBatch->read(null, $id);
		}
		$units = $this->UnitsBatch->Unit->find('list');
		$batches = $this->UnitsBatch->Batch->find('list');
		$this->set(compact('units', 'batches'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for units batch', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->UnitsBatch->delete($id)) {
			$this->Session->setFlash(__('Units batch deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Units batch was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}

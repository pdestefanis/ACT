<?php
class UnitsItemsController extends AppController {

	var $name = 'UnitsItems';

	function index() {
		$this->UnitsItem->recursive = 0;
		$this->set('unitsItems', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid units item', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('unitsItem', $this->UnitsItem->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			print_r($this->data);
			$this->UnitsItem->create();
			if ($this->UnitsItem->save($this->data)) {
				$this->Session->setFlash(__('The units item has been saved', true));
				//$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The units item could not be saved. Please, try again.', true));
			}
		}
		$units = $this->UnitsItem->Unit->find('list');
		$items = $this->UnitsItem->Item->find('list');
		$this->set(compact('units', 'items'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid units item', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->UnitsItem->save($this->data)) {
				$this->Session->setFlash(__('The units item has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The units item could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->UnitsItem->read(null, $id);
		}
		$units = $this->UnitsItem->Unit->find('list');
		$items = $this->UnitsItem->Item->find('list');
		$this->set(compact('units', 'items'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for units item', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->UnitsItem->delete($id)) {
			$this->Session->setFlash(__('Units item deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Units item was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}

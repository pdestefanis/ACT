<?php
class StatusesController extends AppController {

	var $name = 'Statuses';
	var $helpers = array('Html', 'Javascript', 'Crumb');

	function index() {
		$this->Status->recursive = 0;
		$this->set('statuses', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid status', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('status', $this->Status->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Status->create();
			if ($this->Status->save($this->data)) {
				$this->Session->setFlash('The status has been saved', 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('The status could not be saved. Please, try again.', 'flash_failure');
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid status', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Status->save($this->data)) {
				$this->Session->setFlash('The status has been saved', 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('The status could not be saved. Please, try again.', 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Status->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for status', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Status->delete($id)) {
			$this->Session->setFlash('Status deleted', 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Status was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>
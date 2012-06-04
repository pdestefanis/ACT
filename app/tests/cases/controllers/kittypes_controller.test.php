<?php
/* Kittypes Test cases generated on: 2011-05-25 15:05:38 : 1306326338*/
App::import('Controller', 'Kittypes');

class TestKittypesController extends KittypesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class KittypesControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.kittype');

	function startTest() {
		$this->Kittypes =& new TestKittypesController();
		$this->Kittypes->constructClasses();
	}

	function endTest() {
		unset($this->Kittypes);
		ClassRegistry::flush();
	}

	function testIndex() {

	}

	function testView() {

	}

	function testAdd() {

	}

	function testEdit() {

	}

	function testDelete() {

	}

}
?>
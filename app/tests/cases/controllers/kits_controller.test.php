<?php
/* Kits Test cases generated on: 2011-05-27 12:05:18 : 1306488378*/
App::import('Controller', 'Kits');

class TestKitsController extends KitsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class KitsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.kit', 'app.kittype', 'app.drug', 'app.stat', 'app.treatment', 'app.drugs_treatment', 'app.rawreport', 'app.phone', 'app.location', 'app.drugs_kittype', 'app.track', 'app.patient', 'app.status');

	function startTest() {
		$this->Kits =& new TestKitsController();
		$this->Kits->constructClasses();
	}

	function endTest() {
		unset($this->Kits);
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
<?php
/* Patients Test cases generated on: 2011-05-27 14:05:44 : 1306496744*/
App::import('Controller', 'Patients');

class TestPatientsController extends PatientsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class PatientsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.patient', 'app.track', 'app.kit', 'app.kittype', 'app.drug', 'app.drugs_kittype', 'app.location', 'app.level', 'app.phone', 'app.rawreport', 'app.stat', 'app.treatment', 'app.drugs_treatment', 'app.status');

	function startTest() {
		$this->Patients =& new TestPatientsController();
		$this->Patients->constructClasses();
	}

	function endTest() {
		unset($this->Patients);
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
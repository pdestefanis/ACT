<?php
/* Tracks Test cases generated on: 2011-05-27 15:05:26 : 1306499126*/
App::import('Controller', 'Tracks');

class TestTracksController extends TracksController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class TracksControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.track', 'app.kit', 'app.kittype', 'app.drug', 'app.drugs_kittype', 'app.location', 'app.level', 'app.phone', 'app.rawreport', 'app.stat', 'app.treatment', 'app.drugs_treatment', 'app.patient', 'app.status');

	function startTest() {
		$this->Tracks =& new TestTracksController();
		$this->Tracks->constructClasses();
	}

	function endTest() {
		unset($this->Tracks);
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
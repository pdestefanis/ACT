<?php
/* Levels Test cases generated on: 2011-05-25 14:05:29 : 1306323929*/
App::import('Controller', 'Levels');

class TestLevelsController extends LevelsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class LevelsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.level');

	function startTest() {
		$this->Levels =& new TestLevelsController();
		$this->Levels->constructClasses();
	}

	function endTest() {
		unset($this->Levels);
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
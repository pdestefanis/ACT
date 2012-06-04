<?php
/* Kittype Test cases generated on: 2011-05-25 15:05:37 : 1306326337*/
App::import('Model', 'Kittype');

class KittypeTestCase extends CakeTestCase {
	var $fixtures = array('app.kittype');

	function startTest() {
		$this->Kittype =& ClassRegistry::init('Kittype');
	}

	function endTest() {
		unset($this->Kittype);
		ClassRegistry::flush();
	}

}
?>
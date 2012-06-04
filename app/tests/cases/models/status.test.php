<?php
/* Status Test cases generated on: 2011-05-25 14:05:49 : 1306323949*/
App::import('Model', 'Status');

class StatusTestCase extends CakeTestCase {
	var $fixtures = array('app.status');

	function startTest() {
		$this->Status =& ClassRegistry::init('Status');
	}

	function endTest() {
		unset($this->Status);
		ClassRegistry::flush();
	}

}
?>
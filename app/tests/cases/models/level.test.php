<?php
/* Level Test cases generated on: 2011-05-25 14:05:25 : 1306323925*/
App::import('Model', 'Level');

class LevelTestCase extends CakeTestCase {
	var $fixtures = array('app.level');

	function startTest() {
		$this->Level =& ClassRegistry::init('Level');
	}

	function endTest() {
		unset($this->Level);
		ClassRegistry::flush();
	}

}
?>
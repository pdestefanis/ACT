<?php
/* Track Test cases generated on: 2011-05-27 12:05:23 : 1306488263*/
App::import('Model', 'Track');

class TrackTestCase extends CakeTestCase {
	function startTest() {
		$this->Track =& ClassRegistry::init('Track');
	}

	function endTest() {
		unset($this->Track);
		ClassRegistry::flush();
	}

}
?>
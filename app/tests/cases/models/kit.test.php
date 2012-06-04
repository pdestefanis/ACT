<?php
/* Kit Test cases generated on: 2011-05-27 12:05:51 : 1306488231*/
App::import('Model', 'Kit');

class KitTestCase extends CakeTestCase {
	function startTest() {
		$this->Kit =& ClassRegistry::init('Kit');
	}

	function endTest() {
		unset($this->Kit);
		ClassRegistry::flush();
	}

}
?>
<?php
/* Patient Test cases generated on: 2011-05-27 14:05:43 : 1306496743*/
App::import('Model', 'Patient');

class PatientTestCase extends CakeTestCase {
	var $fixtures = array('app.patient', 'app.track', 'app.kit', 'app.kittype', 'app.drug', 'app.drugs_kittype', 'app.location', 'app.level', 'app.phone', 'app.rawreport', 'app.stat', 'app.treatment', 'app.drugs_treatment', 'app.status');

	function startTest() {
		$this->Patient =& ClassRegistry::init('Patient');
	}

	function endTest() {
		unset($this->Patient);
		ClassRegistry::flush();
	}

}
?>
<?php
/* Patient Fixture generated on: 2011-05-27 14:05:43 : 1306496743 */
class PatientFixture extends CakeTestFixture {
	var $name = 'Patient';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'number' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 35, 'key' => 'unique', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'pateintnumberuniq' => array('column' => 'number', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'number' => 'Lorem ipsum dolor sit amet'
		),
	);
}
?>
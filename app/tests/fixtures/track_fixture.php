<?php
/* Track Fixture generated on: 2011-05-27 12:05:23 : 1306488263 */
class TrackFixture extends CakeTestFixture {
	var $name = 'Track';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'key' => 'primary'),
		'kit_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'location_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'patient_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'status_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'kit_id' => 1,
			'location_id' => 1,
			'patient_id' => 1,
			'status_id' => 1,
			'created' => '2011-05-27 12:24:23',
			'modified' => '2011-05-27 12:24:23'
		),
	);
}
?>
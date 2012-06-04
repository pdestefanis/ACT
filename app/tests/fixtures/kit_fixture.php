<?php
/* Kit Fixture generated on: 2011-05-27 12:05:51 : 1306488231 */
class KitFixture extends CakeTestFixture {
	var $name = 'Kit';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'code' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'unique'),
		'kittype_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'code' => array('column' => 'code', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'code' => 1,
			'kittype_id' => 1
		),
	);
}
?>
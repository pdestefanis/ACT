<?php
/* Drug Fixture generated on: 2010-08-29 22:08:00 : 1283111820 */
class DrugFixture extends CakeTestFixture {
	var $name = 'Drug';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'length' => 40, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'code' => array('type' => 'string', 'null' => false, 'length' => 3, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'presentation' => array('type' => 'string', 'null' => false, 'length' => 20, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'code' => 'L',
			'presentation' => 'Lorem ipsum dolor '
		),
	);
}
?>
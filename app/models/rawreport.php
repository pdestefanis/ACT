<?php
class Rawreport extends AppModel {
	var $name = 'Rawreport';
	var $displayField = 'raw_message';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Phone' => array(
			'className' => 'Phone',
			'foreignKey' => 'phone_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	var $hasMany = array(
		'Track' => array(
			'className' => 'Track',
			'foreignKey' => 'rawreport_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}
?>
<?php
class Batch extends AppModel {
	var $name = 'Batch';
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $displayField = 'batch_number';
	var $order = 'id desc';
	
	var $validate = array(
		'batch_number' => array(
			'notempty' => array(
				'rule' => array('between', 3, 14),
				'message' => 'Batch number must be between 3 and 14 characters',
				'allowEmpty' => false,
			),
			'isUnique' => array(
				'rule' =>  'isUnique',
				'message' => 'This code has already been added.',
			),
		),
		'expire_date' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Expiry date is required',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	
	var $hasMany = array(
		'Unit' => array(
			'className' => 'Unit',
			'foreignKey' => 'batch_id',
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

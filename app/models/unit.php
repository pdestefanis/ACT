<?php
class Unit extends AppModel {
	var $name = 'Unit';
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $order ='Unit.id desc';
	var $displayField ='code';
	
	var $validate = array(
		'code' => array(
			'notempty' => array(
				'rule' => array('between', 3, 14),
				'message' => 'Unit code must be between 3 and 14 characters',
				'allowEmpty' => false,
			),
			'isUnique' => array(
				'rule' =>  'isUnique',
				'message' => 'This code has already been added.',
			),
		),
		'item_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Item is required',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	
	var $belongsTo = array(
		'Batch' => array(
			'className' => 'Batch',
			'foreignKey' => 'batch_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	var $hasAndBelongsToMany = array(
		'Item' => array(
			'className' => 'Item',
			'joinTable' => 'units_items',
			'foreignKey' => 'unit_id',
			'associationForeignKey' => 'item_id',
			'unique' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		)
	);
	
	var $hasMany = array(
			'Stat' => array(
				'className' => 'Stat',
				'foreignKey' => 'unit_id',
				'dependent' => false,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
	);

}

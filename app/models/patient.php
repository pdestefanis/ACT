<?php
class Patient extends AppModel {
	var $name = 'Patient';
	
	var $displayField = 'number';
	var $order = "Patient.created DESC";

	var $validate = array(
		'number' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter patient number',
			),
			'numeric' => array(
				'rule' => '/\b[P|7][0-9]{5,6}\b/i',
				'message' => 'Patient number must start with a P or 7 and followed by 5 or 6 digits',
			),
			'unique' => array(
				'rule' =>  'isUnique',
				'message' => 'This patient number already exists.'
			)

		),
		'location_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please select facility',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	var $hasMany = array(
		'Stat' => array(
			'className' => 'Stat',
			'foreignKey' => 'patient_id',
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
		'Location' => array(
			'className' => 'Location',
			'foreignKey' => 'id',
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
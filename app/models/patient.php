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
				'rule' => '/^[0-9]{8,8}$/i',
				'message' => 'Patient number must be 8 digits long',
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
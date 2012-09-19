<?php
class Stat extends AppModel {
	var $name = 'Stat';
	var $order = "Stat.created DESC";
	var $inserted_ids = array();
	
	var $validate = array(
		/* 'item_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Please select an item',
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		), */
		'messagereceived_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Please select a user',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		/*'location_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Please selct a facility',
				'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),*/
		
		'created' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Date is required',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		/* 'modifier_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Please select a modifier',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		), */
		/* 'sent_to' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Please select the receiving facility.',
				'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		), */
		'status_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Please select status',
				'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'limit' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Please enter limit',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'rule' => array('range', 0, 25),
			'message' => 'Please enter a number between 1 and 24'
			
		),
		'appName' => array(
			'alphaNumeric' => array(
				'rule' => array('alphaNumeric'),
				'message' => 'Please enter name for this application',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'rule' => array('range', 0, 25),
			'message' => 'Please enter a number between 1 and 24'
			
		),
		'ndigits' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Please enter n digits',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Unit' => array(
			'className' => 'Unit',
			'foreignKey' => 'unit_id',
			//'conditions' => 'Unit.deleted = 0' , //TODO why is this not working? moved to beforeFind below
			'fields' => '',
			'order' => ''
		),
		'Messagereceived' => array(
			'className' => 'Messagereceived',
			'foreignKey' => 'messagereceived_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Phone' => array(
			'className' => 'Phone',
			'foreignKey' => 'phone_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Location' => array(
			'className' => 'Location',
			'foreignKey' => 'location_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Patient' => array(
			'className' => 'Patient',
			'foreignKey' => 'patient_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Status' => array(
			'className' => 'Status',
			'foreignKey' => 'status_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);
	
	var $hasAndBelongsToMany = array(
			'Approval' => array(
				'className' => 'Approval',
				'joinTable' => 'approvals_stats',
				'foreignKey' => 'stat_id',
				'associationForeignKey' => 'approval_id',
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
	
	function beforeFind($queryData) {
		if (get_class($this) === 'Stat' ) {
			if (!empty($queryData))
			{
				if (!isset($queryData['conditions'][get_class($this) . '.id'])) { //check if viewing item
					$queryData['conditions'][] = array (get_class($this) .'.location_id IN (' . implode(", ", Configure::read('authLocations')) . ')');
					$queryData['conditions'][] = array ('Unit.deleted = 0');
				}
			}
		}
		return $queryData;
	}

	function beforeDelete(){
		if (get_class($this) == 'Stat') {
			$stat = $this->findById($this->id);
			$loc = $stat[get_class($this)]['location_id'];
			//if ($sess->read("Auth.User.group_id") != 8){ // check if admin user
				if (!in_array($loc, Configure::read('authLocations')))
					return false;
			//}
		}
		return true;
	}
	
	function afterSave($created){ //store Ids of insert all operations
		if($created) {
			$this->inserted_ids[] = $this->getInsertID();
		}
		return true;
	}
}
?>
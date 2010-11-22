<?php
class User extends AppModel {
	var $name = 'User';

	   var $belongsTo = array('Group');
	   var $actsAs = array('Acl' => array('type' => 'requester'));

	function parentNode() {
	  	 if (!$this->id && empty($this->data)) {
	  		 return null;
	  	 }
	  	 if (isset($this->data['User']['group_id'])) {
			 $groupId = $this->data['User']['group_id'];
		 } else {
			 $groupId = $this->field('group_id');
	 	}
	 	if (!$groupId) {
				 return null;
		} else {
	 		return array('Group' => array('id' => $groupId));
		 }
	 }


	var $validate = array(
		'username' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter username',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			//'between' => array(
			//	'rule' => array('between', 6, 16),
			//	'message' => 'Must be between 6 to 16 characters'
			//),
			'rule' =>  'isUnique',
			'message' => 'This username has already been taken.'


		),
		'password' => array(
					'identicalFieldValues' => array(
							'rule' => array('identicalFieldValues', 'confirm_passwd' ),
							'message' => 'Passwords did not match',
							//'allowEmpty' => false,
							//'required' => true,
							//'last' => false, // Stop validation after this rule
							//'on' => 'create', // Limit validation to 'create' or 'update' operations
					),

					'notempty' => array(
							'rule' => array('notempty'),
							'message' => 'Passwords cannot be empty',
							'allowEmpty' => false,
							//'required' => true,
							//'last' => false, // Stop validation after this rule
							//'on' => 'create', // Limit validation to 'create' or 'update' operations

					),

					//'rule' => array('minLength', '6'),
					 //'message' => 'Mimimum 6 characters long'

		),

		'confirm_passwd' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Passwords did not match',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations

			),
			//'rule' => array('minLength', '6'),
			//'message' => 'Mimimum 6 characters long'

		),

		'group_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	function identicalFieldValues( $field=array(), $compare_field=null )
	  {
	     foreach( $field as $key => $value ){
	          $v1 = $value;
	          $v2 = $this->data[$this->name][ $compare_field ];
	          if ($key == 'password') $v2 = AuthComponent::password($v2);
	          if($v1 !== $v2) {
	              return FALSE;
	          } else {
	              continue;
	          }
	      }
	      return TRUE;
	  }
}
?>
<?php
class AccessComponent extends Object{
	var $components = array('Acl', 'Auth');
	var $user;
	function startup(){
		$this->user = $this->Auth->user();
	}
	function checkHelper($aro, $aco, $action = "index"){
		App::import('Component', 'Acl');
		$acl = new AclComponent();
		return $acl->check($aro, $aco, $action);
	}
}
?>
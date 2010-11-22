<?php
class UsersController extends AppController {

	var $name = 'Users';
	var $helpers = array('Html', 'Crumb', 'Javascript');
	//var $components = array('Auth');

   function login() {
         function login() {
	     	if ($this->Session->read('Auth.User')) {
	     		$this->Session->setFlash('You are logged in!', 'flash_success');
	     		$this->redirect('/', null, false);
	     	}
	     }

   }

   function logout() {
         $this->Session->setFlash('Good-Bye', 'flash_success');
	     $this->redirect($this->Auth->logout());

   }

   function beforeFilter() {
   		$this->Auth->userModel = 'User';
   		parent::beforeFilter();
    	//$this->Auth->allow(array('*'));
    	$this->Auth->allowedActions = array('');
   }

	function index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid user', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('user', $this->User->read(null, $id));
	}

	function add() {
	/*

		if (!empty($this->data)) {
			$this->User->create();
			if ($this->User->save($this->data)) {
				$this->Session->setFlash('The user has been saved', 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('The user could not be saved. Please, try again.'flash_success');
			}
		}
	*/
		if (!empty($this->data)) {
			$this->User->create();
			if ($this->User->save($this->data)) {
				$this->Session->setFlash('The user has been saved', 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->data['User']['password'] = null;
				$this->data['User']['confirm_passwd'] = null;
				$this->Session->setFlash('The user could not be saved. Please, try again.', 'flash_failure');

			}

		}
		$groups = $this->User->Group->find('list');
		$this->set(compact('groups'));

	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid user', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->User->save($this->data)) {
				$this->Session->setFlash('The user has been saved', 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->data['User']['password'] = null;
				$this->data['User']['confirm_passwd'] = null;
				$this->Session->setFlash('The user could not be saved. Please, try again.', 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->User->read(null, $id);
			$this->data['User']['password'] = null;
		}
		$groups = $this->User->Group->find('list');
		$this->set(compact('groups'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash('Invalid id for user', 'flash_failure');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->User->delete($id)) {
			$this->Session->setFlash('User deleted', 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash('User was not deleted', 'flash_failure');
		$this->redirect(array('action' => 'index'));
	}


	 function initDB() {
		 $group =& $this->User->Group;
		 
		 //Allow admins to everything
		 $group->id = 8;
		 $this->Acl->allow($group, 'controllers');

		 //moderators
		 $group->id = 9;
		 $this->Acl->allow($group, 'controllers');

		 $this->Acl->allow($group, 'controllers/Stats/updateJSONFile');
		 $this->Acl->allow($group, 'controllers/Stats/sdrugs');
		 $this->Acl->allow($group, 'controllers/Stats/streatments');
		 $this->Acl->allow($group, 'controllers/Stats/index');
		 $this->Acl->allow($group, 'controllers/Stats/view');
		 $this->Acl->allow($group, 'controllers/Drugs');
		 $this->Acl->allow($group, 'controllers/Treatments');
		 $this->Acl->allow($group, 'controllers/DrugsTreatments');
		 $this->Acl->allow($group, 'controllers/Locations');
		 $this->Acl->allow($group, 'controllers/Phones');
		 $this->Acl->allow($group, 'controllers/Rawreports/index');
		 $this->Acl->allow($group, 'controllers/Rawreports/view');
		 $this->Acl->allow($group, 'controllers/Users/logout');
		 $this->Acl->allow($group, 'controllers/Stats/options');
		 $this->Acl->allow($group, 'controllers/Users/changePass');
		 
		 $this->Acl->deny($group, 'controllers/Users');
		 $this->Acl->deny($group, 'controllers/Groups');
		 $this->Acl->deny($group, 'controllers/Users/initDB');
		 $this->Acl->deny($group, 'controllers/Users/build_acl');
		 $this->Acl->deny($group, 'controllers/Stats');
		 $this->Acl->deny($group, 'controllers/Rawreports');

		 //Users
		 $group->id = 10;
		 $this->Acl->allow($group, 'controllers');
		 $this->Acl->allow($group, 'controllers/Stats/updateJSONFile');
		 $this->Acl->allow($group, 'controllers/Stats/sdrugs');
		 $this->Acl->allow($group, 'controllers/Stats/streatments');
		 $this->Acl->allow($group, 'controllers/Users/logout');
		 $this->Acl->allow($group, 'controllers/Drugs/view');
		 $this->Acl->allow($group, 'controllers/Treatments/view');
		 $this->Acl->allow($group, 'controllers/Locations/view');
		 $this->Acl->allow($group, 'controllers/Users/changePass');

		 $this->Acl->deny($group, 'controllers/Users');
		 $this->Acl->deny($group, 'controllers/Groups');
		 $this->Acl->deny($group, 'controllers/Users/initDB');
		 $this->Acl->deny($group, 'controllers/Users/build_acl');
		 $this->Acl->deny($group, 'controllers/Drugs');
		 $this->Acl->deny($group, 'controllers/Treatments');
		 $this->Acl->deny($group, 'controllers/Locations');
		 $this->Acl->deny($group, 'controllers/Phones');
		 $this->Acl->deny($group, 'controllers/Stats');
		 $this->Acl->deny($group, 'controllers/Rawreports');
		 $this->Acl->deny($group, 'controllers/DrugsTreatments');
		 $this->Acl->deny($group, 'controllers/Stats/options');
		 
		 echo "all done";
		 exit;
		 }
	
	function build_acl() {
  		if (!Configure::read('debug')) {
  			return $this->_stop();
  		}
  		$log = array();

  		$aco =& $this->Acl->Aco;
  		$root = $aco->node('controllers');
  		if (!$root) {
  			$aco->create(array('parent_id' => null, 'model' => null, 'alias' => 'controllers'));
  			$root = $aco->save();
  			$root['Aco']['id'] = $aco->id;
  			$log[] = 'Created Aco node for controllers';
  		} else {
  			$root = $root[0];
  		}

  		App::import('Core', 'File');
  		$Controllers = Configure::listObjects('controller');
  		$appIndex = array_search('App', $Controllers);
  		if ($appIndex !== false ) {
  			unset($Controllers[$appIndex]);
  		}
  		$baseMethods = get_class_methods('Controller');
  		$baseMethods[] = 'buildAcl';

  		$Plugins = $this->_getPluginControllerNames();
  		$Controllers = array_merge($Controllers, $Plugins);

  		// look at each controller in app/controllers
  		foreach ($Controllers as $ctrlName) {
  			$methods = $this->_getClassMethods($this->_getPluginControllerPath($ctrlName));

  			// Do all Plugins First
  			if ($this->_isPlugin($ctrlName)){
  				$pluginNode = $aco->node('controllers/'.$this->_getPluginName($ctrlName));
  				if (!$pluginNode) {
  					$aco->create(array('parent_id' => $root['Aco']['id'], 'model' =>  $this->_getPluginName($ctrlName), 'alias' => $this->_getPluginName($ctrlName)));
  					$pluginNode = $aco->save();
  					$pluginNode['Aco']['id'] = $aco->id;
  					$log[] = 'Created Aco node for ' . $this->_getPluginName($ctrlName) . ' Plugin';
  				}
  			}
  			// find / make controller node
  			$controllerNode = $aco->node('controllers/'.$ctrlName);
  			if (!$controllerNode) {
  				if ($this->_isPlugin($ctrlName)){
  					$pluginNode = $aco->node('controllers/' . $this->_getPluginName($ctrlName));
  					$aco->create(array('parent_id' => $pluginNode['0']['Aco']['id'], 'model' => $ctrlName, 'alias' => $this->_getPluginControllerName($ctrlName)));
  					$controllerNode = $aco->save();
  					$controllerNode['Aco']['id'] = $aco->id;
  					$log[] = 'Created Aco node for ' . $this->_getPluginControllerName($ctrlName) . ' ' . $this->_getPluginName($ctrlName) . ' Plugin Controller';
  				} else {
  					$aco->create(array('parent_id' => $root['Aco']['id'], 'model' => $ctrlName, 'alias' => $ctrlName));
  					$controllerNode = $aco->save();
  					$controllerNode['Aco']['id'] = $aco->id;
  					$log[] = 'Created Aco node for ' . $ctrlName;
  				}
  			} else {
  				$controllerNode = $controllerNode[0];
  			}

  			//clean the methods. to remove those in Controller and private actions.
  			foreach ($methods as $k => $method) {
  				if (strpos($method, '_', 0) === 0) {
  					unset($methods[$k]);
  					continue;
  				}
  				if (in_array($method, $baseMethods)) {
  					unset($methods[$k]);
  					continue;
  				}
  				$methodNode = $aco->node('controllers/'.$ctrlName.'/'.$method);
  				if (!$methodNode) {
  					$aco->create(array('parent_id' => $controllerNode['Aco']['id'], 'model' => $ctrlName, 'alias' => $method));
  					$methodNode = $aco->save();
  					$log[] = 'Created Aco node for '. $method;
  				}
  			}
  		}
  		if(count($log)>0) {
  			debug($log);
			$this->redirect(array('action' => 'initDB'));
  		}
  	}

  	function _getClassMethods($ctrlName = null) {
  		App::import('Controller', $ctrlName);
  		if (strlen(strstr($ctrlName, '.')) > 0) {
  			// plugin's controller
  			$num = strpos($ctrlName, '.');
  			$ctrlName = substr($ctrlName, $num+1);
  		}
  		$ctrlclass = $ctrlName . 'Controller';
  		$methods = get_class_methods($ctrlclass);

  		// Add scaffold defaults if scaffolds are being used
  		$properties = get_class_vars($ctrlclass);
  		if (array_key_exists('scaffold',$properties)) {
  			if($properties['scaffold'] == 'admin') {
  				$methods = array_merge($methods, array('admin_add', 'admin_edit', 'admin_index', 'admin_view', 'admin_delete'));
  			} else {
  				$methods = array_merge($methods, array('add', 'edit', 'index', 'view', 'delete'));
  			}
  		}
  		return $methods;
  	}

  	function _isPlugin($ctrlName = null) {
  		$arr = String::tokenize($ctrlName, '/');
  		if (count($arr) > 1) {
  			return true;
  		} else {
  			return false;
  		}
  	}

  	function _getPluginControllerPath($ctrlName = null) {
  		$arr = String::tokenize($ctrlName, '/');
  		if (count($arr) == 2) {
  			return $arr[0] . '.' . $arr[1];
  		} else {
  			return $arr[0];
  		}
  	}

  	function _getPluginName($ctrlName = null) {
  		$arr = String::tokenize($ctrlName, '/');
  		if (count($arr) == 2) {
  			return $arr[0];
  		} else {
  			return false;
  		}
  	}

  	function _getPluginControllerName($ctrlName = null) {
  		$arr = String::tokenize($ctrlName, '/');
  		if (count($arr) == 2) {
  			return $arr[1];
  		} else {
  			return false;
  		}
  	}
  /**
   * Get the names of the plugin controllers ...
   *
   * This function will get an array of the plugin controller names, and
   * also makes sure the controllers are available for us to get the
   * method names by doing an App::import for each plugin controller.
   *
   * @return array of plugin names.
   *
   */
  	function _getPluginControllerNames() {
  		App::import('Core', 'File', 'Folder');
  		$paths = Configure::getInstance();
  		$folder =& new Folder();
  		$folder->cd(APP . 'plugins');

  		// Get the list of plugins
  		$Plugins = $folder->read();
  		$Plugins = $Plugins[0];
  		$arr = array();

  		// Loop through the plugins
  		foreach($Plugins as $pluginName) {
  			// Change directory to the plugin
  			$didCD = $folder->cd(APP . 'plugins'. DS . $pluginName . DS . 'controllers');
  			// Get a list of the files that have a file name that ends
  			// with controller.php
  			$files = $folder->findRecursive('.*_controller\.php');

  			// Loop through the controllers we found in the plugins directory
  			foreach($files as $fileName) {
  				// Get the base file name
  				$file = basename($fileName);

  				// Get the controller name
  				$file = Inflector::camelize(substr($file, 0, strlen($file)-strlen('_controller.php')));
  				if (!preg_match('/^'. Inflector::humanize($pluginName). 'App/', $file)) {
  					if (!App::import('Controller', $pluginName.'.'.$file)) {
  						debug('Error importing '.$file.' for plugin '.$pluginName);
  					} else {
  						/// Now prepend the Plugin name ...
  						// This is required to allow us to fetch the method names.
  						$arr[] = Inflector::humanize($pluginName) . "/" . $file;
  					}
  				}
  			}
  		}
  		return $arr;
  	}
	
	function changePass() {
		//only allow the currently logged in user to change his password
		$id = $this->Auth->User('id');
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid user', true));
			$this->redirect(array('action' => 'index'));
		}
		
		if (!empty($this->data)) {
			//don't allow hidden variables tweaking get the group and username 
			//form the system in case an override occured from the hidden fields
			$this->data['User']['group_id'] = $this->Auth->User('group_id');
			$this->data['User']['username'] = $this->Auth->User('username');
			if ($this->User->save($this->data)) {
				$this->Session->setFlash('The password change has been saved', 'flash_success');
				$this->redirect(array('action' => 'index', 'controller' => ''));
			} else {
				$this->data['User']['password'] = null;
				$this->data['User']['confirm_passwd'] = null;
				$this->Session->setFlash('The password could not be saved. Please, try again.', 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->User->read(null, $id);
			$this->data['User']['password'] = null;
		}
		$groups = $this->User->Group->find('list');
		$this->set(compact('groups'));
	}
	
	function resetUsers() {
		$this->User->query('DELETE FROM ACOS');
		$this->User->query('DELETE FROM AROS_ACOS');
		$this->redirect(array('action' => 'build_acl'));
	}
}
?>
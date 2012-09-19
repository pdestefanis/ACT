<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       cake
 * @subpackage    cake.app
 */
class AppController extends Controller {
  var $components = array('Acl', 'AuthExt', 'Session', 'RequestHandler', 'Access', 'ControllerList', 'Rest.Rest');
  var $helpers = array('Access', 'Html', 'Form');

	function beforeFilter() {
		//$this->AuthExt->allow('*');
		$this->AuthExt->userScope = array('User.active' => 1);
		$this->AuthExt->autoRedirect = false;
		 $this->AuthExt->actionPath = 'controllers/';

		  if (!$this->AuthExt->user()) {
				// Try to login user via REST
				if ($this->Rest->isActive()) {
					$this->AuthExt->autoRedirect = false;
					$credentials = $this->Rest->credentials();
					$data = array(
						$this->AuthExt->userModel => array(
							'username' => isset($credentials['username'])?$credentials['username']:'',
							'password' => isset($credentials['password'])?$credentials['password']:'',
						),
					);
					$data = $this->AuthExt->hashPasswords($data);
					if (!$this->AuthExt->login($data)) {
						$msg = sprintf('Unable to log you in with the supplied credentials. ');
						return $this->Rest->abort(array('status' => '403', 'error' => $msg));
					}
				}
			} else {	
				 //Configure AuthComponent
				 $this->AuthExt->authorize = 'actions';
				 $this->AuthExt->loginAction = array('controller' => 'users', 'action' => 'login');
				 $this->AuthExt->logoutRedirect = array('controller' => 'users', 'action' => 'login');
				 $this->AuthExt->loginRedirect = array('controller' => '/', 'action' => '');
		
				$this->AuthExt->loginError = "Username and password did not match";
				$this->AuthExt->authError = "You are not allowed to perform this action";
		
			}
		Configure::write('authLocations',$this->Session->read("userLocations"));
		$this->buildMenus();
		
	
	}
	
	protected function findLocationChildren ($loc, &$children) {
		$class = get_class($this);
		if ($class == 'UsersController') {
			$child = $this->User->Location->find('list', array('callbacks' => 'false', 'conditions' => array( 'parent_id' => $loc, 'deleted = 0')));
		} else {
			$this->loadModel('Location');
			$child = $this->Location->find('list', array('callbacks' => 'false', 'conditions' => array( 'parent_id' => $loc, 'deleted = 0')));
		}	
		foreach (array_keys($child) as $c) {
			if ($c == NULL)
				continue;
			$children[] = $c; 
			$this->findLocationChildren($c, $children);	
		}
		//return $children;
	 }
	 
	protected function findLocationParent ($loc, &$parents, $reach) {
		if ($reach != 0) {	
			$parent = $this->User->Location->find('list', array('callbacks' => 'false', 'fields' => array('Location.id', 'Location.parent_id'), 'conditions' => array( 'Location.id' => $loc, 'Location.deleted = 0')));
			if ( $parent[$loc] == 0) { //exit if top
				$parents[] = $loc;
				return;
			}
			$parents[] = $parent[$loc]; 
			$this->findLocationParent($parent[$loc], $parents, $reach-1);	
		} else {
			$parents[] = $loc; 
		}
	 }
	 
	protected function findTopParent ($loc, &$parents, $reach) {
		if ($reach >= 0) {	
			$this->loadModel('Location');
			$parent = $this->Location->find('list', array('callbacks' => 'false','fields' => array('Location.id', 'Location.parent_id'), 'conditions' => array( 'Location.id' => $loc))); //, 'Location.deleted = 0'
			if ( $parent[$loc] == 0) { //exit top
				$parents = 0;
				return;
			}
			
			$parent =  $parent[$loc]; 
			$this->findTopParent($parent, $parents, $reach-1);	
		} else {
			$parents = $loc; 
		}
	 }
	 
	 protected function findParent ($loc) {
		$parent = $this->Stat->Location->find('list', array('callbacks' => 'false','fields' => array('Location.id', 'Location.parent_id'), 'conditions' => array( 'Location.id' => $loc)));
		return $parent[$loc];
	 }
	 
	protected function findLevel ($loc, &$level) {
			$parent = $this->Location->find('list', array('callbacks' => 'false','fields' => array('Location.id', 'Location.parent_id'), 'conditions' => array( 'Location.id' => $loc))); //, 'Location.deleted = 0'
			if ( $parent[$loc] == 0) { //exit top
				return;
			} 
			$level += 1;
			$parent =  $parent[$loc]; 
			$this->findLevel($parent, $level);
	 }
	 
	protected function findLocationFirstChildren ($loc, &$children) {
		$child = $this->Stat->Location->find('list', array('callbacks' => 'false','conditions' => array( 'parent_id' => $loc, 'deleted = 0')));
			foreach (array_keys($child) as $c) {
				if ($c == NULL)
					continue;
				$children[] = $c; 
			}
		//return $children;
	 }
	
	protected function getReport(&$listitems, $strFilter = null) {
		$query = "SELECT quantity_after, units.code as icode, units.code as dname, units.id as did, created, phone_id as pid, stat_items.location_id, stat_items.id as sid, stat_items.created as screated, locations.id as lid, locations.name as lname, locations.parent_id parent ";
		$query .= "FROM stats stat_items, units, locations ";
		$query .= "WHERE stat_items.unit_id = units.id ";
		//$query .= "AND stat_items.phone_id = phones.id "; //not needed
		
		$query .= "AND stat_items.location_id = locations.id ";
		$query .= "AND stat_items.created = (select max(sa.created) from stats sa where sa.unit_id = stat_items.unit_id  ";
		$query .= "AND location_id = stat_items.location_id) ";
		
		if (isset($strFilter) && !is_numeric($strFilter) ) {
			$query .= "AND (locations.name LIKE '%"  . $strFilter . "%' ";
			//$query .= "OR units.name LIKE '%"  . $strFilter . "%' ";
			$query .= "OR units.code LIKE '%"  . $strFilter . "%') ";
		}  
		$query .= "AND stat_items.location_id IN ( " . implode(",", $this->Session->read("userLocations")) . ") ";
		$query .= "ORDER by locations.parent_id ";
		
		$listd = $this->Stat->query($query);

		foreach ($listd as $ld){
			$listitems[$ld['locations']['lid']][] = $ld;
			$listitems[$ld['locations']['lid']]['Parent'] = $ld['locations']['parent'];
		}
	}
	
	protected function getKitReport(&$listitems, $strFilter = null, $created = null) {
		$query = "select quantity, item.code as icode, item.name as dname, item.id as did,
						 created, phone_id as pid, stat_items.location_id, stat_items.id as sid, 
						 stat_items.created as screated, locations.id as lid, locations.name as lname, 
						 locations.parent_id as parent 
						 FROM stats stat_items, units, items item, units_items as ui, locations 
						 WHERE stat_items.unit_id = units.id 
						 	AND stat_items.location_id = locations.id 
						 	AND Item.id = ui.item_id 
						 	AND units.id = ui.unit_id ";
						 	/*AND stat_items.created = (select max(sa.created) 
						 								from stats sa, units u, items i, units_items as uis 
						 								WHERE sa.unit_id = u.id 
						 								AND location_id = stat_items.location_id 
						 								AND i.id = uis.item_id 
						 								AND u.id = uis.unit_id 
						 								AND location_id = stat_items.location_id) ";*/
		
		if (isset($strFilter) && !is_numeric($strFilter) ) {
			$query .= " AND (locations.name LIKE '%"  . $strFilter . "%' )";
			$query .= " OR items.name LIKE '%"  . $strFilter . "%' ";
			$query .= " OR items.code LIKE '%"  . $strFilter . "%' ";
		} 
		if (isset($created)  ) {
			$timestamp = $this->getFirstLastDates($created);
			$query .= " AND stat_items.created <= '"  . $timestamp['last'] . "' ";
			$query .= " AND stat_items.created >= '"  . $timestamp['first'] . "' ";
		} 
		$query .= " AND stat_items.location_id IN ( " . implode(",", $this->Session->read("userLocations")) . ") ";
		$query .= " AND units.deleted = 0 ";
		$query .= " ORDER by locations.parent_id, location_id, created ASC";

		$listd = $this->Stat->query($query);
		//sum up quantities per date
		$newListd = array();
		$i= 0;
		$old = null;
		$j = 0;
		while ($i < count($listd)){
			$sum = 0;
			do {
				while (($i < count($listd)) 
								&& $old['stat_items']['location_id'] == $listd[$i]['stat_items']['location_id']
								 && $old['stat_items']['created'] == $listd[$i]['stat_items']['created'] )	
				{
					$sum += $listd[$i]['stat_items']['quantity'];
					//only store the last submission for the same day
					$newListd[$j-1] = $listd[$i];		
					$newListd[$j-1]['stat_items']['quantity_after'] = $sum;
					$i++;
				} 
				if (isset($listd[$i]['stat_items']['location_id'])){
					if ($old['stat_items']['location_id'] != $listd[$i]['stat_items']['location_id'])
						$sum = $listd[$i]['stat_items']['quantity'];
					else
						$sum += $listd[$i]['stat_items']['quantity'];
					$newListd[$j] = $listd[$i];		
					$newListd[$j++]['stat_items']['quantity_after'] = $sum;
					$old = $listd[$i];
					$i++;
				}
			} while (($i < count($listd)) && $old['stat_items']['location_id'] == $listd[$i]['stat_items']['location_id'] );
		}
		$listd = $newListd;
		//modify further
		$newListd = array();
		$i= 0;
		$old = null;
		$j = 0;
		while ($i < count($listd)){
			$newListd[$j] = $listd[$i];		
				do {		
					$old = $listd[$i];
					$i++;
				} while (($i < count($listd)) && $old['stat_items']['location_id'] == $listd[$i]['stat_items']['location_id'] )	;
				if ($i != count($listd)) {
					if ($newListd[$j]['stat_items']['location_id'] != $listd[$i]['stat_items']['location_id'])
							$newListd[$j++]['stat_items'] = $listd[$i-1]['stat_items'];
					$old = $listd[$i];
				}
		}
		//
		//find deleted unit_ids and remove them from count
		//searching for deleted as I am assuming that the non deleted ones will be much more
		$this->loadModel('Unit');
		$conditions = array('deleted' => 1);
		$deletedUnits = $this->Unit->find('list', array('conditions' => $conditions));
		$deletedUnits = array_keys($deletedUnits);
		
		$listd = $newListd;
		//echo "<pre>" . print_r($listd, true) . "</pre>";
		foreach ($listd as $ld){
			
			$listitems[$ld['locations']['lid']][] = $ld;
			$listitems[$ld['locations']['lid']]['Parent'] = $ld['locations']['parent'];
			
			$query = "select sum(quantity) as sum from stats ";
			//"WHERE status_id = 2 ";
			$query .= " WHERE location_id =" . $ld['stat_items']['location_id'] . " ";
			if (isset($created)  ) {
				$timestamp = $this->getFirstLastDates($created);
				$query .= " AND created <= '"  . $timestamp['last'] . "' ";
				$query .= " AND created >= '"  . $timestamp['first'] . "' ";
			} 
			$query .= " AND unit_id NOT IN (" . implode(',', $deletedUnits) . ")";//remove deleted units
			$assigned = $this->Stat->query($query);
			
			//sum up expired/discarded
			$query = "select unit_id, location_id from stats s" .
				" WHERE status_id = 3 ";
			$query .= "AND (location_id =" . $ld['stat_items']['location_id'] . " OR location_id IS NULL)";
			if (isset($created)  ) {
				$timestamp = $this->getFirstLastDates($created);
				$query .= " AND created <= '"  . $timestamp['last'] . "' ";
				$query .= " AND created >= '"  . $timestamp['first'] . "' ";
			} 
			$query .= " AND unit_id NOT IN (" . implode(',', $deletedUnits) . ")"; //remove deleted units
			$expired = $this->Stat->query($query);
			foreach ($expired as $key=>$e) { //find latest location for units where it is not specified
				if (is_null($e['s']['location_id']) ) {
					$query = "select max(created) as macCre, location_id  from stats s" .
							" WHERE status_id = 6  " .
							" AND unit_id =" . $e['s']['unit_id'] . 
							" AND location_id = " . $ld['stat_items']['location_id'];
					
					$expiredLoc = $this->Stat->query($query);

					if (is_null($expiredLoc[0][0]['macCre'] )) //unit doesn't belong to this location
						unset($expired[$key]);
				}
			}
			$expiredCount[0][0]['sum'] = count($expired);
			
			$query = "select sum(quantity) as sum from stats " ;
			$query .= " WHERE location_id =" . $ld['stat_items']['location_id'] . " ";
			$query .= " AND  patient_id IS NOT NULL ";
			if (isset($created)  ) {
				$timestamp = $this->getFirstLastDates($created);
				$query .= " AND created <= '"  . $timestamp['last'] . "' ";
				$query .= " AND created >= '"  . $timestamp['first'] . "' ";
			} 
			$query .= " AND unit_id NOT IN (" . implode(',', $deletedUnits) . ")";//remove deleted units
			$patientSent = $this->Stat->query($query); 
			$atPatient['sum'] =  (!isset($patientSent[0][0]['sum'])?0:-$patientSent[0][0]['sum']);
			
			
			$assigned[0][0]['sum'] == ''?($assigned[0][0]['sum'] =0):'';
			$atPatient['sum'] == ''?($patientSent['sum'] =0):'';
			$expiredCount[0][0]['sum'] == ''?($expiredCount[0][0]['sum'] =0):'';
			
			//received means at the location
			$listitems[$ld['locations']['lid']][0]['Assigned'] = $assigned[0][0];
			$listitems[$ld['locations']['lid']][0]['At Patient'] = $atPatient;
			$listitems[$ld['locations']['lid']][0]['Expired'] = $expiredCount[0][0];
		}
		//echo "<pre>" . print_r($listitems, true) . "</pre>";		
	}
	protected function getGraphTimelineReport() {
		//load configuraion options
		Configure::load('graphs');
		$limit = Configure::read('Graph.limit');

		$listitems = array();
		$query = "select quantity, item.code as code, stat_items.location_id, 
					stat_items.id as sid, stat_items.created 
					FROM stats stat_items, units, items item,units_items as ui 
					WHERE stat_items.unit_id = units.id 
					and Item.id = ui.item_id 
					AND units.id = ui.unit_id ";
			
		$query .= " AND stat_items.location_id IN ( " . implode(",", $this->Session->read("userLocations")) . ") ";
		if ($limit != '') {
			$query .= " AND stat_items.created >= (NOW() - INTERVAL " . $limit . " MONTH ) AND stat_items.created <= NOW() ";
		}
		$query .= "ORDER by stat_items.location_id, stat_items.created ASC ";
		$listd = $this->Stat->query($query);
	//sum up quantities per date
		$newListd = array();
		$i= 0;
		$old = null;
		$j = 0;
		while ($i < count($listd)){
			$sum = 0;
			do {
				while (($i < count($listd)) 
								&& $old['stat_items']['location_id'] == $listd[$i]['stat_items']['location_id']
								 && $old['stat_items']['created'] == $listd[$i]['stat_items']['created'] )	
				{
					$sum += $listd[$i]['stat_items']['quantity'];
					//only store the last submission for the same day
					$newListd[$j-1] = $listd[$i];		
					$newListd[$j-1]['stat_items']['quantity_after'] = $sum;
					$i++;
				} 
				if (isset($listd[$i]['stat_items']['location_id'])) {
					if ($old['stat_items']['location_id'] != $listd[$i]['stat_items']['location_id'])
						$sum = $listd[$i]['stat_items']['quantity'];
					else
						$sum += $listd[$i]['stat_items']['quantity'];
					$newListd[$j] = $listd[$i];		
					$newListd[$j++]['stat_items']['quantity_after'] = $sum;
					$old = $listd[$i];
					$i++;
				}
			} while (($i < count($listd)) && $old['stat_items']['location_id'] == $listd[$i]['stat_items']['location_id'] );
		}
//echo "<pre>" . print_r($newListd, true) ."</pre>";
		$listd = $newListd;
		foreach ($listd as $ld){
			//$listitems[$ld['stat_items']['location_id']]['values'][$ld['items']['code']][1] = '';
			$ld['stat_items']['code'] = $ld['item']['code'];
			unset($ld['units']);
			$listitems[$ld['stat_items']['location_id']][] = $ld;
			$listitems[$ld['stat_items']['location_id']]['values'][$ld['stat_items']['code']] = array();
			
			
		}
		foreach ($listitems as $key => $li) {
			//put first and last dates into array for location
			//put date diff for total time span
			// for each of these measures put distance from 0
			//$listitems[key(reset($li))]['first'] = reset($li);
			//$listitems[$li['stat_items']['location_id']]['last'] = end($li);
			
			//first last dates plus difference representing the itnerval
			$first = 0;
			$last =0;
			$diff =  0;
			if (count($li) >= 1 && !isset($listitems[$key]['first'])) {
				$first = new DateTime($li[0]['stat_items']['created']);
				$firstOfMonth = new DateTime($first->format('Y-') . $first->format('m'). '-01 ' . $first->format('H:i:s') );
				$last = new DateTime($li[count($li)-2]['stat_items']['created']); //-2 one for count function and one for values array
				$lastOfMonth = new DateTime($last->format('Y-') . $last->format('m-') . $last->format('t ') . $last->format('H:i:s') );
				//echo date_format($last, "Y-m-d H:i:s") . " " . $lastOfMonth . "<br>";
				$listitems[$key]['first'] = $firstOfMonth->format("U");
				$listitems[$key]['last'] = $lastOfMonth->format("U");
				$diff = $lastOfMonth->format("U") - $firstOfMonth->format("U");
				$listitems[$key]['diff'] = $diff;
			}
			
			//distance from point 0 which is first date
			$i = 0;
			$min = 9999999999;
			$max = 0;
			//foreach($li as $k=>$val) {
			for ($j = 0; $j < count($li)-1; $j++){
				//if ($k != 'values') {
					
					
					$curr = new DateTime($li[$j]['stat_items']['created']);
					$distance = $curr->format("U") - $firstOfMonth->format("U");
					$listitems[$key][$j] ['stat_items']['distance'] = $distance;
					//$listitems[$key][$j] ['stat_items']['xAxis'] = round(($distance/$diff)*100);
					$listitems[$key][$j] ['stat_items']['xAxisMonthDay'] =  date_format($curr, 'M%20Y');
					$listitems[$key][$j] ['stat_items']['xAxisYear'] =  date_format($curr, 'Y');
					
					//adjust min and max for y axis
					if ($listitems[$key][$j]['stat_items']['quantity_after'] < $min)
						$min = $listitems[$key][$j]['stat_items']['quantity_after'];
					if ($listitems[$key][$j]['stat_items']['quantity_after'] > $max)
						$max = $listitems[$key][$j]['stat_items']['quantity_after'];
					
					//[1] is for position [0] for data [2] for max
					if (!isset($listitems[$key]['values'][ $listitems[$key][$j]['stat_items']['code']][1]) ) { 
						$listitems[$key]['values'][ $listitems[$key][$j]['stat_items']['code']][1] = $listitems[$key][$j]['stat_items']['quantity_after'] ; 
						$listitems[$key]['values'][ $listitems[$key][$j]['stat_items']['code']][0] = round(($distance/$diff)*100) ; 
						if (!isset($listitems[$key]['scale'])) //get the largest qty so that scaling can be set to it
							$listitems[$key]['scale'] = $listitems[$key][$j]['stat_items']['quantity_after'] ; 
						else {
							if ($listitems[$key]['scale'] < $listitems[$key][$j]['stat_items']['quantity_after'] ) {
								$listitems[$key]['scale'] = $listitems[$key][$j]['stat_items']['quantity_after'] ; 
							}
						}
						if ($listitems[$key][$j]['stat_items']['quantity_after']  < 0) {//set minimum qty for negative values
								$listitems[$key]['scaleMin']  = $listitems[$key][$j]['stat_items']['quantity_after'];
						}
					} else {
						$listitems[$key]['values'][ $listitems[$key][$j]['stat_items']['code']][1] .= "," . $listitems[$key][$j]['stat_items']['quantity_after'] ; 
						$listitems[$key]['values'][ $listitems[$key][$j]['stat_items']['code']][0] .= "," . round(($distance/$diff)*100) ; 
						//set to highest qty for scaling later
						if ($listitems[$key]['scale'] < $listitems[$key][$j]['stat_items']['quantity_after'] )
							$listitems[$key]['scale'] = $listitems[$key][$j]['stat_items']['quantity_after'] ; 
						if ($listitems[$key][$j]['stat_items']['quantity_after']  < 0) {//set minimum qty for negative values
								$listitems[$key]['scaleMin']  = $listitems[$key][$j]['stat_items']['quantity_after'];
						}
					}
					
					
						
					if ($i == 0){	
						
						$listitems[$key]['xAxisMonthDay'] = $listitems[$key][$j] ['stat_items']['xAxisMonthDay'] ; 
						$listitems[$key]['xAxisYear'] = $listitems[$key][$j] ['stat_items']['xAxisYear'] ; 
					} else {
						
						$listitems[$key]['xAxisMonthDay'] .= "|" . $listitems[$key][$j] ['stat_items']['xAxisMonthDay'] ; 
						$listitems[$key]['xAxisYear'] .= "|" . $listitems[$key][$j] ['stat_items']['xAxisYear'] ; 
					}
					
					$i++;
					
				//}
			}
			$r = 1;
			//must be for each values
			
			foreach ($listitems[$key]['values'] as $item){
				if ($r++ == count($listitems[$key]['values'])) {
					if (!isset($listitems[$key]['scaled']))
						$listitems[$key]['scaled'] = "0,100," .(isset($listitems[$key]['scaleMin'])?$listitems[$key]['scaleMin']:'0' ). "," . $listitems[$key]['scale']; //first & last
					else 	
						$listitems[$key]['scaled'] .= $listitems[$key]['scale'] ; //last
				}else{
					if (!isset($listitems[$key]['scaled']))
						$listitems[$key]['scaled'] = "0,100,"  .(isset($listitems[$key]['scaleMin'])?$listitems[$key]['scaleMin']:'0' ). "," . $listitems[$key]['scale'] ."," ; //first
					else 	
						$listitems[$key]['scaled'] .= $listitems[$key]['scale'] ."," ; //any
				}	
				
				if (!isset($listitems[$key]['xAxis'])) {
					$listitems[$key]['xAxis'] = $item[0]; 
					$listitems[$key]['xAxis'] .= "|" .$item[1];
					$listitems[$key]['scale'] = "0,100,"  .(isset($listitems[$key]['scaleMin'])?$listitems[$key]['scaleMin']:'0' ). "," .$listitems[$key]['scale'] ;
				} else {
					$listitems[$key]['xAxis'] .= "|" .$item[0]; 
					$listitems[$key]['xAxis'] .= "|" .$item[1];
				}
			}
			
			
			//legend values
			$listitems[$key]['legent'] = implode ("|", array_keys($listitems[$key]['values']));
			//y axis values
			$listitems[$key]['min'] = 0;
			$listitems[$key]['lowest'] = $min;
			$listitems[$key]['half'] = ($max) /2;
			$listitems[$key]['max'] = $max;
			//colors for data
			$collor = array();
			for ($i = 0; $i < count($listitems[$key]['values']); $i++){
				$collor[] = $this->get_random_color();
			}
			$listitems[$key]['colors'] = implode (",", $collor);
			
			
		} //foreach listitems
		/*  echo "<pre>";
			print_r($listitems);
			echo "<pre>";
		 */
		return $listitems;
	}
	//random color generation for graph chart
	protected function get_random_color() {
		$c = '';
		for ($i = 0; $i<6; $i++) {
			$c .=  dechex(rand(0,15));
		}
		return "$c";
	} 
	//TODO
	//Move this to a componenet for building graphs
	protected function buildGraphURL($listitems) {
		$graphURL = array();
		//$locs = Configure::read('authLocations');
		//echo "<pre>" . print_r($listitems, true) . "</pre>"; 
		foreach ($listitems as $key => $l) { //for each user location get the statistics'
			$graphURL[$key] = "http://chart.apis.google.com/chart?chs=350x175&cht=lxy&chd=t:";
			$graphURL[$key] .= $l['xAxis'];
			$graphURL[$key] .= "&chco=" . $l['colors'];
			$xAxisMonthDay = explode("|", $l['xAxisMonthDay']); 
			$xAxisMonthDay = array_unique($xAxisMonthDay);//remove dup dates
			$xAxisMonthDay = array_values($xAxisMonthDay); //reindexes the array
			//add missing months in between for axis labels
			$months = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
			$firstMonth = substr($xAxisMonthDay[0], 0, 3); //locate first month 
			$firstYear = substr($xAxisMonthDay[0], -4, 4); 
			$lastMonth = substr($xAxisMonthDay[(count($xAxisMonthDay))-1], 0, 3); //locate last month
			$lastYear = substr($xAxisMonthDay[(count($xAxisMonthDay))-1], -4, 4);  
			
			$firstYearMonths = array();
			$lastYearMonths = array();
			if ($firstYear != $lastYear) { //time spans over years
				$firstYearMonths = array_slice($months, array_search($firstMonth,$months), 11, true);
				$lastYearMonths = array_slice($months, 0, ((array_search($lastMonth,$months))+1),true);	
			} else {
				if (count($xAxisMonthDay) == 1){ //only one month in array add the next month as end month
					$firstYearMonths = array_slice($months, array_search($firstMonth,$months), 1, true);
					$firstYearMonths[] = $months[array_search($firstMonth,$months)+1];
				} else {
					$firstYearMonths = array_slice($months, array_search($firstMonth,$months), (array_search($lastMonth,$months) - array_search($firstMonth,$months))+1, true);
					$firstYearMonths[] = $months[array_search($lastMonth,$months)+1];
				}
			}
			
			//add all months from first and last to xAxis labels
			$firstYears = array();
			$lastYears = array();
			foreach ($firstYearMonths as $fm) {
				$firstYears[] = $firstYear;
			}
			foreach ($lastYearMonths as $fm) {
				$lastYears[] = $lastYear;
			}
			
			$xAxisMonthDay = array_merge($firstYearMonths, $lastYearMonths);
			$xAxisYear = array_merge($firstYears, $lastYears);
			
			
			$graphURL[$key] .= "&chxt=x,x,r&chxl=0:|" .  implode("|", $xAxisMonthDay); //axis 0-x,1 -x, 2 -y
			$graphURL[$key] .= "|1:|" . implode("|",$xAxisYear);
			$xaxisLabels = "|2:|"  .(isset($listitems[$key]['scaleMin'])?$listitems[$key]['scaleMin']. "|" 
						. ((round(3*$l['scaleMin']/4) == $l['scaleMin'] || round(3*$l['scaleMin']/4) == round($l['scaleMin']/4) || round(3*$l['scaleMin']/4) == round($l['scaleMin']/2))? "":round(3*$l['scaleMin']/4). "|")   
						. ((round($l['scaleMin']/2) == $l['scaleMin'] || round($l['scaleMin']/2) == 3*$l['scaleMin']/4  || round($l['scaleMin']/2) == $l['scaleMin']/4)? "":round($l['scaleMin']/2). "|")   
						. ((round($l['scaleMin']/4) == $l['scaleMin'] || round($l['scaleMin']/4) == 3*$l['scaleMin']/4 || round($l['scaleMin']/4) == $l['scaleMin']/2)? "":round($l['scaleMin']/4). "|")   
			: "" ) //isset scaleMin 
						. $l['min']    . "|"
						 . ((round($l['max']/4) == $l['max'] || round($l['max']/4) == 3*$l['max']/4 || round($l['max']/4) == $l['min'])? "":round($l['max']/4). "|")
						. ((round($l['max']/2) == $l['max'] || round($l['max']/2) == 3*$l['max']/4 || round($l['max']/2) == $l['min'])? "":round($l['max']/2). "|")
						. ((round(3*$l['max']/4) == $l['max'] || round($l['max']/2) == 3*$l['max']/4 || round($l['max']/2) == $l['min']) ? "":round(3*$l['max']/4). "|")   
						/* . (round($l['max']/4) . "|" ) 
						 . (round($l['max']/2) . "|" )
						 . (round(3*$l['max']/4) . "|" ) */
						 . $l['max']; //. "|"  . $l['lowest']
			$graphURL[$key] .= $xaxisLabels;
			$graphURL[$key] .= "&chdlp=b"; //lecgend bottom
			$graphURL[$key] .= "&&chxtc=0,10|2,10"; //tick marks of length 10 for both axis 0 and 1
			$colors = explode(",", $l['colors']); 
			$graphURL[$key] .= "&chm="; //like dot
			$circles = array();
			for ($i = 0; $i < count($colors); $i++) //add line dot for measurement with the same color
				$circles[] = "o,". $colors [$i]. "," . $i .",-1,5"; //line dot on quantity 0 for circle
			$graphURL[$key] .= implode("|", $circles);
			$graphURL[$key] .= "&chxs=0,000000|1,000000|2,000000"; //label color from above layers
			$graphURL[$key] .= "&chdl=" . $l['legent']; 
			$graphURL[$key] .= "&chds=" . $l['scaled'];
			//$graphURL[$key] .= "&chg=0," .(isset($listitems[$key]['scaleMin'])?'20':'25' ). "" ; //step size for lines on y axis
			$graphURL[$key] .= "&chg=0," . 100/ (substr_count($xaxisLabels, "|")-2) . "" ; //step size for lines on y axis
			
		}
		//
		return $graphURL;
	}

	protected function sumChildren ($children, &$listitems, $loc) {
		$sum = NULL;

		foreach ($children as $child) {
			
			if (isset($listitems[$child]))
				
				for ($j = 0; $j < count($listitems[$child])-1; $j++) 
					if ($child == $loc) {
						$sum[$listitems[$child][$j]['units']['did']]['sum'] = 0;
						$sum[$listitems[$child][$j]['units']['did']]['name'] = '';
						$sum[$listitems[$child][$j]['units']['did']]['code'] = $listitems[$child][$j]['units']['icode'];
					} else 
					if (isset($sum[$listitems[$child][$j]['units']['did']])) {
						$sum[$listitems[$child][$j]['units']['did']]['sum'] += $listitems[$child][$j]['stat_items']['quantity_after'];
						$sum[$listitems[$child][$j]['units']['did']]['name'] = $listitems[$child][$j]['units']['dname'];
						$sum[$listitems[$child][$j]['units']['did']]['code'] = $listitems[$child][$j]['units']['icode'];
					} else {
						$sum[$listitems[$child][$j]['units']['did']]['sum'] = $listitems[$child][$j]['stat_items']['quantity_after'];
						$sum[$listitems[$child][$j]['units']['did']]['name'] = $listitems[$child][$j]['units']['dname'];
						$sum[$listitems[$child][$j]['units']['did']]['code'] = $listitems[$child][$j]['units']['icode'];
					}
		}
		return $sum;
	}
	protected function sumKitChildren ($children, &$listitems, $loc) {
		$sum = NULL;
		//echo "<pre>" . print_r( $listitems, true) . "</pre>";
		foreach ($children as $child) {
			if (isset($listitems[$child])){
				for ($j = 0; $j < count($listitems[$child])-1; $j++) {
					if ($child == $loc) {
						$sum[$listitems[$child][$j]['item']['did']]['sum'] = 0;
						$sum[$listitems[$child][$j]['item']['did']]['Assigned'] = 0;
						$sum[$listitems[$child][$j]['item']['did']]['At Patient'] = 0;
						$sum[$listitems[$child][$j]['item']['did']]['Expired'] = 0;
						$sum[$listitems[$child][$j]['item']['did']]['name'] = '';
						$sum[$listitems[$child][$j]['item']['did']]['code'] = $listitems[$child][$j]['item']['icode'];
					} else 
					if (isset($sum[$listitems[$child][$j]['item']['did']])) {
						$sum[$listitems[$child][$j]['item']['did']]['sum'] += $listitems[$child][$j]['stat_items']['quantity_after'];
						$sum[$listitems[$child][$j]['item']['did']]['Assigned'] += $listitems[$child][$j]['Assigned']['sum'];
						$sum[$listitems[$child][$j]['item']['did']]['At Patient'] += $listitems[$child][$j]['At Patient']['sum'];
						$sum[$listitems[$child][$j]['item']['did']]['Expired'] += $listitems[$child][$j]['Expired']['sum'];
						$sum[$listitems[$child][$j]['item']['did']]['name'] = $listitems[$child][$j]['item']['dname'];
						$sum[$listitems[$child][$j]['item']['did']]['code'] = $listitems[$child][$j]['item']['icode'];
					} else {                         
						$sum[$listitems[$child][$j]['item']['did']]['sum'] = $listitems[$child][$j]['stat_items']['quantity_after'];
						$sum[$listitems[$child][$j]['item']['did']]['Assigned'] = $listitems[$child][$j]['Assigned']['sum'];
						$sum[$listitems[$child][$j]['item']['did']]['At Patient'] = $listitems[$child][$j]['At Patient']['sum'];
						$sum[$listitems[$child][$j]['item']['did']]['Expired'] = $listitems[$child][$j]['Expired']['sum'];
						$sum[$listitems[$child][$j]['item']['did']]['name'] = $listitems[$child][$j]['item']['dname'];
						$sum[$listitems[$child][$j]['item']['did']]['code'] = $listitems[$child][$j]['item']['icode'];
					}
				}
			}
		}
		return $sum;
	}
	
	protected function processItems($count,  $p, &$locations, &$listitems, &$items, &$report) {
	
		foreach (array_keys($locations) as $l) {
			if (!isset($listitems[$l]['Parent'])){ //add missing parents to structure so that children with reports are displayed
				$listitems[$l]['Parent'] = key($locations[$l]);
			}
		//	echo "<pre>" . key($locations[$l]) . ":" . $p ."</pre>";
		//	echo "<pre>" . print_r($locations,true) ."</pre>";
			if ( key($locations[$l]) == $p ) {


				$children = NULL;
				$children[] = $l;
				$this->findLocationChildren ($l, $children);
				
				$sum = $this->sumChildren($children, $listitems, $l);
				
				if (isset($sum)){
					// foreach (array_keys($items) as $s) {
						foreach (array_keys($sum) as $s) { 
						
							$agg = 0;
							$own = 0;
							
							$report[$l][$s]['lname'] = $locations[$l][$p] ;
							$report[$l][$s]['lid'] = $l ;
							$report[$l][$s]['parent'] =   $listitems[$l]['Parent'] ;
							$report[$l][$s]['level'] =  $count-1 ;
							
							$report[$l][$s]['iname'] =  $items[$s];
							$report[$l][$s]['icode'] =  $sum[$s]['code'];
							$report[$l][$s]['iid'] =  $s;
							
							
							if (isset($sum[$s])) {
								$agg = $sum[$s]['sum'] ;
							} else {
								$agg = 0;
							}
							$report[$l][$s]['aggregated'] =  $agg;
							
							for ($k = 0; $k < count($listitems[$l])-1; $k++) { 
								if ($listitems[$l][$k]['units']['did'] == $s) {
									$own = $listitems[$l][$k]['stat_items']['quantity_after'];
									if (!isset($report[$l][$s]['sid'])) {
										$report[$l][$s]['sid'] = $listitems[$l][$k]['stat_items']['sid'] ;
										$report[$l][$s]['screated'] =  $listitems[$l][$k]['stat_items']['screated'] ;
									}
									if ($report[$l][$s]['sid'] < $listitems[$l][$k]['stat_items']['sid']) {
										$report[$l][$s]['sid'] = $listitems[$l][$k]['stat_items']['sid'] ;
										$report[$l][$s]['screated'] =  $listitems[$l][$k]['stat_items']['screated'] ;
									}
								}
							}
							$report[$l][$s]['own'] = $own;
							$report[$l][$s]['total'] =   $own + $agg ;
							
						}
					// }
				}
				$this->processItems($count+1,  $l, $locations, $listitems, $items, $report, $app);
			}
		}
	}
	protected function processKitItems($count,  $p, &$locations, &$listitems, &$items, &$report) {
	
		foreach (array_keys($locations) as $l) {
			if (!isset($listitems[$l]['Parent'])){ //add missing parents to structure so that children with reports are displayed
				$listitems[$l]['Parent'] = key($locations[$l]);
			}
		//	echo "<pre>" . key($locations[$l]) . ":" . $p ."</pre>";
		//	echo "<pre>" . print_r($locations,true) ."</pre>";
			if ( key($locations[$l]) == $p ) {


				$children = NULL;
				$children[] = $l;
				$this->findLocationChildren ($l, $children);
				
				$sum = $this->sumKitChildren($children, $listitems, $l);
				
				if (isset($sum)){
					// foreach (array_keys($items) as $s) {
						foreach (array_keys($sum) as $s) { 
						
							$agg = 0;
							$own = 0;
							$ownReceived = 0;
							$ownPatient = 0;
							$ownExpired = 0;

							
							$report[$l][$s]['lname'] = $locations[$l][$p] ;
							$report[$l][$s]['lid'] = $l ;
							$report[$l][$s]['parent'] =   $listitems[$l]['Parent'] ;
							$report[$l][$s]['level'] =  $count-1 ;
							
							$report[$l][$s]['iname'] =  $items[$s];
							$report[$l][$s]['icode'] =  $sum[$s]['code'];
							$report[$l][$s]['iid'] =  $s;
							
							
							if (isset($sum[$s])) {
								$agg = $sum[$s]['sum'] ;
								$aggReceived = $sum[$s]['Assigned'];
								$aggPatient = $sum[$s]['At Patient'];
								$aggExpired = $sum[$s]['Expired'];
							} else {
								$agg = 0;
								$aggReceived = 0;
								$aggSent = 0;
								$aggPatient = 0;
								$aggExpired = 0;
								$aggSentTo = 0;
								$aggSentToOnly = 0;
							}
							$report[$l][$s]['aggregated'] =  $agg;
							$report[$l][$s]['agg']['Assigned'] = $aggReceived;
							$report[$l][$s]['agg']['At Patient'] = $aggPatient;
							$report[$l][$s]['agg']['Expired'] = $aggExpired;
						
							for ($k = 0; $k < count($listitems[$l])-1; $k++) { 
								if ($listitems[$l][$k]['item']['did'] == $s) {
									//TODO not quantity but agg quantity
									$own = $listitems[$l][$k]['stat_items']['quantity_after'];
									$ownReceived = $listitems[$l][$k]['Assigned']['sum'];
									$ownPatient = $listitems[$l][$k]['At Patient']['sum'];
									$ownExpired = $listitems[$l][$k]['Expired']['sum'];
									if (!isset($report[$l][$s]['sid'])) {
										$report[$l][$s]['sid'] = $listitems[$l][$k]['stat_items']['sid'] ;
										$report[$l][$s]['screated'] =  $listitems[$l][$k]['stat_items']['screated'] ;
									}
									if ($report[$l][$s]['sid'] < $listitems[$l][$k]['stat_items']['sid']) {
										$report[$l][$s]['sid'] = $listitems[$l][$k]['stat_items']['sid'] ;
										$report[$l][$s]['screated'] =  $listitems[$l][$k]['stat_items']['screated'] ;
									}
								}
							}
							$report[$l][$s]['own'] = $own;
							$report[$l][$s]['Assigned'] = $ownReceived;
							$report[$l][$s]['At Patient'] = $ownPatient;
							$report[$l][$s]['Expired'] = $ownExpired;
							
							$report[$l][$s]['total'] =   $own + $agg ;
							
							$report[$l][$s]['Total']['Assigned'] = $aggReceived + $ownReceived;
							$report[$l][$s]['Total']['At Patient'] = $aggPatient +  $ownPatient;
							$report[$l][$s]['Total']['Expired'] = $aggExpired + $ownExpired;
							
						}
					// }
				}
				$this->processKitItems($count+1,  $l, $locations, $listitems, $items, $report);
			}
		}
	}

	protected function storeConfig($name, $data = array(), $reload = false) {
		
		
		$content = '';
		if (!empty($data)) {
			foreach ($data as $key => $value) {
				foreach ($value as $k=>$v)
					$content .= sprintf("\$config['%s']['%s'] = %s;\n", $key, $k, $v);
			}
		}
		$content = "<?php\n".$content."?>";
		
		App::import('core', 'File');
		$name = strtolower($name);
		$file = new File(CONFIGS.$name.'.php');
		if ($file->open('w')) {
			$file->append($content);
		}
		$file->close();
	 
		if ($reload) {
			Configure::load($name);
		}
	}

	protected function buildMenus() {
		if (!isset($menus)) {
		//all available menu options
		$menus = array (
					'Main Menu' => array(
								array('label' =>'Inventory by Facility',
										'url' => '/stats/facilityInventory',
										'ACL' => 'Stats/facilityInventory',
										'order' => '0',
										'exclude' => '',
										'sub' => '',
										'tooltip' => ''),
								array('label' =>'Aggregated Inventory',
										'url' => '/stats/aggregatedInventory',
										'ACL' => 'Stats/aggregatedInventory',
										'order' => '1',
										'exclude' => '',
										'sub' => '',
										'tooltip' => ''),
								array('label' =>'Hierarchical Chart',
										'url' => '/stats/aggregatedChart',
										'ACL' => 'Stats/aggregatedChart',
										'order' => '2',
										'exclude' => '',
										'sub' => '',
										'tooltip' => ''),
								/*array('label' =>'Kits in Transit',
										'url' => '/stats/kitsInTransit',
										'ACL' => 'Stats/kitsInTransit',
										'order' => '3',
										'exclude' => '',
										'sub' => '',
										'tooltip' => ''),*/
								array('label' => __('Drug Usage', true),
										'url' => '/stats/drugUsage',
										'ACL' => 'Stats/drugUsage',
										'order' => '4',
										'exclude' => '',
										'sub' => '',
										'tooltip' => ''),
								array('label' =>'Kits Expired/Discarded',
										'url' => '/stats/kitsExpired',
										'ACL' => 'Stats/kitsExpired',
										'order' => '5',
										'exclude' => '',
										'sub' => '',
										'tooltip' => ''),
								array('label' =>'Patients with Kits',
										'url' => '/stats/patientsWithKits',
										'ACL' => 'Stats/patientsWithKits',
										'order' => '4',
										'exclude' => '',
										'sub' => '',
										'tooltip' => ''),
								'label' => 'Main Menu',
								'url' => '/',
								'ACL' => 'Pages/display',
								'tooltip' => '',
								'exclude' => array (0 => 'Locations/index',
													1 => 'Locations/view',
													2 => 'Locations/edit',
													3 => 'Stats/index',
													4 => 'Items/index',
													5 => 'Alerts/index',
													5 => 'Users/index',
													6 => 'Roles/index'),
								'sub' => '',
								'order' => '0',
										),
					'System Management' => array(
								array('label' =>'Facilities',
										'url' => '/locations/index',
										'ACL' => 'Locations/index',
										'order' => '0',
										'exclude' => '',
										'sub' => '',
										'tooltip' => ''),
								array('label' =>'Patients',
										'url' => '/patients/index',
										'ACL' => 'Patients/index',
										'order' => '1',
										'exclude' => '',
										'sub' => '',
										'tooltip' => ''),
								array('label' =>'Kits',
										'url' => '/units/index',
										'ACL' => 'Units/index',
										'order' => '2',
										'exclude' => '',
										'sub' => '',
										'tooltip' => ''),
								array('label' =>'Phones',
										'url' => '/phones/index',
										'ACL' => 'Phones/index',
										'order' => '3',
										'exclude' => '',
										'sub' => '',
										'tooltip' => ''),
								array('label' =>'Users',
										'url' => '/users/index',
										'ACL' => 'Users/index',
										'order' => '3',
										'exclude' => '',
										'sub' => '',
										'tooltip' => ''),
								array('label' =>'Roles',
										'url' => '/roles/index',
										'ACL' => 'Roles/index',
										'order' => '4',
										'exclude' => '',
										'sub' => '',
										'tooltip' => ''),
								'label' => 'System Management',
								'url' => '/locations/index',
								'ACL' => 'Locations/index',
								'tooltip' => '',
								'exclude' => array (0 => 'Stats/facilityInventory',
													1 => 'Stats/graphTimeline',
													2 => 'Stats/aggregatedChart',
													3 => 'Stats/aggregatedInventory',
													4 => 'Stats/update_facility_select',
													5 => 'Alerts/triggeredAlerts',
													5 => 'Users/changePass',
													6 => 'Roles/managePermissions',
													6 => 'Stats/options'),
								'sub' => '',
								'order' => '1',
								),
						'Updates and Messages' => array(
								array('label' =>'Updates',
										'url' => '/stats/index',
										'ACL' => 'Stats/index',
										'order' => '0',
										'exclude' => '',
										'sub' => '',
										'tooltip' => ''),
								array('label' =>'Raw Messages',
										'url' => '/messagereceiveds/index',
										'ACL' => 'Messagereceiveds/index',
										'order' => '1',
										'exclude' => '',
										'sub' => '',
										'tooltip' => ''),
								'label' => 'Updates',
								'url' => '/stats/index',
								'ACL' => 'Stats/index',
								'tooltip' => '',
								'exclude' => array (0 => 'Stats/facilityInventory',
													1 => 'Stats/graphTimeline',
													2 => 'Stats/aggregatedChart',
													3 => 'Stats/aggregatedInventory',
													3 => 'Stats/options',
													4 => 'Stats/update_facility_select'),
								'sub' => '',
								'order' => '2',
								),
						'Account' => array(
								array('label' =>'Account',
										'url' => '/users/changePass',
										'ACL' => 'Users/changePass',
										'order' => '0',
										'exclude' => '',
										'sub' => '',
										'tooltip' => ''),
								'label' => 'Account',
								'url' => '/users/changePass',
								'ACL' => 'Users/changePass',
								'tooltip' => '',
								'exclude' => array (0 => 'Users/index',
													1 => 'Users/view',
													2 => 'Users/edit',
													3 => 'Users/add'),
								'sub' => '',
								'order' => '3',
								),
						'Permissions' => array(
								array('label' =>'Permissions',
										'url' => '/roles/managePermissions',
										'ACL' => 'Roles/managePermissions',
										'order' => '0',
										'exclude' => '',
										'sub' => '',
										'tooltip' => ''),
								'label' => 'Permissions',
								'url' => '/roles/managePermissions',
								'ACL' => 'Roles/managePermissions',
								'tooltip' => '',
								'exclude' => array (0 => 'Locations/index',
													1 => 'Locations/view',
													2 => 'Locations/edit',
													3 => 'Stats/index',
													4 => 'Items/index',
													5 => 'Alerts/index',
													5 => 'Users/index',
													6 => 'Phones/index',
													7 => 'Approvals/index',
													8 => 'Roles/index'),
								'sub' => '',
								'order' => '4',
								),
						'Options' => array(
								array('label' =>'Options',
										'url' => '/stats/options',
										'ACL' => 'Stats/options',
										'order' => '0',
										'exclude' => '',
										'sub' => '',
										'tooltip' => ''),
								'label' => 'Options',
								'url' => '/stats/options',
								'ACL' => 'Stats/options',
								'tooltip' => '',
								'exclude' => array (0 => 'Stats/facilityInventory',
													1 => 'Stats/graphTimeline',
													2 => 'Stats/aggregatedChart',
													3 => 'Stats/aggregatedInventory',
													4 => 'Stats/update_facility_select'),
								'sub' => '',
								'order' => '5',
								),
					);
		
		
		//build list of sub pages in parent array
		foreach ($menus as $key => $value) {
			foreach ($value as $k => $v) {
				if (is_numeric($k)) {
					$menus[$key]['sub'][] =  $v['ACL'];
					//$actoins = $this->ControllerList->get(substr($v['ACL'], 0, strpos($v['ACL'], '/')));
					//foreach($actoins[substr($v['ACL'], 0, strpos($v['ACL'], '/'))] as $k => $a)
						//$menus[$key]['sub'][] = substr($v['ACL'], 0, strpos($v['ACL'], '/')) . "/" . $a;
				}
			}
			$menus[$key]['sub'][] = $value['ACL'];
			
			//add all class methods to sub
			
		}	

		$this->set('menus', $menus);

		}	
	}
	
	protected function isUnusedUnit($unitId, $date = null) {
		$this->loadModel('Stats'); 
		$conditions = array('unit_id' => $unitId, 'patient_id is not null', (!is_null(' created <= \''.$date.'\'')?$date:'') );
		
		$statUnit = $this->Stats->find('list', array('conditions' => $conditions));
		if (empty($statUnit))
			return true;
		return false;
	}
	
	protected function isDiscardedUnit($unitId, $date=null) {
		$this->loadModel('Stats');
		$conditions = array('unit_id' => $unitId, 'status_id =3', (!is_null(' created <= \''.$date.'\'')?$date:'') );
		$statUnit = $this->Stats->find('list', array('conditions' => $conditions));
		if (empty($statUnit))
			return true;
		return false;
	}
	
	/*
	 * adjsut the quantities so that sql sum on a faiclity always returns current total 
	 */
	protected function adjustQuantities($created, $unitId, $action, $quantity, $locationId = null, 
		$patientId = null, $phoneId = null, $userId = null, $messagereceivedId = null, $extraFacility = null){
		$this->loadModel('Stats');
		//back entry run the update sequence
		$shouldAdjust = FALSE;
		if (!is_null($locationId)){
			if ($action == 'R')
				//when receiveing locationId is lastfacility not the current one
				$this->updateBackEntry($created, $unitId,  $extraFacility); 
			else 
				$this->updateBackEntry($created, $unitId,  $locationId);
		}
		//is it a facility or patient assignment?
		$lastFacilityWithKit = $this->findLastUnitFacility($unitId, $this->dateArrayToString($created));
		//TODO get the user id of phone 
		//adjust quantity for last facility that had the unit
			$data = array();
			$data['Stats'] = array();
			$data['Stats']['quantity'] = -1;
			$data['Stats']['phone_id'] = ((is_null($phoneId)?0:$phoneId));
			$data['Stats']['location_id'] = $lastFacilityWithKit;
			$data['Stats']['unit_id'] = $unitId;
			$data['Stats']['messagereceived_id'] = ((is_null($messagereceivedId)?NULL:$messagereceivedId));;
			$data['Stats']['user_id'] = ((is_null($userId)?NULL:$userId));;
			$data['Stats']['status_id'] = 6; //system update
			$data['Stats']['patient_id'] = ((is_null($patientId)?NULL:$patientId));;
			$data['Stats']['created'] = $created;
			$this->Stats->create();
			$this->Stats->save($data);
	}
	/*
	 * Updates the next record for backentry. 
	 * I.e. updates the the location_id of the record de-asigning the unit after the current entry
	 */
	protected function updateBackEntry($created, $unitId,  $locationId){
		//first see if this unit was already automatically dispensed id 6
		// from a different facility
		//if ti was insert the record 
		$compDate = $this->dateArrayToString($created);
		$this->loadModel('Stats');
		//last location prior to a date - this is to cater for back entry
		$query = 'SELECT id, created, location_id, patient_id from stats st ';
		$query .= ' WHERE unit_id=' . $unitId;
		$query .= ' AND created >  \'' . $compDate . '\''
								. ' AND quantity = -1 '
								. ' AND status_id = 6 ';
	
		$result = $this->Stats->query($query);
		$maxCreated = NULL;
		$maxCreatedId = NULL;
		$maxFacilityId = NULL;
		
		foreach ($result as $key => $value) {
			if (is_null($maxCreated)) { //initial, set the both
				$maxCreated = $value['st']['created'];
				$maxCreatedId = $value['st']['id'];
				$maxFacilityId = $value['st']['location_id'];
			}
			if ($maxCreated > $value['st']['created'] && $compDate > $value['st']['created'] 
						&& !is_null($value['st']['location_id'])) {
				$maxCreated = $value['st']['created'];
				$maxCreatedId = $value['st']['id'];
				$maxFacilityId = $value['st']['location_id'];
			}
		}
		//now use the id to update this record with the newer location
		if (!is_null($maxCreated)) {
			$data = array();
			$data['Stats'] = array();
			$data['Stats']['id'] = $maxCreatedId;
			$data['Stats']['location_id'] =  $locationId;
			//$data['Stats']['created'] = $created;
			$this->Stats->save($data);
			echo "ID: " . $maxCreatedId . " date: " . $maxCreated . " loc: " . $maxFacilityId . "</br>";
		//	return FALSE;
		}
		//return TRUE;
	}

	/*
	 * Find the facility that had the unit up to a date
	 */
	protected function findLastUnitFacility($unitId, $created){
		$this->loadModel('Stats');
		//last location prior to a date - this is to cater for back entry
		$query = 'SELECT created, location_id from stats st ';
		$query .= ' WHERE unit_id=' . $unitId;
		/*$query .= ' AND created = (select max(created) from stats s  
								WHERE s.unit_id=' . $unitId . 
								' AND s.created <= \'' . $created . '\''
								. ' AND quantity != -1) ';*/
		$query .=  ' AND created <= \'' . $created . '\' AND quantity != -1';
		$result = $this->Stats->query($query);
		$maxCreated = NULL;
		$maxFacilityId = NULL;
		
		foreach ($result as $key => $value) {
			if (is_null($maxCreated)) { //initial, set the both
				$maxCreated = $value['st']['created'];
				$maxFacilityId = $value['st']['location_id'];
			}
			if ($maxCreated < $value['st']['created'] && !is_null($value['st']['location_id'])) {
				$maxCreated = $value['st']['created'];
				$maxFacilityId = $value['st']['location_id'];
			}
		}
		if (!is_null($maxFacilityId)) {
			//TODO
			//unit found see if it was dispensed from this location already
			//if it was dispensed from thsi location with a system update remove this record 
			// and dispense it here to the next location 
			//record with this date to assign it to that location
			//and remove previously assigned record 
			//return the last record if more than one found
			//return $result[count($result)-1]['st']['location_id'];
			return $maxFacilityId;
		}
 
		return -1;
	}
	
	protected function dateArrayToString($date){
		return $date['year'] . "-" . $date['month'] ."-" . $date['day'] 
					. " " . (!isset($date['hour'])?"00":$date['hour']) . ":" . (!isset($date['min'])?"00":$date['min']) 
								 . ":" . (!isset($date['min'])?"01":$date['min']) ;
	}
	
	//get the current facility or patient of a unit
	protected function getUnitCurrentFacility($unitId, $hasPatient = true, $date = null) {
			$this->loadModel('Stats');
			//last location
			$query = 'SELECT created, patient_id, location_id from stats st ';
			$query .= ' WHERE unit_id=' . $unitId;
			if (!is_null($date)) {
				$query .= ' and created <=\'' . $date .'\'';
			}
			$result = $this->Stats->query($query);
			$maxCreated = NULL;
			$maxFacilityId = NULL;
			$maxPatientId = NULL;
			$maxStatusId = NULL;
		
			foreach ($result as $key => $value) {
				if (is_null($maxCreated)) { //initial, set them both
					$maxCreated = $value['st']['created'];
					
				}
				if ($maxCreated <= $value['st']['created'] && (!is_null($value['st']['location_id']) 
												|| (!is_null($value['st']['patient_id']) && $hasPatient )) ) {
					$maxCreated = $value['st']['created'];
					$maxFacilityId = $value['st']['location_id'];
					$maxPatientId = $value['st']['patient_id'];
					$maxStatusId = $value['st']['status_id'];
				}
			}
			if (!is_null($maxFacilityId) || !is_null($maxPatientId) ) {
					return array($maxFacilityId, $maxPatientId, $maxStatusId);
			}
		
			return -1;
	}
	
	
	//get the unit creation date
	protected function getUnitFirstDate($unitId) {
		$this->loadModel('Stats');
		//last location
		$query = 'SELECT min(created) created from stats st ';
		$query .= ' WHERE unit_id=' . $unitId;
		$result = $this->Stats->query($query);
		$minCreated = NULL;
	
		foreach ($result as $key => $value) {
			if (is_null($minCreated)) { //initial, set them both
				$minCreated = $value[0]['created'];	
			}
		}
		if (!is_null($minCreated) ) {
			return $minCreated;
		}
	
		return -1;
	}
	
	
	//get the unit first assignment date that isn't unit creatoin
	protected function getUnitFirstAssignDate($unitId, $created) {
		$this->loadModel('Stats');
		//last location
		$query = 'SELECT min(created) created from stats st ';
		$query .= ' WHERE unit_id=' . $unitId;
		$query .= ' AND created != \'' . $created .'\'';
		$query .= ' AND status_id in (1, 2)';
		$result = $this->Stats->query($query);
		$minCreated = NULL;
	
		foreach ($result as $key => $value) {
			if (is_null($minCreated)) { //initial, set them both
				$minCreated = $value[0]['created'];
			}
		}
		if (!is_null($minCreated) ) {
			return $minCreated;
		}
	
		return __('Not assigned yet', true);
	}
	
	
	//get the unit open date
	protected function getUnitOpenDate($unitId) {
		$this->loadModel('Stats');
		//last location
		$query = 'SELECT min(created) created from stats st ';
		$query .= ' WHERE unit_id=' . $unitId;
		$query .= ' AND patient_id IS NOT NULL';
		$query .= ' AND status_id = 2';
		$result = $this->Stats->query($query);
		$minCreated = NULL;
	
		foreach ($result as $key => $value) {
			if (is_null($minCreated)) { //initial, set them both
				$minCreated = $value[0]['created'];
			}
		}
		if (!is_null($minCreated) ) {
			return $minCreated;
		}
	
		return __('Not opened yet', true);
	}
	
	
	//get fist and last days of month in supplied date
	protected function getFirstLastDates($created){
		$suppliedDate = strtotime($created); //timestamp for the current date
		// Current timestamp is assumed, so these find first and last day of THIS month
		$firstDay = date('Y-m-01 00:00:00', $suppliedDate); //'01' for first day 00 hours for first hour
		$lastDay = date('Y-m-t 23:59:59', $suppliedDate); 
		//'t' for last day (number of days = last day) '23.59' for last hour
		return array('first' => $firstDay, 'last' => $lastDay);
	}
	
	/*
	 * Check if operation that is going to be performed is allowed
	 * action can be A, R, or E
	 * Not finihsed as I am not sure if we need so much restriction
	 */
	function isActionAllowed($unitId, $facility = null, $action = null, $date) {
		$unitStats = $this->getUnitCurrentFacility($unit);
		//TODO is unused kit must be date sensitive
		$isUnsed = $this->isUnusedUnit($unitId, $date);
		$isDiscarded = $this->isDiscardedUnit($unitId, $date); //TODO is unit expired
		if ($action == 'A') { //only possible with unused
				if ($isUnsed && !$isDiscarded)
					return true;
		} else if ($action == 'R') {
				if (!$isDiscarded)
					return true;
		} else if ($action == 'E') {
			if (!$isDiscarded)
				return true;
		}
		return false;
	}
}

<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
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
 * @subpackage    cake.cake.libs.controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       cake
 * @subpackage    cake.cake.libs.controller
 */
 
class PagesController extends AppController {

/**
 * Controller name
 *
 * @var string
 * @access public
 */
	var $name = 'Pages';

/**
 * Default helper
 *
 * @var array
 * @access public
 */
	var $helpers = array('Html', 'Form', 'Javascript', 'GoogleMap', 'Crumb', 'UpdateFile', 'Ajax', 'GoogleChart', 'GoogleMapv3');
	

/**
 * This controller does not use a model
 *
 * @var array
 * @access public
 */
	var $uses = array();

/**
 * Displays a view
 *
 * @param mixed What page to display
 * @access public
 */
	function  beforeFilter() {
		parent::beforeFilter();
        //load the stats model to update the points file
		$this->loadModel('Stat');
    }
	
	function display() {
		$path = func_get_args();
		//$this->buildMenus();
		$count = count($path);
		if (!$count) {
			$this->redirect('/');
		}
		$page = $subpage = $title_for_layout = null;

		if (!empty($path[0])) {
			$page = $path[0];
		}
		if (!empty($path[1])) {
			$subpage = $path[1];
		}
		if (!empty($path[$count - 1])) {
			$title_for_layout = Inflector::humanize($path[$count - 1]);
		}
		$this->updateJSONFile();
		
		$this->set(compact('page', 'subpage', 'title_for_layout'));
		$this->render(implode('/', $path));
	}
	
	//moved from stats contreoller so that file update is on the fly here
	private function updateJSONFile() {
		/* App::import('Controller', 'Stats');
		var $Stats;
		
		// We need to load the class
		$Stats = new StatsController;
		// If we want the model associations, components, etc to be loaded
		$Stats->constructClasses(); */
		
		if (!($this->data['Stat']['JSONFile'])) {	
				$locations = $this->Stat->query('SELECT * FROM locations where id IN (' .  implode(",", $this->Session->read("userLocations"))  . ') ');
				//$locations = $this->Stat->query('SELECT * FROM locations where deleted = 0 and id IN (' .  implode(",", $this->Session->read("userLocations"))  . ') ');
	
				$this->set('locations', $locations);
				$this->set('allLocations', $this->Stat->query('SELECT * FROM locations'));
				//$listitems = $this->getReports($locations);
				//$listitems = $this->getKitReports($locations);
				$listitems =  array();
				$this->getKitReport($listitems);
				
				$this->set(compact('listitems', $listitems));
				
				App::import('Controller', 'Alerts');
				$Alerts = new AlertsController;

				$Alerts->constructClasses();
				$alerts = $Alerts->triggeredAlerts();
				$this->set('alerts', $alerts);
				
				
				App::import('Controller', 'Stats');
				$Stats = new StatsController;

				$Stats->constructClasses();
				$graphURL = $Stats->graphTimeline();
				
				$this->set('graphURL', $graphURL);
		}
	}
	
	private function &getReports($locations) {
				$listitems = array();
				$temp = array();
				
				//for ($j = 1; $j <= count($locations); $j++)
				foreach ($locations as $loc)
				{
					//items
					$query = "SELECT quantity_after, unit.code as dname, st.unit_id ";
					$query .= "FROM stats st, units unit ";
					$query .= "WHERE st.unit_id = unit.id ";
					$query .= "AND st.id = (select max(sa.id) from stats sa where sa.unit_id = st.unit_id  ";
					$query .= "AND location_id =" . $loc['locations']['id'] . " ) ";
					$query .= "AND location_id =" . $loc['locations']['id'] . " ";
					$query .= "ORDER by created DESC ";


					//$result = runQuery($query);
					$temp = $this->Stat->query($query);
					//$this->set('listitems',$listitems);

					$listd= array();

					$i = 0;
					/*while ($row = $result->fetch_assoc()) {
						$listd[$i++]['Listitems'] = $row;
						//print_r($row);
					}*/
					
					foreach ($temp as $row ){
						$listd[$i++]['Listitems'] = $row;
					}
					if (!empty($listd )){
						$listitems[$loc['locations']['id']] = $listd;
					}
					
				}
				return $listitems;
	}
	private function &getKitReports($locations) {
				$listitems = array();
				$temp = array();
				$sent = array();
				$sentTo = array();
				$received = array();
				$expired = array();
				$patientSent = array();
				$patientReceived = array();
				
				//for ($j = 1; $j <= count($locations); $j++)
				foreach ($locations as $loc)
				{
					//items get count for all
					$query = "SELECT st.quantity, st.quantity_after, unit.code dname, unit_id ";
					$query .= "FROM stats st, statuses status, units unit ";
					$query .= "WHERE st.status_id = status.id ";
					$query .= "AND st.unit_id = unit.id ";
					$query .= "AND st.id = (select max(sa.id) from stats sa where sa.unit_id = st.unit_id  ";
					$query .= "AND location_id =" . $loc['locations']['id'] . " ) ";
					$query .= "AND location_id =" . $loc['locations']['id'] . " ";
					$query .= "ORDER by created DESC ";

					
					//$result = runQuery($query);
					$temp = $this->Stat->query($query);
					
					//sum up received
					$query = "select sum(quantity) as sum from stats " .
					"WHERE status_id = 1 ";
					$query .= "AND location_id =" . $loc['locations']['id'] . " ";
					$query .= "AND patient_id is NULL ";
					//$query .= "ORDER by created DESC ";
					$received = $this->Stat->query($query);
					
					//sum up sent to this location
					$query = "select sum(quantity) as sum from stats " .
					"WHERE status_id = 2 ";
					$query .= "AND location_id =" . $loc['locations']['id'] . " ";
					$sentTo = $this->Stat->query($query);
					
					//sum up sent from this location
					$query = "select sum(quantity) as sum from stats " .
					"WHERE status_id = 2 ";
					$query .= "AND location_id =" . $loc['locations']['id'] . " ";
					$sent = $this->Stat->query($query);
					
					//sum up expired
					$query = "select sum(quantity) as sum from stats " .
					"WHERE status_id = 3 ";
					$query .= "AND location_id =" . $loc['locations']['id'] . " ";
					$expired = $this->Stat->query($query);
					
					/* //sum up sent to patient
					$query = "select sum(quantity) as sum from stats " .
					"WHERE status_id = 2 ";
					$query .= "AND location_id =" . $loc['locations']['id'] . " ";
					$query .= "AND patient_id is not null ";
					$patientSent = $this->Stat->query($query);
					
					//sum up received from patient
					$query = "select sum(quantity) as sum from stats " .
					"WHERE status_id = 1 ";
					$query .= "AND location_id =" . $loc['locations']['id'] . " ";
					$query .= "AND patient_id is not null ";
					$patientReceived = $this->Stat->query($query); */
					$sentP = $this->Stat->query('select id, patient_id from stats s where  patient_id is not null and status_id = 2 and location_id =' . $loc['locations']['id'] );
					$receivedP = $this->Stat->query('select id, patient_id from stats s where  patient_id is not null and status_id = 1 and location_id =' . $loc['locations']['id'] );
					$popped = false;
					//loop trhough received and remove all patient ids that have a receive record 
					//patients will more then one send will remain only one send will be removed
					foreach ($receivedP as $r) { 
						foreach ($sentP as $key=>$s) {
							if ($r['s']['patient_id'] == $s['s']['patient_id'] && !$popped){
								unset($sentP[$key]);
								$popped = true;
							}
						}
						$popped = false;
					}
					$statIds = array();
					foreach ($sentP as $c) {
						$statIds[] = $c['s']['id'];
					}
					if (empty($statIds))
						$statIds[] = -1;
					$query = "SELECT sum(quantity) sum from stats Stat where id in (" . implode(",", $statIds). ")";
					$patientSent = $this->Stat->query($query); 
					$atPatient['sum'] =  (!isset($patientSent[0][0]['sum'])?0:$patientSent[0][0]['sum']);

				
					$listd= array();

					$i = 0;
					/*while ($row = $result->fetch_assoc()) {
						$listd[$i++]['Listitems'] = $row;
						//print_r($row);
					}*/
					
					foreach ($temp as $row ){
						$listd[$i]['Listitems'] = $row;
						$received[0][0]['sum'] == ''?($received[0][0]['sum'] =0):'';
						$listd[$i]['Listitems']['Received'] = $received[0][0];
						$sentTo[0][0]['sum'] == ''?($sentTo[0][0]['sum'] =0):($sentTo[0][0]['sum'] -=$received[0][0]['sum']);
						$listd[$i]['Listitems']['Sent to'] = $sentTo[0][0];
						$listd[$i]['Listitems']['Sent'] = $sent[0][0];
						$listd[$i]['Listitems']['At Patient'] = $atPatient;
						//$listd[$i]['Listitems']['ReceivedPatient'] = $patientReceived[0][0];
						$listd[$i]['Listitems']['Expired'] = $expired[0][0];
						$i++;
					}
					if (empty($temp) && ($sentTo[0][0]['sum'] != '')) {
						$listd[$i]['Listitems']['st'] = array('quantity' => 0, 'quantity_after' => 0, 'unit_id' => 1);
						$listd[$i]['Listitems']['unit'] = array('dname' => 'Kit');
						$received[0][0]['sum'] == ''?($received[0][0]['sum'] =0):'';
						$listd[$i]['Listitems']['Received'] = $received[0][0];
						$sentTo[0][0]['sum'] == ''?($sentTo[0][0]['sum'] =0):($sentTo[0][0]['sum'] -=$received[0][0]['sum']);
						$listd[$i]['Listitems']['Sent to'] = $sentTo[0][0];
						$listd[$i]['Listitems']['Sent'] = $sent[0][0];
						$listd[$i]['Listitems']['At Patient'] = $atPatient;
						//$listd[$i]['Listitems']['ReceivedPatient'] = $patientReceived[0][0];
						$listd[$i]['Listitems']['Expired'] = $expired[0][0];
						$i++;
					}
					if (!empty($listd )){
						$listitems[$loc['locations']['id']] = $listd;
					} 
					
				}
				
				//echo "<pre>" . print_r($listitems, true) . "</pre>";
				return $listitems;
	}
	
}

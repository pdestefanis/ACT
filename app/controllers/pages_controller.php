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
	var $helpers = array('Html', 'Form', 'Javascript', 'GoogleMap', 'Crumb', 'UpdateFile', 'Ajax');

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
        //load the stats model to update the points file
		$this->loadModel('Stat');
    }
	
	function display() {
		$path = func_get_args();

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
	function updateJSONFile() {
		/* App::import('Controller', 'Stats');
		var $Stats;
		
		// We need to load the class
		$Stats = new StatsController;
		// If we want the model associations, components, etc to be loaded
		$Stats->constructClasses(); */
		
		if (!($this->data['Stat']['JSONFile'])) {	
				//require_once('db_connect.php');
				/*$result = runQuery("SELECT * FROM locations");

				while ($row = $result->fetch_assoc()) {
					$locations[$row['id']] = $row;
				}
				*/
				$locations = $this->Stat->query('SELECT * FROM locations where deleted = 0');
				$this->set('locations', $locations);

				$listdrugs = array();
				$listtreatments = array();
				$temp = array();
				
				//for ($j = 1; $j <= count($locations); $j++)
				foreach ($locations as $loc)
				{
					//drugs
					$query = "SELECT quantity, drugs.name as dname ";
					$query .= "FROM stats st, drugs ";
					$query .= "WHERE st.drug_id = drugs.id ";
					$query .= "AND st.id = (select max(sa.id) from stats sa where sa.drug_id = st.drug_id  ";
					$query .= "AND location_id =" . $loc['locations']['id'] . " ) ";
					$query .= "AND location_id =" . $loc['locations']['id'] . " ";
					$query .= "ORDER by created DESC ";


					//$result = runQuery($query);
					$temp = $this->Stat->query($query);
					//$this->set('listdrugs',$listdrugs);

					$listd= array();

					$i = 0;
					/*while ($row = $result->fetch_assoc()) {
						$listd[$i++]['Listdrugs'] = $row;
						//print_r($row);
					}*/
					
					foreach ($temp as $row ){
						$listd[$i++]['Listdrugs'] = $row;
					}
					if (!empty($listd )){
						$listdrugs[$loc['locations']['id']] = $listd;
					}

					//$result->close();


					$this->set(compact('listdrugs'));

					//treatments
					$query = "SELECT quantity, treatments.code as dname ";
					$query .= "FROM stats st, treatments ";
					$query .= "WHERE st.treatment_id = treatments.id ";
					$query .= "AND st.id = (select max(sa.id) from stats sa where sa.treatment_id = st.treatment_id  ";
					$query .= "AND location_id =" . $loc['locations']['id'] . " ) ";
					$query .= "AND location_id =" . $loc['locations']['id'] . " ";
					$query .= "ORDER by created DESC ";


					//$result = runQuery($query);
					$temp  = $this->Stat->query($query);
					//$this->set('listtreatments',$listtreatments);
					$listd= array();

					$i = 0;
					/*while ($row = $result->fetch_assoc()) {
						$listd[$i++]['Listtreatments'] = $row;
						//print_r($row);
					}*/
					foreach ($temp as $row ){
						$listd[$i++]['Listtreatments'] = $row;
					}
					if (!empty($listd )){
						$listtreatments[$loc['locations']['id']] = $listd;
					}

					//$result->close();


					$this->set(compact('listtreatments'));


				}
				
				//create the new file
				//touch($filenameNew);
		 
				//$this->Session->setFlash('Not implemented yet', 'flash_success');
			
			
			//$this->autoRender = false;
			//$this->redirect( '/' );
		} else {
			define ('SCRIPT_PATH', ROOT.DS.APP_DIR . DS . 'webroot' . DS);
			$filename = SCRIPT_PATH . 'points.json';
			
			if ($fn = fopen($filename, 'w')) {
				/*$startTime = microtime();
				 do {
					$canWrite = flock($fnn, LOCK_EX);
					// If lock not obtained sleep for 0 - 100 milliseconds, to avoid collision and CPU load
					if(!$canWrite) usleep(round(rand(0, 100)*100));
				 } while ((!$canWrite)and((microtime()-$startTime) < 100)); */
				if (flock($fn, LOCK_EX)){
					fwrite($fn, $this->data['Stat']['JSONFile']);
					flock($fn, LOCK_UN);
					fclose($fn);
					$this->Session->setFlash('Points updated successfully', 'flash_success');
					$this->redirect( '/' );
				} else {
					$this->Session->setFlash('Points file is in use. Please try again.', 'flash_failure');
					$this->redirect( '/' );
				}
			 } else {
				$this->Session->setFlash('Can\'t open file please try again' . $filename, 'flash_failure');
				$this->redirect( '/' );
			}
			
		}
	}
}

<?php
class StatsController extends AppController {

	var $name = 'Stats';
	var $helpers = array('Html', 'Crumb', 'Javascript', 'Ajax', 'UpdateFile');
	var $components = array('RequestHandler');

	var $uses = array('Stat', 'Phone');

	//var $scaffold;

	function beforeFilter() {
		   		parent::beforeFilter();
		    	//$this->Auth->allow(array('*'));
		    	$this->Auth->allowedActions = array('');
   }

	function index() {
		$this->Stat->recursive = 0;
		$this->paginate['Stat'] = array('order' => 'Stat.created DESC');
		$this->set('stats', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid stat', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('stat', $this->Stat->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			if (($this->data['Stat']['drug_id'] == 0) && ($this->data['Stat']['treatment_id'] == 0)){
				$this->set('locations', $this->Stat->Location->find('list', array('conditions' => array('Location.id' => $this->data['Stat']['location_id'] ))));
				$this->Session->setFlash(__('You need to select drug or treatment for this report', true));
				$this->Stat->invalidate('drug_id', 'Please select drug or treatment below.');
				
			} else {
				$this->Stat->create();
				if ($this->Stat->save($this->data)) {
					$this->Session->setFlash('The stat has been saved', 'flash_success');
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('The report could not be saved. Please, try again.', true));
				}
			}
		}
		$drugs = $this->Stat->Drug->find('list');
		$treatments = $this->Stat->Treatment->find('list');
		$rawreports = $this->Stat->Rawreport->find('list');
		$phones = $this->Stat->Phone->find('list', array ('conditions' => 'Phone.deleted = 0')); 
		//$this->Stat->Location->find('list');
		$this->set(compact('drugs', 'treatments', 'rawreports', 'phones'));

	}

	function edit($id = null) {

		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid stat', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if (($this->data['Stat']['drug_id'] == 0) && ($this->data['Stat']['treatment_id'] == 0)){
				$this->set('locations', $this->Stat->Location->find('list', array('conditions' => array('Location.id' => $this->data['Stat']['location_id'] ))));
				$this->Session->setFlash(__('You need to select drug or treatment for this report', true));
				$this->Stat->invalidate('drug_id', 'Please select drug or treatment below.');
				
			} else {
				if ($this->Stat->save($this->data)) {
					$this->Session->setFlash('The stat has been saved', 'flash_success');
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('The stat could not be saved. Please, try again.', true));
				}
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Stat->read(null, $id);
		}

		$drugs = $this->Stat->Drug->find('list');
		$treatments = $this->Stat->Treatment->find('list');
		$rawreports = $this->Stat->Rawreport->find('list');
		$phones = $this->Stat->Phone->find('list', array ('conditions' => 'Phone.deleted = 0')); 
		//$locations = $this->Stat->Location->find('list');
		$this->set('locations', $this->Stat->Location->find('list', array('conditions' => array('Location.id' => $this->data['Stat']['location_id'] ))));

		$this->set(compact('drugs', 'treatments', 'rawreports', 'phones'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for stat', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Stat->delete($id)) {
			$this->Session->setFlash('Stat deleted', 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Stat was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}

	function update_select() {

			if (isset($this->data['Stat']['phone_id'])) {
				$phone = $this->Phone->findById($this->data['Stat']['phone_id']);
				$this->set('options', array($phone['Phone']['location_id']  => $phone['Location']['name']) );

			}

			if ( isset($this->data['Stat']['treatment_id'])) {
				$this->set('options', array('Stat.drug_id'  => 'Not allowed' ) );
			} else

			if (isset($this->data['Stat']['drug_id'] )) {
				$this->set('options', array('Stat.treatment_id'  => 'Not allowed' ) );
			}
			$this->render('update_select');
	}

//this functionlaity is moved to pages controller
	function updateJSONFile() {

		if (!($this->data['Stat']['JSONFile'])) {	
				//require_once('db_connect.php');
				/*$result = runQuery("SELECT * FROM locations");

				while ($row = $result->fetch_assoc()) {
					$locations[$row['id']] = $row;
				}
				*/
				$locations = $this->Stat->query('SELECT * FROM locations');
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
	
	function sdrugs($strFilter = null) {
		$locations = $this->Stat->query('SELECT * FROM locations');
		$this->set(compact('locations'));

		$listdrugs = array();
		foreach ($locations as $loc)
		{
			//drugs
			$query = "SELECT quantity, drugs.name as dname, drugs.id as did, created, phone_id as pid, stat_drugs.location_id, phones.phonenumber as pnumber, phones.name as pname, phones.deleted as pdeleted, rawreport_id, locations.name as lname ";
			$query .= "FROM stats stat_drugs, drugs, phones, locations ";
			$query .= "WHERE stat_drugs.drug_id = drugs.id ";
			$query .= "AND stat_drugs.phone_id = phones.id ";
			$query .= "AND stat_drugs.location_id = locations.id ";
			$query .= "AND stat_drugs.id = (select max(sa.id) from stats sa where sa.drug_id = stat_drugs.drug_id  ";
			$query .= "AND location_id =" . $loc['locations']['id'] . " ) ";
			$query .= "AND stat_drugs.location_id =" . $loc['locations']['id'] . " ";
			if (isset($this->data['Search']['search']) && !is_numeric($this->data['Search']['search']) ) {
				$query .= "AND (locations.name LIKE '%"  . $this->data['Search']['search'] . "%' ";
				$query .= "OR drugs.name LIKE '%"  . $this->data['Search']['search'] . "%') ";
			} else if (isset($this->data['Search']['search']) && is_numeric($this->data['Search']['search']) ) {
				$query .= "AND (stat_drugs.quantity <=  "  . $this->data['Search']['search'] . " )";
			}
			$query .= "ORDER by created DESC ";
			
			$listd = $this->Stat->query($query);

			if (!empty($listd)){
				$listdrugs[$loc['locations']['id']] = $listd;
			}

		}
		$this->set('listdrugs', $listdrugs);

	}
	function streatments() 
	{
		$locations = $this->Stat->query('SELECT * FROM locations');
		$this->set(compact('locations'));

		$listtreatments = array();
		foreach ($locations as $loc)
		{
			//treatments
			$query = "SELECT quantity, treatments.code as dname, treatments.id as did, created, phone_id as pid, stat_drugs.location_id, phones.phonenumber as pnumber, phones.name as pname, phones.deleted as pdeleted, rawreport_id, locations.name as lname  ";
			$query .= "FROM stats stat_drugs, treatments, phones, locations ";
			$query .= "WHERE stat_drugs.treatment_id = treatments.id ";
			$query .= "AND stat_drugs.phone_id = phones.id ";
			$query .= "AND stat_drugs.location_id = locations.id ";
			$query .= "AND stat_drugs.id = (select max(sa.id) from stats sa where sa.treatment_id = stat_drugs.treatment_id  ";
			$query .= "AND location_id =" . $loc['locations']['id'] . " ) ";
			$query .= "AND stat_drugs.location_id =" . $loc['locations']['id'] . " ";
			if (isset($this->data['Search']['search']) && !is_numeric($this->data['Search']['search'])) {
				$query .= "AND (locations.name LIKE '%"  . $this->data['Search']['search'] . "%' ";
				$query .= "OR treatments.code LIKE '%"  . $this->data['Search']['search'] . "%') ";
			} else if (isset($this->data['Search']['search']) && is_numeric($this->data['Search']['search']) ) {
				$query .= "AND (stat_drugs.quantity <=  "  . $this->data['Search']['search'] . " )";
			}
			$query .= "ORDER by created DESC ";
			
			$listd = $this->Stat->query($query);

			if (!empty($listd)){
				$listtreatments[$loc['locations']['id']] = $listd;
			}

		}
		$this->set('listtreatments', $listtreatments);
	
	}
	
	//options action to cater for the last n digits
	function  options() {
        
		define ('SCRIPT_PATH', ROOT.DS.APP_DIR . DS . 'webroot' . DS);
		$filename = SCRIPT_PATH . 'config.php';
		$filename = SCRIPT_PATH . 'config.php';
		$contents = "";
		$fContents = "";
		$ndigits = '';
		
		if (!($this->data['Stat']['ndigits'])) {
			if (is_writable($filename)) {
				if ($handle = fopen($filename, 'r')) {
					 while (!feof($handle)) {
						 $contents = fgets($handle); //read line from file
						 if (stristr($contents, 'define ("PHONE_NUMBER_LENGTH",')) {
							$fContents .= 'define ("PHONE_NUMBER_LENGTH", ' . $this->data['Stat']['ndigits']  . ");\n";
							preg_match('/([\d]+)/', $contents, $match);
							$this->data['Stat']['ndigits'] = $match[0];
							$this->data['Stat']['ndigitsOld'] = $match[0];
						} else {
							$fContents .= $contents;
						}
					}
					fclose($handle);
				}
			}  else {
					$this->Session->setFlash('Config file not writeable', 'flash_failure');
				}	
		} else {
			if (($this->data['Stat']['ndigitsOld'] < $this->data['Stat']['ndigits']) 
				|| $this->data['Stat']['ndigits'] == '' 
				|| !is_numeric($this->data['Stat']['ndigits'])){
				$this->Session->setFlash(__('Last n digits cannot be empty  or less then the previous value', true));
				$this->Stat->invalidate('ndigits', 'Please enter numeric value less than the previous value used: <= '. $this->data['Stat']['ndigitsOld']);
			} else {
				 if (is_writable($filename)) {
					if ($handle = fopen($filename, 'r')) {
						 while (!feof($handle)) {
							 $contents = fgets($handle); //read line from file
							 if (stristr($contents, 'define ("PHONE_NUMBER_LENGTH",')) {
								$fContents .= 'define ("PHONE_NUMBER_LENGTH", ' . $this->data['Stat']['ndigits']  . ");\n";
							} else {
								$fContents .= $contents;
							}
						}
						fclose($handle);
						if ($handle = fopen($filename, 'w')) {
							if (!flock($handle, LOCK_EX)) {
								$this->Session->setFlash('Cannot lock  file', 'flash_failure');
								 //echo "Cannot lock  file ($filename)";
							 }
							 fwrite ($handle, $fContents );
					
						}
					} else {
						$this->Session->setFlash('Cannot open  file', 'flash_failure');
						//echo "Cannot open file ($filename)" ;
						//exit;
					}		
					flock($handle, LOCK_UN);
					fclose($handle);
						
				} else {
					$this->Session->setFlash('Config file not writeable', 'flash_failure');
				}
				$this->Session->setFlash('Options updated successfully', 'flash_success');
				$this->redirect( '/' );
			}
		}
    }
}
?>
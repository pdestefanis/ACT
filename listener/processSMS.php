<?php
class Action{
	/* TODO can I move this to a config file instead? */
	public $send = array ('ASSIGN', 'SEND', 'A');
	public $receive = array ('RECEIVE', 'R');
	public $expire = array ('EXPIRE', 'E');
	public $consent = array ('CONSENT', 'C');
	public $create = array ('CREATE', 'CR');
	private $actions = NULL;
	private static $instance;
	
	function __construct() {} 
	//build a list of actions only once
	public static function getInstance(){
		if (!self::$instance) {
			self::$instance = new Action();
		}
		return self::$instance;
	}
	/*
	 * Buld a list of actions
	 */
	private function buildActions() {
		$a = array();
		$r = new ReflectionObject($this);
		$props = $r->getProperties(ReflectionProperty::IS_PUBLIC);
		foreach ($props as $p) {
			$a[$p->getName()] = $p->getValue($this);
		}
		$this->actions  = $a;
	}

	public function getActions() {
		if ( is_null($this->actions))
			$this->buildActions();
		return $this->actions;
	}
}
	require_once('utils.php');
	require 'Pest.php';
	define('__ROOT__', dirname(dirname(__FILE__))); //this is a workaround for the require
	//cater for commandline input as well
	//all the message must be enclosed in quotes
	if (isset($argv[1]))
		echo init($argv[1], $argv[2]);
	
	function init($msg, $caller){
		//TODO move this into config file
		//app api URL
		$pest = new Pest('http://localhost:10080/testing');
		$headers = array(
				//Username and password to use for API login
				'Authorization: TRUEREST username=admin&password=admin12&apikey=247b5a2f72df375279573f2746686daa',
				'Content-Type: text/xml'
		);
		error_reporting(-1);
		//THIS MUST BE CHANGED FOR EACH PROJECTS' TIMEZONE
		//OTHERWISE PHP DATE AND MYSQL DATE FROM THE WEBAPP MAY BE DIFFERENT
		//THIS MAY BE A WINDOWS BUG
		//TODO move it to the config page on the app
		//date_default_timezone_set('Africa/Johannesburg');
		date_default_timezone_set('America/New_York'); //for EDT current v08 timezone
		$currDate = date("Y-m-d H:i:s");
		
		//replace all non alphanumerical characters with space
		//this is to accommodate for different/mix of delimeters
		$origMsg = trim($msg);
		$msg = preg_replace("/[^a-zA-Z0-9\s]/", " ", $msg); //to spaces	
		$msg = preg_replace("'\s+'", ' ', $msg);//multi spaces to single space
		$msg = strtoupper($msg);
		
		//get all actions
		$action = Action::getInstance();
		$actions = $action->getActions();
		$matchedAction = NULL;
		$matchedPatient = NULL;
		$matchedFacility = NULL;
		$matchedUnits = NULL;
		$matchedDate = NULL;
		$suppDate = NULL;
		$thing = NULL;
		$actString = null;
		//loop through all actions and see if sms contains one
		foreach ($actions as $act => $aliases) {
			foreach ($aliases as $key => $alias) {
				$what = "/\b$alias\b/";
				if (preg_match($what, $msg, $matched) ) {
					$matchedAction[] = strtoupper($act);
					$actString =$alias;
					$msg = preg_replace("[\b" . $alias . "\b]", '', $msg); //remove action from message
				}
			}
		}

		//check if date is supplied //removed for now |20[0-9]{2}
		//to avoid matching kit number for year \d{4} avoid matching if 1 or 2 digits follow
		$what = "/(\b([0-9]{2}|[0-9]{4})\D([0-9]{1,2})\D([0-9]{1,2})\b)(?!\b\s\d{1,2}\b)/";
		preg_match($what, $msg, $matchedDate);
		//we have backentry
		if (isset($matchedDate[0])) {
			//dont know why match fills first two array elements with same data remove one
			array_shift($matchedDate);
			$suppDate = $matchedDate[1] . "-" . $matchedDate[2] . "-" .$matchedDate[3];
			$msg = preg_replace("[" . $matchedDate[0] . "]", '', $msg);
		} else {
			$suppDate = date("Y") ."-" . date("m") ."-" . date("d");
		}
		//check for presence of patient
		//Patient starts with a P and contains 5-6 digits
		$what = "/\b[P|7][0-9]{5,6}\b/";
		preg_match($what, $msg, $matchedPatient);
		if (isset($matchedPatient[0])) {
			$msg = preg_replace("[" . $matchedPatient[0] . "]", '', $msg);
		}
		
		//check for facility
		//if (isset($matchedAction[0]) ) 
			//don't match the action which can be {2,3} but also don't match parts of the date
		//	$what = "/(?!".$actString.")(?!\b([0-9]{2})[\D]([0-9]{1,2})[\D]([0-9]{1,2})\b)\b[0-9|A-z]{2,3}\b/"; 
		//else if (isset($matchedAction[0]) && isset($matchedDate[0])) 
		//	$what = "/\b[^" . $matchedAction[0] . "]\b" . "\b[^(" . $matchedDate[0] . ")]\b" . "\b[0-9|A-Z]{2,3}\b/"; //exclude actions from matching
		//else 	
		$what = "/\b[A-JL-OQ-Z]{1}[A-Z|0-9]{3,6}\b/";
		if (preg_match($what, $msg, $matchedFacility) ) {
			$matchedFacility[0] = trim($matchedFacility[0]);
			$msg = preg_replace("[" . $matchedFacility[0] . "]", '', $msg);
		}
		
		$what = "/\bKIT[0-9]{4}\b|\b[0-9]{4}\b/";
		preg_match_all($what, $msg, $matchedUnits);
		if (isset($matchedUnits[0])) {
			foreach ($matchedUnits[0] as $key => $unit) {
				$matchedUnits[$key] = str_replace('KIT', '', $unit);
				$msg = preg_replace("[KIT]", '', $msg);
				$msg = preg_replace("[" . $matchedUnits[$key] . "]", '', $msg);
				//TODO untill this is implemented only process first kit
				break;
			}
		}
	
		//TODO have an auth function to make sure you can authenticate.
		//Also have a disconect. Auth authentication may require to extent Pest class to handle the exception properly
		
		//if we have actionless operation rather add the action and treat it as regular
		// instead of providing spagetti for handling this
		if (is_null($matchedAction) && !empty($matchedUnits[0]) && isset($matchedFacility[0]) && !isset($matchedPatient[0]))
			$matchedAction = array('SEND');
		else if (is_null($matchedAction) && !empty($matchedUnits[0]) && !isset($matchedFacility[0]) && isset($matchedPatient[0]))
			$matchedAction = array('SEND');
		else if (is_null($matchedAction) && !empty($matchedUnits[0]) && !isset($matchedFacility[0]) && !isset($matchedPatient[0]))
			$matchedAction = array('RECEIVE');
					
		$msg = trim($msg);

//echo $msg  . "\n" . print_r($matchedDate, true) . "\n" . print_r($matchedPatient, true) ."\n" .  
			print_r($matchedFacility, true) . "\n"  . print_r($matchedUnits, true) . "\n"  . print_r($matchedAction, true) . "\n";
		if (strlen($msg) != 0 || strlen($origMsg) ==0) { // we coudn't recognize everything in the string reject message
			//TODO send back what wasnt recognized
			$thing = $pest->get('/apis/rejectMessage/' . $caller . '/msgUnrecognized.xml', $headers);
		} else if (empty($matchedUnits[0]) && $matchedAction[0]  != 'CONSENT') { //unit always required
			$thing = $pest->get('/apis/rejectMessage/' . $caller . '/noUnit.xml', $headers);
		} else {
			//TODO mulitple units is not proceesed yet
			if (!is_null($matchedAction) && (!empty($matchedUnits[0]) || $matchedAction[0]  == 'CONSENT' )) {
				//action supplied
				//make sure it is only one
				if (!isset($matchedAction[1])){
					if ($matchedAction[0]  == 'SEND') { //unit or facility + phone number are required
					/* 	if (!isset($matchedFacility[0]) && !isset($matchedPatient[0]) ) { 
							//Assignment without a patient or facility
							$thing = $pest->get('/apis/assign/' . $caller . "/" . $matchedUnits[0] .  ".xml", $headers); 
						} else */ 
						if(isset($matchedFacility[0]) && !isset($matchedPatient[0])) {
							$thing = $pest->get('/apis/assignToFacility/' . $caller . "/" . 
									$matchedUnits[0] .  "/" . $matchedFacility[0] . '/'. $suppDate .  ".xml", $headers);
						} else if(!isset($matchedFacility[0]) && isset($matchedPatient[0])) {
							$thing = $pest->get('/apis/assignToPatient/' . $caller . "/" . 
								$matchedUnits[0] .  "/" . $matchedPatient[0] . '/'.$suppDate . ".xml", $headers); 
						} else {
							$thing = $pest->get('/apis/rejectMessage/' . $caller . '/lessMissParams.xml', $headers);
						}
					} else if ($matchedAction[0]  == 'RECEIVE') {
						if (isset($matchedFacility[0]) && !isset($matchedPatient[0])) {
							$thing = $pest->get('/apis/receiveUnit/' . $caller .  "/" . 
									$matchedUnits[0] . "/" . $matchedFacility[0]. '/'. $suppDate .   ".xml", $headers);
						} else if (!isset($matchedFacility[0]) && !isset($matchedPatient[0]) ){
							$thing = $pest->get('/apis/receiveUnit/' . $caller .  "/" . $matchedUnits[0] . '/_/'. $suppDate .  ".xml", $headers);
						} else {
							$thing = $pest->get('/apis/rejectMessage/' . $caller . '/lessMissParams.xml', $headers);
						}
					} else if ($matchedAction[0]  == 'EXPIRE') {
						if(isset($matchedFacility[0])) {
							$thing = $pest->get('/apis/discardUnit/' . $caller .  "/" . 
									$matchedUnits[0] . "/" . $matchedFacility[0] . '/'. $suppDate .  ".xml", $headers);
						 } else if (!isset($matchedFacility[0])){
							$thing = $pest->get('/apis/discardUnit/' . $caller .  "/" .
									$matchedUnits[0] . '/_/'. $suppDate .  ".xml", $headers); 
						} else {
							$thing = $pest->get('/apis/rejectMessage/' . $caller . '/lessMissParams.xml', $headers);
						}
					} else if ($matchedAction[0]  == 'CREATE') {
						if (isset($matchedFacility[0])) {
							$thing = $pest->get('/apis/createUnit/' . $caller .  "/" . 
									$matchedUnits[0] . "/" . $matchedFacility[0] . '/'. $suppDate .  ".xml", $headers);
						} else {
							$thing = $pest->get('/apis/rejectMessage/' . $caller . '/lessMissParams.xml', $headers);
						}
					} else if ($matchedAction[0]  == 'CONSENT') {
						if (isset($matchedPatient[0])) {
							$thing = $pest->get('/apis/patientConsent/' . $caller .  "/" .
									$matchedPatient[0] .  ".xml", $headers);
						} else {
							$thing = $pest->get('/apis/rejectMessage/' . $caller . '/lessMissParams.xml', $headers);
						}
					}
				} else {
					//more then one action detectedreject
					$thing = $pest->get('/apis/rejectMessage/' . $caller . '/moreActions.xml', $headers);
				}
			/* } else if (isset($matchedUnits[0]) && !isset($matchedPatient[0])) {
				//action not supplied
				//call actionless assign/receive
				if (isset($matchedUnits[0]) && !isset($matchedFacility[0]) && isset($matchedPatient[0]))
					$thing = $pest->get('/apis/assignToPatient/' . $caller . "/" . 
									$matchedUnits[0] .  "/" . $matchedPatient[0] . ".xml", $headers); 
				else if (isset($matchedUnits[0]) && isset($matchedFacility[0]) && !isset($matchedPatient[0]))
					$thing = $pest->get('/apis/assignToFacility/' . $caller . "/" . 
									$matchedUnits[0] .  "/" . $matchedFacility[0] . ".xml", $headers); 
				else if (isset($matchedUnits[0]) && !isset($matchedFacility[0]) && !isset($matchedPatient[0]))
					$thing = $pest->get('/apis/assign/' . $caller . "/" . $matchedUnits[0] .  ".xml", $headers);	 */
	
			} else { //we need at least the unit to be supplied
				$thing = $pest->get('/apis/rejectMessage/' . $caller . '/lessMissParams.xml', $headers);
			}
		}
		//echo getResult($thing); //TODO remove this testing only
		return getResult($thing);
	}
	/*
	 * Return the message part of the XML
	 */
	function getResult($thing) {
		$util = new Utils();
		//TODO direct XML processing could replace this
		$array = $util->xmlToArray($thing); //convert xml to array
		//for now we only care about the message wether 
		//it is an error or info doesn't matter here just return the message
		$find = 'message'; 
		$result = array();
		$util->findArrayElement($array, $find, $result);
		if (isset($result['_v']))
			return $result['_v'];
		else 
			return "Unexpected error";
	}
?>


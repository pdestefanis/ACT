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
	
	//TODO for testing purpose only
	init(' r 1004..smt', '+15553349901');
	
	define('__ROOT__', dirname(dirname(__FILE__))); //this is a workaround for the require
	require_once(__ROOT__ . '/config/options.php'); //use this configuration so that we can make use of App::Configure in cake for the form

	define ("PHONE_NUMBER_LENGTH", $config['Phone']['length']);
	
	function init($msg, $caller){
		//TODO move this into config file
		//app api URL
		$pest = new Pest('http://localhost:10080/track');
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
		date_default_timezone_set('Africa/Johannesburg');
		//date_default_timezone_set('America/New_York'); //for EDT current v08 timezone
		$currDate = date("Y-m-d H:i:s");
		
		//replace all non alphanumerical characters with space
		//this is to accommodate for different/mix of delimeters
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
		$thing = NULL;
		
		//loop through all actions and see if sms contains one
		foreach ($actions as $act => $aliases) {
			foreach ($aliases as $key => $alias) {
				$what = "/\b$alias\b/";
				if (preg_match($what, $msg, $matched) ) {
					$matchedAction[] = strtoupper($act);
				}
			}
		}
		
		//check for presence of patient
		//Patient starts with a P and contains 5-6 digits
		$what = "/\b[P][0-9]{5,6}\b/";
		preg_match($what, $msg, $matchedPatient);
		
		//check for facility
		if (isset($matchedAction[0])) 
			$what = "/\b[^" . $matchedAction[0] . "]\b" . "\b[0-9|A-Z]{2,3}\b/"; //exclude actions from matching
		else 	
			$what = "/\b[0-9|A-Z]{2,3}\b/";
		if (preg_match($what, $msg, $matchedFacility) )
			$matchedFacility[0] = trim($matchedFacility[0]);

		//Kits - calling it kits for preparation of multiple kits processing
		$what = "/\bKIT{0,1}[0-9]{4}\b|\b[0-9]{4}\b/";
		preg_match($what, $msg, $matchedUnits);
		
		//TODO try matching for an unknown => everything but the above
		// if it exists what should we do reject or process as much as we understand?
		
		//TODO have an auth function to make sure you can authenticate.
		//Also have a disconect. Auth authentication may require to extent Pest class to handle the exception properly
		
		//TODO mulitple units is not proceesed yet
		if (!is_null($matchedAction) ) {
			//action supplied
			//make sure it is only one
			if (!isset($matchedAction[1])){
				if ($matchedAction[0]  == 'SEND') { //unit or facility + phone number are required
					if (!isset($matchedFacility[0]) && !isset($matchedPatient[0]) ) { 
						//Assignment without a patient or facility
						$thing = $pest->get('/apis/assign/' . $caller . "/" . $matchedUnits[0] .  ".xml", $headers); 
					} else if(isset($matchedFacility[0]) && !isset($matchedPatient[0])) {
						$thing = $pest->get('/apis/assignToFacility/' . $caller . "/" . 
								$matchedUnits[0] .  "/" . $matchedFacility[0] . ".xml", $headers); 
					} else if(!isset($matchedFacility[0]) && isset($matchedPatient[0])) {
						$thing = $pest->get('/apis/assignToPatient/' . $caller . "/" . 
								$matchedUnits[0] .  "/" . $matchedPatient[0] . ".xml", $headers); 
					}
				} else if ($matchedAction[0]  == 'RECEIVE') {
					if (isset($matchedFacility[0]) && isset($matchedUnits[0])) {
						$thing = $pest->get('/apis/receiveUnit/' . $caller .  "/" . 
								$matchedUnits[0] . "/" . $matchedFacility[0] .   ".xml", $headers);
					} else if (isset($matchedUnits[0])){
						$thing = $pest->get('/apis/receiveUnit/' . $caller .  "/" . $matchedUnits[0]  .".xml", $headers);
					} else {
						$thing = $pest->get('/apis/rejectMessage/apis/rejectMessage/lessMissParams.xml', $headers);
					}
				} else if ($matchedAction[0]  == 'EXPIRE') {
					if(isset($matchedFacility[0]) && isset($matchedUnits[0])) {
						$thing = $pest->get('/apis/discardUnit/' . $caller .  "/" . 
								$matchedUnits[0] . "/" . $matchedFacility[0] .   ".xml", $headers);
					} else if (isset($matchedUnits)){
						$thing = $pest->get('/apis/discardUnit/' . $caller .  "/" .
								$matchedUnits[0] .  ".xml", $headers);
					} else {
						$thing = $pest->get('/apis/rejectMessage/apis/rejectMessage/lessMissParams.xml', $headers);
					}
				} else if ($matchedAction[0]  == 'CREATE') {
					if (isset($matchedFacility[0]) && isset($matchedUnits[0])) {
						$thing = $pest->get('/apis/createUnit/' . $caller .  "/" . 
								$matchedUnits[0] . "/" . $matchedFacility[0] .   ".xml", $headers);
					} else {
						$thing = $pest->get('/apis/rejectMessage/apis/rejectMessage/lessMissParams.xml', $headers);
					}
				} else if ($matchedAction[0]  == 'CONSENT') {
					if (isset($matchedPatient[0])) {
						$thing = $pest->get('/apis/patientConsent/' . $caller .  "/" .
								$matchedPatient[0] .  ".xml", $headers);
					} else {
						$thing = $pest->get('/apis/rejectMessage/apis/rejectMessage/lessMissParams.xml', $headers);
					}
				}
			} else {
				//more then one action detectedreject
				$thing = $pest->get('/apis/rejectMessage/moreActions', $headers);
			}
		} else {
			//action not supplied
			//call actionless assign/receive
			if (isset($matchedUnits[0]))
				$thing = $pest->get('/apis/assign/' . $caller . "/" . $matchedUnits[0] .  ".xml", $headers);//$facility.
		}
		//echo getResult($thing); //TODO remove this testing only
		return getResult($thing);
		exit;
		
	}
	/*
	 * Return the message part of the XML
	 */
	function getResult(&$thing) {
		$util = new Utils();
		//TODO direct XML processing could replace this
		$array = $util->xmlToArray($thing); //convert xml to array
		//for now we only care about the message wether 
		//it is an error or info doesn't matter here just return the message
		$find = 'message'; 
		$result = array();
		$util->findArrayElement($array, $find, $result);
		return $result['_v'];
	}
?>


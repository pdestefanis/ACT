<?php
//emulation of enum
class modifier {
    const EQUAL = '=';
    const MINUS = '-';
	const PLUS = '+';
}

class action{
	/* public $query =  array ('QUERY', 'Q', 'ITEM');
	public $approve = array ('APPROVE', 'A', 'OK');
	public $count = array ('COUNT', 'C'); */
	public $send = array ('SEND', 'S');
	public $receive = array ('RECEIVE', 'R');
	public $expire = array ('EXPIRE', 'E');
	public $consent = array ('CONSENT', 'C');
	
}

class sms {
	private $item;
	private $qty;
	private $phone;
	private $modifier;
	private $action;
	private $facility;
	private $patient;
	
	function __construct($args) {
		// Forms are also being processed under the <None> keyword rules
		// This is a hack to skip the message processing if a form is received
		// I would like for this to report nothing back, but FLSMS will still report.
		// Requested to fix that in the forums: http://frontlinesms.ning.com/forum/topics/binary-forms-going-to-the

		/* if (substr($args[1],0,10) == "AAI+gCUAAA") {
			exit;
		} */
		
		if (is_numeric($args[2]) )
			$this->phone = $args[2];
		else 
			$this->phone = NULL;
		
		//an item code is sent
		/* if (strpos($args[1], ' ') === FALSE )
			$action = "item";
		else */
		$argsEx = explode(" ", $args[1]); //explode the message 0 - action, 1 - qty/patient, 2 - facility
		if (isset($argsEx[0]))
			$action = $argsEx[0];
		//action case
		if ($this->checkAction($action) ) { //action gets set in checkAction
			
			if (count($argsEx) != 0) {
				//determine if patient or qty
				if (isset($argsEx[1]) && isset($argsEx[2]) ) {
					if ($argsEx[2] < MIN_PATIENT_NUM) { //facility
						$this->qty = $argsEx[1]; 
						$this->facility = strtoupper($argsEx[2]);
						$this->patient = NULL;
					} else {
						$this->qty = $argsEx[1];  //qty always 1 with patients
						$this->patient = $argsEx[2]; 
						$this->facility = NULL;
					}
				} else if (isset($argsEx[1]) && !isset($argsEx[2]) ) {
					if ($argsEx[1] < MIN_PATIENT_NUM) {	
						$this->qty = $argsEx[1];
						$this->patient = NULL; 
					} else if ($this->action != 'expire'){
						$this->qty = 1; 
						$this->patient = $argsEx[1]; 
					} else {
						$this->qty = $argsEx[1]; 
					}
				}
			}
		} else 
			$this->action = -1;
		
		/* else { //update case	
			$this->item = substr($args[1], 0, strpos($args[1], ' '));
			$this->modifier = substr($args[1], strpos($args[1], ' ')+1, 1);
			if (is_numeric($this->modifier) ) { //no modifier given
				$this->qty = substr($args[1], strpos($args[1], $this->modifier), strlen($args[1])) +0; 
				if (!is_numeric($this->qty) )
					$this->qty = NULL;
				$this->modifier = NULL;
			} else {
				$this->qty = substr($args[1], strpos($args[1], $this->modifier) + 1, strlen($args[1])) +0; //+0 to remove sign if positive number
				if (!is_numeric($this->qty) )
					$this->qty = NULL;
			}
		} */
		
	}    
	
	function getPhone () {
		return $this->phone;
	}
	
	function getItem () {
		return $this->item;
	}
	
	function getQty () {
		return $this->qty;
	}
	
	function getModifier () {
		return $this->modifier;
	}
	
	function getAction () {
		return $this->action;
	}
	
	function getFacility () {
		return $this->facility;
	}
	
	function getPatient () {
		return $this->patient;
	}
	
	function setModifier ($mod) {
		$this->modifier = $mod;
	}
	
	function check() { //check all fields are set except modifier
		if ($this->qty != NULL  && $this->phone != NULL)
			return TRUE;
		else 
			return FALSE;
	}
	
	function checkQty() { //check quantity for patient is <> 1
		if ($this->qty != 1  && $this->patient != NULL)
			return FALSE;
		else 
			return TRUE;
	}
	
	function checkModifier() {
		$mods = new modifier;
		$enums = new ReflectionObject($mods);
		return in_array($this->modifier, $enums->getConstants());
	}
	
	function checkAction($a) {
		$act = new action;
		$r = new ReflectionObject($act);
		$props = $r->getProperties();
		foreach ($props as $p) {
			if (in_array(strtoupper($a), $p->getValue($act)))  {
				$this->action = $p->getName();
				return TRUE;
			}
		}
		return FALSE;
	}

}

?>
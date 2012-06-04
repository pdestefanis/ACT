<?php

class smsManipulation {
	private $children;
	private $locations;
	private $listitems;
	private $dbManip;
	private $sms;
	private $phoneId;
	private $phoneStatus;
	private $locationId;
	private $itemId;
	private $qtyAfter;
	private $currDate;
	private $receivedId;
	private $approvalId;
	private $userId;
	private $args;
	private $modifier;
	private $rawreportId;
	private $facilityId;
	private $patientId;
	private $patient;
	private $patientConsent;
	private $raw;
	private $dirty;
	
	
	function __construct(&$dbManip, $args, $currDate) {
		$this->currDate = $currDate;
		$this->dbManip = $dbManip;
		$this->args = explode("|", trim(urldecode(implode("|", $args)))); //also remove space from begining and end
		//remove spaces from middle if more then one
		$arg2 = '';
		foreach (explode(' ', $this->args[1]) as $a) {
			if ($a == "") //spaces in array are nulls from explode
				continue;
			$arg2 .=  $a . ' ';
		} 
		$this->args[1] = trim($arg2);
		$this->locations = $this->dbManip->getLocations();
		$this->dirty = FALSE;
		$this->patientId = -1;
		$this->facilityId = -1;
	}
		
	function initSms (&$sms) {
		$this->sms = $sms;
		
		$this->phoneId = $this->dbManip->getPhoneId(end($this->args));
		if ($this->phoneId == -1) {
			$this->dbManip->setPhone(end($this->args)); //insert the not found phone in the database as inactive
			
			$this->phoneId = $this->dbManip->getPhoneId(end($this->args));
			$raw =  "Error: phone number " . end($this->args) . " not found in database. It has been added but you won't be able to enter data until you request activation\n";
			$this->dbManip->setSent($this->phoneId, $this->currDate, $raw, $this->getReceivedId ());
			$this->dirty = TRUE;
			$this->raw = $raw;
			return;
		}
		
		$this->phoneStatus = $this->dbManip->getPhoneStatus($this->phoneId);
		if ($this->phoneStatus == 0) {
			$this->phoneId = $this->dbManip->getPhoneId($this->sms->getPhone());
			$raw =  "Error: phone number " . $this->sms->getPhone() . " is not active. You won't be able to enter data until you request activation\n";;
			$this->dbManip->setSent($this->phoneId, $this->currDate, $raw, $this->getReceivedId ());
			$this->dirty = TRUE;
			$this->raw = $raw;
			return;
		}
		$this->locationId = $this->dbManip->getLocation($this->sms->getPhone(), $this->phoneId);
		if ($this->locationId == -1) {
			$raw =  "This phone is not assigned to a facility. The update will not be processed. Please contact the central office.\n";
			$this->dbManip->setSent($this->phoneId, $this->currDate, $raw, $this->getReceivedId ());
			$this->dirty = TRUE;
			$this->raw = $raw;
			return;
		}
		
		$this->qtyAfter = $this->dbManip->getQuantityAfter($this->itemId, $this->locationId);
		if ($this->qtyAfter == -1) 
			$this->qtyAfter = 0; //no quantity first submission set it to zero
		
	}
	
	function getDirty() {
		return $this->dirty;
	}
	
	function getRaw() {
		return $this->raw;
	}
	
	function getChildren (){
		return $this->children;
	}
	
	
	function findChildren($loc) {
		$child = array_keys($this->locations, $loc);
		foreach (array_values($child) as $c) {
			if ($c == NULL)
				continue;
			$this->children[] = $c; 
			$this->findChildren($c);	
		}
	}
	
	function getChildrenSum() {
		if ($this->children == NULL){
			$raw =  "Your facility does not contain sub facilities. You can get a count for your facility by sending the item code\n";
			$this->dbManip->setSent($this->phoneId, $this->currDate, $raw, $this->getReceivedId ());
			return $raw;
		}
		$listd = $this->dbManip->getChildrenSum($this->children, $this->itemId);
		
		if ($listd == -1) {
			$raw =  "There are no quanities recorded for this item\n";
			$this->dbManip->setSent($this->phoneId, $this->currDate, $raw, $this->getReceivedId ());
			return $raw;

		}
		$sum = NULL;
		foreach ($listd as $l) {
			if (isset($sum[$l['did']])) { //sum up qty for each drug and cread a stat ids in sid array for each summed up drug
				$sum[$l['did']]['sum'] += $l['quantity_after'];
				$sum[$l['did']]['sid'][] = $l['sid'];
			} else {
				$sum[$l['did']]['sum'] = $l['quantity_after'];
				$sum[$l['did']]['sid'][] = $l['sid'];
			}
		}
		
		return $sum;
	}
	function getChildrenAndParentSum() {

		$this->children[] = $this->locationId;
		$listd = $this->dbManip->getChildrenSum($this->children, $this->itemId);
		
		if ($listd == -1) {
			$raw =  "There are no quanities recorded for this item\n";
			$this->dbManip->setSent($this->phoneId, $this->currDate, $raw, $this->getReceivedId ());
			return $raw;
		}
		$sum = NULL;
		foreach ($listd as $l) {
			if (isset($sum[$l['did']])) { //sum up qty for each drug and create a stat ids in sid array for each summed up drug
				$sum[$l['did']]['sum'] += $l['quantity_after'];
				$sum[$l['did']]['sid'][] = $l['sid'];
			} else {
				$sum[$l['did']]['sum'] = $l['quantity_after'];
				$sum[$l['did']]['sid'][] = $l['sid'];
			}
		}
		
		return $sum;
	}
	
	function getReceivedId () {
		if (empty($this->receivedId)) {
			$this->receivedId = $this->dbManip->setReceived ($this->phoneId, $this->currDate, $this->args[1] . " " . end($this->args));
			if ($this->receivedId == -1) {
				$this->raw = "There was an error processing your message (received id). Please resend\n";
				$this->dirty = TRUE;
				$this->raw = $raw;
				return;
			}
		}
		return $this->receivedId;
	}
	
	function getApprovalId() {
		if (empty($this->approvalId)) {
			$this->approvalId = $this->dbManip->setApproval($this->userId, $this->currDate, $this->receivedId); //insert approval record
			if ($this->approvalId == -1) {
				$raw = "There was an error processing your message (approvalId). Please resend\n";
				$this->dbManip->setSent($this->getPhoneId(), $this->currDate, $raw, $this->getReceivedId ());
				$this->dirty = TRUE;
				$this->raw = $raw;
				return;
			}
		}
		return $this->approvalId;
	}
	
	function setUserId() {
		$this->userId = $this->dbManip->getUser ($this->phoneId ); //confirm user is assigned to this phone
		if ($this->userId == -1) {
			$raw = "Phone number ". $this->sms->getPhone() . " is not assigned to a user. You cannot send approvals\n";
			$this->dbManip->setSent($this->getPhoneId(), $this->currDate, $raw, $this->getReceivedId ());
			$this->dirty = TRUE;
			$this->raw = $raw;
			return;
		}
	}
	
	function setItemId ($item) {
		$this->itemId = $item;
	}

	function getPhoneId () {
		return $this->phoneId;
	}
	
	function getLocationId () {
		return $this->locationId;
	}
	
	function getItemId () {
		return $this->itemId;
	}
	
	function getQtyAfter () {
		return $this->qtyAfter;
	}
	
	function getCurrDate () {
		return $this->currDate;
	}
	
	function getArgs () {
		return $this->args;
	}
	
	function getFacilityId () {
		return $this->facilityId;
	}
	
	function getPatientId () {
		return $this->patientId;
	}
	
	function setModifier () {
		//populate modifier array with mod id and name
		$this->modifier = $this->dbManip->getModifier($this->sms->getItem(), $this->sms->getModifier());
		if (isset($this->modifier['mname']))	
			$this->sms->setModifier($this->modifier['mname']);
		//}
	
		if ($this->modifier == -1 ) {
			$raw = "Default modifier for: " . $this->sms->getItem() . " is not set please include  a valid modifier in your report and re-send. Message not processed!" ;
			$this->dbManip->setSent($this->getPhoneId(), $this->currDate, $raw, $this->getReceivedId ());
			$this->dirty = TRUE;
			$this->raw = $raw;
			return;
		}
	}
	
	function checkArguments(){
		// If not complete set of arguments record the raw sms and exit
		// Also, check that the SMS contents only carry one value pair
		$a = explode (' ', $this->args[1]);

		if ( (count($a) > 3 || count($a) < 2) //|| ($this->sms->getAction() == "send" && ($this->sms->getPatient() != NULL || $this->sms->getFacility() == NULL))
					|| ($this->sms->getAction() == "send" && ($this->sms->getPatient() == NULL && $this->sms->getFacility() == NULL))
					|| ($this->sms->getAction() == "receive" && count($a) != 2)
					|| ($this->sms->getAction() == "expire" && count($a) != 2)
					|| ($this->sms->getAction() == "consent" && count($a) != 2)
					) { 
			$raw = "Incorrect report format. Please correct and resend";
			$this->dbManip->setSent($this->getPhoneId(), $this->currDate, $raw, $this->getReceivedId ());
			$this->dirty = TRUE;
			$this->raw = $raw;
			return;
		}
		
		if ($this->sms->getAction() == -1) {
			
			$raw = "Cannot find action " . $a[0]. ". Please verify and resend\n";
			$this->dbManip->setSent($this->getPhoneId(), $this->currDate, $raw, $this->getReceivedId ());
			$this->dirty = TRUE;
			$this->raw = $raw;
			return;
		}
		
		if (!is_numeric($this->sms->getQty())) {
			$raw = "The quantity for the item must be numeric, but received ".$this->sms->getQty().". Please verify and resend\n";
			$this->dbManip->setSent($this->getPhoneId(), $this->currDate, $raw, $this->getReceivedId ());
			$this->dirty = TRUE;
			$this->raw = $raw;
			return;
		}
		if ($this->sms->getQty() > MAX_QTY) {
			$raw = "The quantity cannot be that high: ".$this->sms->getQty().". Please verify and resend\n";
			$this->dbManip->setSent($this->getPhoneId(), $this->currDate, $raw, $this->getReceivedId ());
			$this->dirty = TRUE;
			$this->raw = $raw;
			return;
		}
		
		if (!$this->sms->checkQty()) {
			$raw = "Quantityfor patient cannot be specified: ".$this->sms->getQty().". Please use only patient id\n";
			$this->dbManip->setSent($this->getPhoneId(), $this->currDate, $raw, $this->getReceivedId ());
			$this->dirty = TRUE;
			$this->raw = $raw;
			return;
		}

	}
	
	function processSms() {
		
		if ($this->receivedId == -1) {
			$raw = "There was an error processing your message (raw report id). Please resend\n";
			$this->dbManip->setSent($this->getPhoneId(), $this->currDate, $raw, $this->getReceivedId ());
			$this->dirty = TRUE;
			$this->raw = $raw;
			return;
		}
		//$this->dbManip->setStats($this->sms->getQty(), $this->getCurrDate(), $this->getItemId(), $this->receivedId, $this->getPhoneId(), $this->locationId, $this->qtyAfter, $this->modifier['mid'], $this->sms->getModifier() );
		//$quantity, $currDate, $facilityId, $patientId, $rawreportId, $phoneId, $locationId, $qtyAfter, $action
		$this->dbManip->setStats($this->sms->getQty(), $this->getCurrDate(), $this->getFacilityId(), $this->getPatientId(),  $this->receivedId, $this->getPhoneId(), $this->locationId, $this->qtyAfter, $this->sms->getAction() );
		
		$raw = "Message processed successfully. Qty: " . $this->sms->getQty();
		$this->dbManip->setSent($this->getPhoneId(), $this->currDate, $raw, $this->getReceivedId ());
		$this->dirty = TRUE;
		$this->raw = $raw;
		return;
	}

	
	function setPatient() {
		return $this->dbManip->regPatient($this->getPatientNumber(), $this->getCurrDate()) ;
	}
	
	function getFacility() {
		$this->facilityId = $this->dbManip->getFacilityId($this->sms->getFacility());
		if ($this->facilityId == -1) {
			$raw =  "Cannot find facility with code '" . $this->sms->getFacility() . "'. Message not processed\n";
			$this->dbManip->setSent($this->phoneId, $this->currDate, $raw, $this->getReceivedId ());
			$this->dirty = TRUE;
			$this->raw = $raw;
			return;
		}
	}
	
	function getPatient() {
		$this->patientId = $this->dbManip->getPatientId($this->sms->getPatient());
		if ($this->patientId == -1) {
			$raw =  "Cannot find patient with number '" . $this->sms->getPatient() . "'. Message not processed\n";
			$this->dbManip->setSent($this->phoneId, $this->currDate, $raw, $this->getReceivedId ());
			$this->dirty = TRUE;
			$this->raw = $raw;
			return;
		}
	}
	function checkPatient($pNumber, &$pId, &$consent) {
		$p = -1;
		$c = -1;
		$this->patient = $this->dbManip->checkPatient($pNumber, $p, $c);
		if ($this->patient == -1) {
			$raw =  "Cannot find patient with number '" . $this->sms->getPatient() . "'. Message not processed\n";
			$this->dbManip->setSent($this->phoneId, $this->currDate, $raw, $this->getReceivedId ());
			$this->dirty = TRUE;
			$this->raw = $raw;
			return;
		} else {
			$this->patientId = $p;
			$this->patientConsent = $c;
		}
		if ($this->patientConsent == 0) {
			$raw =  "Patient has not given consent '" . $this->sms->getPatient() . "'. Message not processed\n";
			$this->dbManip->setSent($this->phoneId, $this->currDate, $raw, $this->getReceivedId ());
			$this->dirty = TRUE;
			$this->raw = $raw;
			return;
		}
			
	}
	
	function regPatient($pNumber) { 
		if ($pNumber < MIN_PATIENT_NUM) {
			$raw =  "Patient number invalid. Message not processed'\n";
			$this->dbManip->setSent($this->phoneId, $this->currDate, $raw, $this->getReceivedId ());
			$this->dirty = TRUE;
			$this->raw = $raw;
			return;
		}
			
		$creUpdate = $this->dbManip->regPatient($pNumber, $this->currDate, $this->getLocationId ());
		$raw =  "Patient has been '" . $creUpdate . "'\n";
		$this->dbManip->setSent($this->phoneId, $this->currDate, $raw, $this->getReceivedId ());
		$this->dirty = TRUE;
		$this->raw = $raw;
		return;
	}
	
}

?>
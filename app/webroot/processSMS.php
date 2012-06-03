<?php
	require_once('dataBase.php');
	require_once('databaseManipulation.php');
	require_once('sms.php');
	require_once('smsManipulation.php');
	define('__ROOT__', dirname(dirname(__FILE__))); //this is a workaround for the require
	require_once(__ROOT__ . '/config/options.php'); //use this configuration so that we can make use of App::Configure in cake for the form

	define ("PHONE_NUMBER_LENGTH", $config['Phone']['length']);
	define ("MIN_PATIENT_NUM", 9999999); //threhold for patient number
	define ("MAX_QTY", 5000); //threhold for qty
	function init($msg, $caller){
		error_reporting(-1);
		//THIS MUST BE CHANGED FOR EACH PROJECTS' TIMEZONE
		//OTHERWISE PHP DATE AND MYSQL DATE FROM THE WEBAPP MAY BE DIFFERENT
		//THIS MAY BE A WINDOWS BUG
		date_default_timezone_set('Africa/Johannesburg');
		//date_default_timezone_set('America/New_York'); //for EDT current v08 timezone
		
		$currDate = date("Y-m-d H:i:s");
		//instead of reworking already existing methods set argv to the required values
		
		$argv = array();
		$argv[0] = "ProcessSMS.php";
		$argv[1] = $msg;
		$argv[2] = $caller;
		
		$db = new dataBase();
		
		$dbManip = new databaseManipulation($db);
		$smsManip = new smsManipulation($dbManip, $argv, $currDate);
		$sms = new sms($smsManip->getArgs());
		$smsManip->initSms($sms);
		
		//insert received record and get id
		$smsManip->getReceivedId ();
		$smsManip->checkArguments ();
		
		if ($smsManip->getDirty() === TRUE)
			return $smsManip->getRaw();
		//decide if action was given or an update of an item
		if ($sms->getAction() != NULL) {
			if ($sms->getAction()  == 'send') { //send to patient (valid patient) or facility (QTY + short name)
				if ($sms->getFacility() != '' && $sms->getPatient() == '') { //check if send and facility
					$smsManip->getFacility();
				} else if($sms->getPatient() != NULL && $sms->getFacility() == ''  && $sms->getFacility() == NULL) {
					$pNumber = $sms->getPatient();
					$pId = -1;
					$consent = -1;
					$smsManip->checkPatient($pNumber,$pId, $consent);
				}
				if ($smsManip->getDirty() === TRUE)
					return $smsManip->getRaw();
				else {
					$smsManip->processSms(); //everything is ok process the message
					if ($smsManip->getDirty() === TRUE)
						return $smsManip->getRaw();
				}
			} else if ($sms->getAction()  == 'receive') {
				if($sms->getPatient() != NULL ) {
					$pNumber = $sms->getPatient();
					$pId = -1;
					$consent = -1;
					$smsManip->checkPatient($pNumber,$pId, $consent);
				}
				if ($smsManip->getDirty() === TRUE)
					return $smsManip->getRaw();
				else {
					$smsManip->processSms(); //everything is ok process the message
					if ($smsManip->getDirty() === TRUE)
						return $smsManip->getRaw();
				}
			} else if ($sms->getAction()  == 'expire') {
					$smsManip->processSms(); //everything is ok process the message
					if ($smsManip->getDirty() === TRUE)
						return $smsManip->getRaw();
			} else if ($sms->getAction()  == 'consent') {
				
				$smsManip->regPatient($sms->getPatient()); //everything is ok process the message
				if ($smsManip->getDirty() === TRUE)
					return $smsManip->getRaw();
			}
			/* 
			//for orbis
			if ($sms->getAction()  == 'query') {
				$raw = "The current quantity for: " . $sms->getItem() . " is: " . $smsManip->getQtyAfter();
				$dbManip->setSent($smsManip->getPhoneId(), $currDate, $raw, $smsManip->getReceivedId ());
				return $raw;
			} else if ($sms->getAction()  == 'count') {
				$smsManip->findChildren($smsManip->getLocationId());
				$sum = $smsManip->getChildrenSum();
				$raw = "The current summed up quantity for: " . $sms->getItem() . " is: " . $sum[$smsManip->getItemId()]['sum'];
				$dbManip->setSent ($smsManip->getPhoneId(), $smsManip->getCurrDate(), $raw, $smsManip->getReceivedId ());
				return $raw;
			} else if ($sms->getAction()  == 'approve') {
				$smsManip->setUserId(); //check that user is associated with phone
				$smsManip->findChildren($smsManip->getLocationId()); //get all children for location
				$sum = $smsManip->getChildrenAndParentSum(); //sum children and the paret - parent is the users location
				
				//two cases an item and all
				if (strtoupper($sms->getItem() ) == "ALL") {
					$dbManip->approveAll($sum, $smsManip->getApprovalId());
					$raw = "All quantities have been approved: ";
					foreach (array_keys($sum) as $s) {
						$raw .= $dbManip->getItemCode($s) . ": " . $sum[$s]['sum'] . ". ";
					}
					$dbManip->setSent($smsManip->getPhoneId(), $smsManip->getCurrDate(), $raw, $smsManip->getReceivedId() );
					return $raw;
				} else {

					$dbManip->approveOne($smsManip->getItemId(), $sum, $smsManip->getApprovalId());
					$raw = "The following quantities have been approved: ". $sms->getItem() . " quanitity: " . $sum[$smsManip->getItemId()]['sum'];
					$dbManip->setSent ($smsManip->getPhoneId(), $smsManip->getCurrDate(), $raw, $smsManip->getReceivedId ());
					return $raw;
				}
			} */
			
		} /* else { //we have an update
			$smsManip->setModifier(); //get default and id for modifier
			$smsManip->checkArguments(); //make sure all arguemnts are set for an update
			$smsManip->processSms(); //insert sucessfull rawreport and update
		}  */
	}
?>


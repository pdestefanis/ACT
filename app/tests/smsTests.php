<?php
	require_once("..\webroot\processSMS.php");
	$activePhoneFac0 =  "+15559934234";
	$activePhoneFac1 =  "+15553993131";
	$activePhoneFac2 =  "+19194382549";
	$newPhone = "+34988441277";
	$inactivePhone = "+34988441277";
	$notAssignePhoned = "+15558873342";
	$realUnit = "1004";
	$notExistingUnit = "0400";
	$newUnit = "6500";
	$newUnit1 = "6511";
	$newUnit2 = "6515";
	$newUnit3 = "6525";
	$facility0 = "s001"; //assigned to phone0 and child of $facility1
	$facility1 = "m001"; //assigned to phone1
	$facility2 = "s002"; //assigned to phone2 not child or parent of above
	$notExistantFacility = "o001";
	$realPatient = "P000012"; 
	$realPatient1 = "P123452";
	$notExistantPatient = "P000099";
	$notExistantPatient1 = "P000199";
	$patientNoConsent = "7000012";
	$assignAction = 'a';
	$receiveAction = 'r';
	$discardAction = 'e';
	$consentAction = 'c';
	$createAction = 'cr';
	$sleep = 1;
	
	$dateInvalid0 = '2012-18-12';
	$dateInvalid1 = '012-8-12';
	$dateInvalid2 = '2012-8-32';
	$dateInvalid3 = '2012-8-0';
	$date00 = '2012-08-12';
	$date10 = '2012-08-15';
	$date20 = '12-08-20';
	$date30 = '12-8-25';
	$date40 = '2012-08-30';
	$date50 = '2012-9-02';
	$date60 = '2012-9-5';
	$date70 = '12-9-10';
	$date80 = '2012-09-15';
	$date90 = '12-09-20';
	
	

	echo "Empty message \n";
	$msg = "";
	echo "sms: " . $msg . "\t\t\t\t" . init($msg, $activePhoneFac0) . "\n"; //active phone
	echo "\n\nGarbage in message message \n";
	$msg = "JKI $facility0";
	echo "sms: " . $msg . "\t\t\t\t" . init($msg, $activePhoneFac0) . "\n"; //active phone
	echo "\n\nNew phones \n";
	echo "\n\nCreate  \n";
	$msg = "$newUnit $createAction $facility0";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	sleep($sleep);
	$msg = "$realUnit $facility0";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $newPhone) . "\n"; //new phones
	echo "\n\nInactive phones \n";
	$msg = "$realUnit $facility0";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $inactivePhone) . "\n"; //inactive phone
	echo "\n\nNot assigned phones \n";
	$msg = "$realUnit $facility0";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $notAssignePhoned) . "\n"; //not assigned  phone
	echo "\n\nWrong facility number but proper format \n";
	$msg = "$notExistantFacility";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n"; //active phone
	$msg = "$realUnit $notExistantFacility";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	$msg = "$realPatient $realUnit $notExistantFacility";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	$msg = "$realPatient $notExistantFacility";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	echo "\n\nWrong unit number but proper format \n";
	$msg = "$notExistingUnit $facility0";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	$msg = "$notExistingUnit $realPatient";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	$msg = "$facility0 $notExistingUnit $realPatient";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	echo "\n\nPatient code doesn\"t exist\n";
	$msg = "$notExistantPatient";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	$msg = "$realUnit $notExistantPatient";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	$msg = "$facility0 $realUnit $notExistantPatient";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	echo "\n\nPatient no consent \n";
	$msg = "$patientNoConsent";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	$msg = "$realUnit $patientNoConsent";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	$msg = "$facility0 $realUnit $patientNoConsent";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	echo "\n\nToo manu actions \n";
	$msg = "$createAction $realUnit $patientNoConsent $assignAction";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	echo "\n\nOnly consent action because it is treated differently \n";
	$msg = "$consentAction";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	echo "\n\nOnly non-consent action  \n";
	$msg = "$assignAction";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	
	//With actions
	echo "\n\nCreate  \n";
	$msg = "$newUnit $createAction";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	$msg = "$newUnit $createAction $facility0";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	echo "\n\nCreate unit at  $facility0\n";
	$msg = "$newUnit $createAction $facility0";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	sleep($sleep);
	//assign
	echo "\n\nAssign\n";
	$msg = "$assignAction $newUnit ";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	sleep($sleep);
	echo "\n\nAssign to $facility0\n";
	$msg = "$assignAction $newUnit $facility0";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	sleep($sleep);
	echo "\n\nAssign to patient\n";
	$msg = "$assignAction $newUnit $realPatient";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	sleep($sleep);
	echo "\n\nAssign open kit to $facility0\n";
	$msg = "$assignAction $newUnit $facility0";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	echo "\n\nCreate $newUnit1 $facility0\n";
	$msg = "$createAction $newUnit1 $facility0";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	sleep($sleep);
	echo "\n\nAssign new kit to child $facility1\n";
	$msg = "$assignAction $newUnit1 $facility1";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	sleep($sleep);
	echo "\n\nAssign new from phone that isn't child of the current one\n";
	$msg = "$assignAction $newUnit1 $facility2";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac2) . "\n";
	sleep($sleep);
	echo "\n\nAssign to patient already with kit\n";
	$msg = "$assignAction $newUnit1 $realPatient";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac2) . "\n";
	echo "\n\nAssign\n";
	$msg = "$assignAction $newUnit1 $realPatient $facility2";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac2) . "\n";
	echo "\n\nCreate $newUnit2 $facility0\n";
	$msg = "$createAction $newUnit2 $facility0";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	sleep($sleep);
	echo "\n\nKeywordless assign $newUnit2 to Facility\n";
	$msg = "$newUnit2 $facility2";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	sleep($sleep);
	echo "\n\nKeywordless receive $newUnit2  closed kit\n";
	$msg = "$newUnit2";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac2) . "\n";
	sleep($sleep);
	echo "\n\nKeywordless assign $newUnit2  to Patient\n";
	$msg = "$newUnit2 $realPatient1";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac2) . "\n";
	sleep($sleep);
	echo "\n\nKeywordless receive $newUnit2  open kit\n";
	$msg = "$newUnit2";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac2) . "\n";
	sleep($sleep);
	
	echo "\n\nCreate $newUnit3 $facility0\n";
	$msg = "$createAction $newUnit3 $facility0";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	sleep($sleep);
	echo "\n\n Receive closed $newUnit3 $facility0\n";
	$msg = "$receiveAction $newUnit3 $facility0";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	sleep($sleep);
	echo "\n\n Receive open $newUnit $facility0\n";
	$msg = "$receiveAction $newUnit $facility0";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	sleep($sleep);
	echo "\n\n Receive closed from diff facility $newUnit3\n";
	$msg = "$receiveAction $newUnit3 ";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac1) . "\n";
	sleep($sleep);
	echo "\n\n Receive open $newUnit $realPatient\n";
	$msg = "$receiveAction $newUnit $realPatient";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	sleep($sleep);
	echo "\n\n Receive open $newUnit $realPatient\n";
	$msg = "$receiveAction $newUnit $realPatient $facility0";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	sleep($sleep);
	
	echo "\n\n Discard open $newUnit \n";
	$msg = "$discardAction $newUnit";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	sleep($sleep);
	echo "\n\n Receive closed $newUnit1 \n";
	$msg = "$receiveAction $newUnit1";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	sleep($sleep);
	echo "\n\n Discard closed $newUnit1 \n";
	$msg = "$discardAction $newUnit1";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	sleep($sleep);
	echo "\n\n Discard open same $newUnit \n";
	$msg = "$discardAction $newUnit";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	sleep($sleep);
	echo "\n\n Discard open diff facility $newUnit2 \n";
	$msg = "$discardAction $newUnit2 $facility0";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac2) . "\n";
	sleep($sleep);
	echo "\n\n Create patient $notExistantPatient \n";
	$msg = "$consentAction $notExistantPatient";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac2) . "\n";
	sleep($sleep);
	echo "\n\n Update consent $patientNoConsent\n";
	$msg = " $patientNoConsent.$consentAction";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac2) . "\n";
	sleep($sleep);
	echo "\n\n Update consent params ignored $patientNoConsent\n";
	$msg = "$consentAction $facility2 $patientNoConsent ";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac2) . "\n";
	sleep($sleep); 

	echo "\n\n Different formats invalid dates \n"; 	
	$msg = "$assignAction $newUnit3 $facility2 $dateInvalid0 ";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac2) . "\n";
	sleep($sleep);
	$msg = "$assignAction $dateInvalid1 $newUnit3 $facility2 ";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac2) . "\n";
	sleep($sleep);
	$msg = "$dateInvalid2 $receiveAction  $newUnit3 $facility2 ";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac2) . "\n";
	sleep($sleep); 
	$msg = " $discardAction  $newUnit3 $dateInvalid3 $facility2 ";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac2) . "\n";
	sleep($sleep);
	
	
	echo "\n\n Normal in order back entry \n";
	++$newUnit3;
	$msg = "$createAction $newUnit3 $facility0 $date00";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	//sleep($sleep);
	$msg = "$assignAction $newUnit3 $facility1 $date10";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	//sleep($sleep);
	$msg = "$receiveAction $newUnit3 $facility1 $date20";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac1) . "\n";
	//sleep($sleep);
	++$patientNoConsent;
	$msg = "$consentAction $patientNoConsent $facility1";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac1) . "\n";
	//sleep($sleep);
	$msg = "$assignAction $newUnit3 $patientNoConsent $date30";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac1) . "\n";
	//sleep($sleep);
	$msg = "$receiveAction $newUnit3 $date40";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac1) . "\n";
	//sleep($sleep);
	$msg = "$receiveAction $newUnit3 $date50";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	//sleep($sleep);
	$msg = "$discardAction $newUnit3 $date50";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	//sleep($sleep);
	
	echo "\n\n Out of order back entry R before A \n";
	++$newUnit3;
	++$patientNoConsent;
	$msg = "$createAction $newUnit3 $facility0 $date00";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	//sleep($sleep);
	$msg = "$receiveAction $newUnit3 $facility1 $date20";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac1) . "\n";
	//sleep($sleep);
	$msg = "$assignAction $newUnit3 $facility1 $date10";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	//sleep($sleep);
	
	$msg = "$consentAction $patientNoConsent $facility1";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac1) . "\n";
	//sleep($sleep);
	$msg = "$assignAction $newUnit3 $patientNoConsent $date30";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac1) . "\n";
	//sleep($sleep);
	$msg = "$receiveAction $newUnit3 $date40";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac1) . "\n";
	//sleep($sleep);
	$msg = "$receiveAction $newUnit3 $date50";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	//sleep($sleep);
	$msg = "$discardAction $newUnit3 $date50";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	
	echo "\n\n Out of order back entry R before A diff facility \n";
	++$newUnit3;
	++$patientNoConsent;
	$msg = "$createAction $newUnit3 $facility0 $date00";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	//sleep($sleep);
	$msg = "$receiveAction $newUnit3 $facility1 $date20";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac1) . "\n";
	//sleep($sleep);
	$msg = "$assignAction $newUnit3 $facility2 $date10";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	//sleep($sleep);
	
	$msg = "$consentAction $patientNoConsent $facility1";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac1) . "\n";
	//sleep($sleep);
	$msg = "$assignAction $newUnit3 $patientNoConsent $date30";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac1) . "\n";
	//sleep($sleep);
	$msg = "$receiveAction $newUnit3 $date40";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac1) . "\n";
	//sleep($sleep);
	$msg = "$receiveAction $newUnit3 $date50";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	//sleep($sleep);
	$msg = "$discardAction $newUnit3 $date50";
	echo "sms: " . $msg . "\t\t\t" . init($msg, $activePhoneFac0) . "\n";
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
?>
<?php

define('DIR', dirname(dirname(__FILE__)));
require_once(DIR . '/app/webroot/processSMS.php');

$szEOL = "\r\n";
$logfile = DIR . '/listener/parser.log';
echo $logfile;

function stXMLtoArray($szXML)
        {
        $arrData = array();
        $arrObjData = simplexml_load_string ($szXML);
        if (is_object($arrObjData))
                $arrObjData = get_object_vars($arrObjData);
        if (is_array($arrObjData))
                foreach ($arrObjData as $index => $value)
                        {
                        if (strlen ($value))
                                $arrData[$index] = $value;
                        else
                                $arrData[$index] = "";
                        }
        return $arrData;
        }


function szGetResponse ($szMsgIn, $inMsgUID, $szCallerID)
        {
        /* Here we process the message and send a reply. Here’s a mock example */
        //$szMsgOut = "Here’s the answer (MsgUID : ".$inMsgUID.") to phone ".$szCallerID.". End of message";
		//$sms = new ProcessSMS();
		$szMsgOut = init($szMsgIn,$szCallerID);
		return ($szMsgOut);
        }

function szMakeHeader ($szData)
        {
        global $szEOL;

        $szHeader =  "HTTP/1.1 200 OK".$szEOL
                    ."Content-Type: text/xml;charset=UTF-8".$szEOL
                    ."Content-Length: ".(strlen($szData))."".$szEOL
                    .$szEOL
                    ."";
        return ($szHeader);
        }

/* Turn the received XML into an array. This is for Apache + */
$stRequest = stXMLtoArray($GLOBALS['HTTP_RAW_POST_DATA']);

// log the request sent to the SMS processor
error_log("[ ".date("Y-m-d H:i:s")." ]:  SMSparser: Sending request (procesSMS): Message: ".$stRequest['mensaje']."  MSGUID: ".$stRequest['msguid']."  Caller: ".$stRequest['callerid']."\n", 3, $logfile);

/* Get the reply for the caller. */
$szResponse = szGetResponse ($stRequest['mensaje'], $stRequest['msguid'], $stRequest['callerid']);
//$szResponse = szGetResponse ($stRequest['mensaje'], $stRequest['callerid']); //no need for msguid and assuming caller id is the phone number

// log the reply from the SMS processor
error_log("[ ".date("Y-m-d H:i:s")." ]:  SMSparser: Received reply (procesSMS): ".$szResponse."\n", 3, $logfile);

/* Build the reply. */
$szXML = "<?xml version='1.0' encoding='UTF-8'?>"
        ."<smsresponse>"
        ."<destination>".$stRequest['callerid']."</destination>"
        ."<mensaje>".$szResponse."</mensaje>"
        ."<simid>".$stRequest['simid']."</simid>"
        ."<msguid>".$stRequest['msguid']."</msguid>"
        ."<retcodigo></retcodigo>"
        ."<extradata></extradata>"
        ."</smsresponse>"
        ."";
$szHeader = szMakeHeader ($szXML);

/* Alternative way of passing the reply to other app
 */
// echo $szHeader;

echo $szXML;

// log the output somewhere
error_log("[ ".date("Y-m-d H:i:s")." ]:  SMSparser output: szHeader: ".$szHeader."\n", 3, $logfile);
error_log("[ ".date("Y-m-d H:i:s")." ]:  SMSparser output: szXML: ".$szXML."\n", 3, $logfile);
?>

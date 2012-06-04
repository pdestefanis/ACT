<?php
require_once('phpWebRequest.class.php');

if (count($argv) < 2) {
  echo "Usage: app caller message [timestamp]\n";
  exit;
}

// variables for the message
$time = date("Y-m-d H:i:s");
$caller = $argv[1];
$message = $argv[2];
$simid = "5493773475882";
$timestamp = time();

$rq = new PhpWebRequest();
$rq->InitServer("127.0.0.1","/SMSparser.php",5454);
$strSize = $rq->DataReaderFactory("<?xml version='1.0' encoding='UTF-8'?>
<smsrequest>
<datetime>".$time."</datetime>
<callerid>".$caller."</callerid>
<mensaje>".$message."</mensaje>
<simid>".$simid."</simid>
<msguid>".$timestamp."</msguid>
<extradata></extradata>
</smsrequest>");
$rq->InitRequest("POST");
 
//header fields
$headerElements = array("Host" => "127.0.0.1",
"Connection" => "Close",
"Content-type" => "multipart/mixed",
"Content-length" => $strSize
);
//load the headers
$rq->CfgHeader($headerElements);
 
//send it
if($rq->SendRequest()){
echo "Request sent. Message \"".$message."\" from ".$caller."\n";
}
else{
echo "ERROR";
}
 
?>
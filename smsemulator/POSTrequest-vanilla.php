<?php
require_once('phpWebRequest.class.php');
 
$rq = new PhpWebRequest();
$rq->InitServer("127.0.0.1","/SMSparser.php",5454);
$strSize = $rq->DataReaderFactory("the body of the request");
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
echo "Request sent !";
}
else{
echo "ERROR";
}
 
?>
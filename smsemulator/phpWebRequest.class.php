<?php
/*
PhpWebRequest objects provide methods to send or read HTTP requests
Date: June-08
Author: Guillaume Nachury
Version: 0.1
 
*/
 
class PhpWebRequest{
//VERSION
var $version = "0.1";
 
//HTTP
var $boundary;
var $method;
var $header;
var $body;
var $protocol;
var $headerElements;
 
//SERVER
var $address;
var $path;
var $port;
 
var $isOverSSL = false;
 
//FEED
var $xml;
 
/**
* InitServer()
* $a = server address
* $p = path to the ressource
* $prt = port (by default 80)
*/
function InitServer($a,$p,$prt=80){
$this->address = $a;
$this->path = $p;
$this->port = $prt;
}
 
/**
* InitRequest()
* $m = the HTTP method you wanna use
* $secured = in case we use HTTPS
* $bounds = a string to create a boundary (unused since v 0.1)
*/
function InitRequest($m, $secured = false, $bounds="PhpWebRequest"){
$this->method = $m;
$this->boundary = md5($bounds);
$this->isOverSSL = $secured;
if($this->isOverSSL === false){
$this->protocol = "HTTP/1.1";
}
else{
$this->protocol = "HTTPS";
}
}
 
/**
* DataReaderFactory()
* a factory that can read data from either a file, an URL or a String to be used as the content of the request
* $u = the path to the data ( for a file start with file://
* for an URL start with http://
* for string just enter chars)
*/
function DataReaderFactory($u){
//if we wanna read from a file
if(strpos($u,'file://' ) === true){
 
}
//or from a remote web page
elseif(strpos($u,'http://' ) === true){
 
}
//a string have been passed
else{
$this->xml=$u;
return strlen($this->xml);
}
}
 
/**
* CfgHeader()
* Create the HTTP header fields
* $attributesArray = an array of headers that MUST have keys
* keys = field name
* value = field calue
*/
function CfgHeader($attributesArray){
while(list($k,$v) = each($attributesArray)){
$this->headerElements .= $k.": ".$v."\n";
}
}
 
/**
* CraftRequest()
* Create a HTTP request
*/
function CraftRequest(){
$this->header = $this->method." ".$this->path." ".$this->protocol. "\n".$this->headerElements."\n";
$this->body = $this->xml."\n";
}
 
/**
* SendRequest()
* Send the request, return true if successfully sent
*/
function SendRequest(){
$this->CraftRequest();
$request = $this->header.$this->body;
 
$fs = fsockopen($this->address,$this->port);
if(!$fs){
return false;
}
else{
fputs($fs,$request);
fflush($fs);
fclose($fs);
return true;
}
 
}
 
/**
* readHTTPRequest()
* return the HTTP request BODY
*/
function readHTTPRequest(){
return @file_get_contents('php://input');
}
 
}
?>
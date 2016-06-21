<?php
$postVars = $_REQUEST;

ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
error_reporting(E_ALL);

$db_host = "OR-TSSQL01.response.corp";
$db_user = "dwWEIci16rGByGw8";
$db_pass= "R22tCY2kkK8UguFU";
//$db_host = "AV-RMGDEVDB01.blooms.corp";
//$db_user = "8v8UzAEARNFIVNo2";
//$db_pass = "NzdYc5oh4XeDXecY";
$database = "Touchstar";

$dbhandle = mssql_connect($db_host, $db_user, $db_pass) or die("could not connect to sql server on $serverName");
$selected = mssql_select_db($database, $dbhandle) or die("could not connect to database $database");

//$stmt = mssql_init('usp_BlooAdsLeadImport', $dbhandle);

$fname = $_REQUEST['fname'];
$lname = $_REQUEST['lname'];
$addr1 = $_REQUEST['addr1'];
$addr2 = $_REQUEST['addr2'];
$homeCity = $_REQUEST['homeCity'];
$homeState = $_REQUEST['homeState'];
$homePostcode = $_REQUEST['homePostalCode'];
$homeCountry = $_REQUEST['homeCountry'];
$homePhone = $_REQUEST['homePhone'];
$email = $_REQUEST['email'];
$maskedCc = $_REQUEST['maskedCc'];
$offerName = $_REQUEST['offerName'];
$leadId = $_REQUEST['leadId'];
$projectId = $_REQUEST['projectId'];
$sourceTable = $_REQUEST['sourceTable'];
$sourceId = $_REQUEST['sourceId'];


$offerName = 'MiddleMan Test';
$projectId = 297;
$sourceTable = '';


mssql_query("exec usp_BlooAdsLeadImport
@fname = '" . addslashes($fname) . "',
@lname = '" . addslashes($lname) . "',
@homeAdd1 = '" . addslashes($addr1) . "',
@homeAdd2 = '" . addslashes($addr2) . "',
@homeCity = '" . addslashes($homeCity) . "',
@homeState = '" . addslashes($homeState) . "',
@homePostCode = '" . addslashes($homePostcode) . "',
@homeCountry = '" . addslashes($homeCountry) . "',
@homePhone = '" . addslashes($homePhone) . "',
@Email = '" . addslashes($email) . "',
@Maskedcc = '" . addslashes($maskedCc) . "',
@offerName = '" . addslashes($offerName) . "',
@leadId = '" . addslashes($leadId) . "',
@projectId = '" . addslashes($projectId) . "',
@sourceTable = '" . addslashes($sourceTable) . "',
@sourceId = '" . addslashes($sourceId) . "'
");




?>

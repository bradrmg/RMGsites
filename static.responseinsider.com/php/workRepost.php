<?php

$db_host = "OR-TSSQL01.response.corp";
$db_user = "dwWEIci16rGByGw8";
$db_pass= "R22tCY2kkK8UguFU";
$database = "DBA";

$dbhandle = mssql_connect($db_host, $db_user, $db_pass) or die("could not connect to sql server on $serverName");
$selected = mssql_select_db($database, $dbhandle) or die("could not connect to database $database");


$fields = $_REQUEST['pString'];
//$activityid = $_REQUEST['ActivityId'];
//$tourcode = $_REQUEST['TourCode'];
//$calldate = $_REQUEST['CallDate'];
$rmgID = $_REQUEST['RMGregID'];


$uri = 'https://reg.evtech.com/api/register_existing.json';

$postString = 'vendor_token=RMG&api_token=7aaeec40ffe4c14d98fe9d0d81fc9aea&'.$fields;
//$postString = 'vendor_token=SDPORTAL&api_token=9f152293e0855a73588c7f76036b036f7ec014b0&[registration_id]='.$activityid.'&[tour_code]='.$tourcode.'';

//echo $uri.$postString;


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $uri);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);

$result = curl_exec($ch);

//echo $result;

$array = json_decode($result);

//echo '<pre>', print_r($array), '</pre>';

//Setting variable from results returned
$error = $array->error;
$id = $array->id;
$reg_id = $array->registration_id;

// Setting the post type for API log. This post is to handle registrations for workshop events and other
// existing registration types
$postype = 'RegExisting';
$responseID = $rmgID;

$sql =<<<EOD
INSERT INTO PostResult (RegID, error, id, registration_id, PostType)
VALUES ('$responseID','$error', '$id', '$reg_id', '$postype')
EOD;
mssql_query($sql);


echo "completed - post result is ".$result."!";

    
	  
	  //$dbhandle = mssql_connect($db_host, $db_user, $db_pass) or die("could not connect to sql server on $serverName");
//$selected = mssql_select_db($database, $dbhandle) or die("could not connect to database $database");
?>

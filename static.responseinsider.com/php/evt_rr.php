<?php

$db_host = "OR-TSSQL01.response.corp";
$db_user = "dwWEIci16rGByGw8";
$db_pass= "R22tCY2kkK8UguFU";
$database = "DBA";

$dbhandle = mssql_connect($db_host, $db_user, $db_pass) or die("could not connect to sql server on $serverName");
$selected = mssql_select_db($database, $dbhandle) or die("could not connect to database $database");

$activityid = $_REQUEST['ActivityId'];
$tourcode = $_REQUEST['TourCode'];
$rmgid = $_REQUEST['rmgID'];


$uri = 'https://reg.evtech.com/api/reschedule.json';
$postString = 'vendor_token=RMG&api_token=7aaeec40ffe4c14d98fe9d0d81fc9aea&reschedule[ActivityId]='.$activityid.'&reschedule[TourCode]='.$tourcode.'';

//echo $postString;


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $uri);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);

$result = curl_exec($ch);

$array = json_decode($result);

//echo '<pre>', print_r($array), '</pre>';

//Setting variable from results returned
$error = $array->error;
$id = $array->id;
$reg_id = $array->new_ActivityId;

// Setting the post type for API log. This is a ReReg post so it is set accordingly.
$postype = 'ReReg';

// Insert Statement to send results to the DBA.dbo.PostResult table.
$sql =<<<EOD
INSERT INTO PostResult (RegID, error, id, registration_id, PostType)
VALUES ('$rmgid','$error', '$id', '$reg_id', '$postype')
EOD;
mssql_query($sql);

//print_r($sql);

echo "completed - post result is ".$result."!";


?>

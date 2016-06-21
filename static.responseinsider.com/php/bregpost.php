<?php

$db_host = "OR-TSSQL01.response.corp";
$db_user = "dwWEIci16rGByGw8";
$db_pass= "R22tCY2kkK8UguFU";
$database = "DBA";

$dbhandle = mssql_connect($db_host, $db_user, $db_pass) or die("could not connect to sql server on $serverName");
$selected = mssql_select_db($database, $dbhandle) or die("could not connect to database $database");




$fullstring = $_REQUEST['fstring'];

$uri = 'pl-rmgstaticws01.response.corp/php/regpost.php';

$postString = 'vendor_token=RMG&api_token=7aaeec40ffe4c14d98fe9d0d81fc9aea&'.$fullstring.'';

$uri = preg_replace('/\r+/', '', $uri);

//echo $uri;


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $uri);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fullstring);
$presult = curl_exec($ch);

echo $presult;
/*
$sql =<<<EOD
INSERT INTO PostResult (RegID,PostResult)
VALUES ('$rmgregid','$result')
EOD;
mssql_query($sql);

echo "completed - post result is ".$presult."!";
*/

?>

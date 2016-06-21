<?php

$db_host = "OR-TSSQL01.response.corp";
$db_user = "dwWEIci16rGByGw8";
$db_pass= "R22tCY2kkK8UguFU";
$database = "DBA";

$dbhandle = mssql_connect($db_host, $db_user, $db_pass) or die("could not connect to sql server on $serverName");
$selected = mssql_select_db($database, $dbhandle) or die("could not connect to database $database");

$postVars = $_REQUEST['pString'];
//$postVars = $_REQUEST;
$regID = $_REQUEST['customer_id'];

$testString = $postVars;
//var_dump($postVars);

//echo $pVars;
//echo urlencode($pVars);
//$testString = http_build_query($pVars);
//$testString = urlencode($pVars);
//echo $testString;

// Staging version http://qa.coachalba.com/partner/user
$surl = 'http://qa.coachalba.com/partner/user?';
// Production version https://coachalba.com/partner/user
$purl = 'https://coachalba.com/partner/user?';

//TEST PARAMS
//$token = '4394ae49c25c1e841616185c55bcdc7b';
//$secret = 'd5bce2875429525dadde41baa668b1b1';

//PRODUCTION PARAMS
$token = '0cf54e08956f4e7f937ec83cff01e1b8dc06832a'; 
$secret= '5b0253e00feaedb033f825dd1c225549f5d2f4b5';


$preString = $purl.$testString.'&token='.$token.'&secret='.$secret;
//echo $preString;
//echo "<br>";
$checksum = sha1($preString);
$csum = '1a26adfcc1bbf6f5102f524e4a1264307cf45dab';
//echo $checksum;
//echo "<br/>";

$finalString = $testString.'&token='.$token.'&csum='.$checksum.'';
//echo $purl.$finalString;


$url = 'pl-rmgstaticws01.response.corp/php/mobilecoach/mcpost.php?';
//$url = urlencode($rawurl);
//echo $url;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postVars);

$result = curl_exec($ch);

$array = json_decode($result);

//echo '<pre>', print_r($array), '</pre>';

//Setting variable from results returned


//$postype = 'NewReg';
/*
$sql =<<<EOD
INSERT INTO MobileCoach (RegID, Result, String)
VALUES ('$regID','$result','$preString')
EOD;
mssql_query($sql);

*/
echo $result;
?>
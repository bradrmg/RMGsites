<?php
// Fresh Address script, corrects and validates email addresses

//Database connection info
$db_host = "OR-TSSQL01.response.corp";
$db_user = "dwWEIci16rGByGw8";
$db_pass= "R22tCY2kkK8UguFU";
$database = "DBA";

$dbhandle = mssql_connect($db_host, $db_user, $db_pass) or die("could not connect to sql server on $serverName");
$selected = mssql_select_db($database, $dbhandle) or die("could not connect to database $database");


//Varibales to be recieved from incoming post
$email	=	$_REQUEST['email'];


//REQUIRED Variables for Fresh Address
//company = 15277
//Contract_old = 5010
//Contract New = 6891

$url = 'https://rt.freshaddress.biz/v4?service=REACT&company=15277&contract=5010&format=json&RTC=true&RTC_TIMEOUT=1200&email='.$email;



$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS);

$result = curl_exec($ch);



//Decoding response and setting variable to be written to database
$array = json_decode($result); // Second param turns it into a full array, not an array of objects

$femail = $array->FA_EMAIL;
$fvalid = $array->FA_VALID;
$facomment = $array->FA_COMMENT;
$fsemail = $array-> FA_SEMAIL;
$ferror = $array->FA_ERROR;
//echo '<pre>', print_r($array), '</pre>';


if ($fsemail <> '')
	{
		echo $fsemail;
	}
else
	{
		echo $fvalid;
	}


//Insert results from Fresh Addres post to DB tabe DBA.dbo.FreshAddress
$sql =<<<EOD
INSERT INTO FreshAddress (FA_EMAIL, FA_VALID, FA_COMMENT, FA_SEMAIL, FA_ERROR)
VALUES ('$femail','$fvalid', '$facomment', '$fsemail', '$ferror')
EOD;
mssql_query($sql);

//echo "Fresh Address process complete with result ".$result."!";


?>
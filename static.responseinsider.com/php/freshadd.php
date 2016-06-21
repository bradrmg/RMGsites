<?php
// THIS IS VERSION 7 API
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
//Company = 15277
//Contract = 5010


$url = 'https://rt.freshaddress.biz/v7?service=react&company=15277&contract=5010&format=json&RTC=true&email='.$email;



$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$result = curl_exec($ch);

//echo "<pre>";
//print_r($result);
//echo "</pre>";

//Decoding response and setting variable to be written to database
$array = json_decode($result); // Second param turns it into a full array, not an array of objects

//Setting variables from returned response array
$pemail = $array->EMAIL; //posted email
$finding = $array->FINDING;
$fvalid = $array->FA_VALID;
$facomment = $array->COMMENT;
$fsemail = $array->SUGG_EMAIL; //suggested email from post
$ferror = $array->ERROR;
$fa_id = $array->UUID;

//Variable for final output of clean email
$cleanedEmail = '';


//From results return correct safe to send email

switch ($finding)
{
	case 'V':
		$cleanedEmail = $pemail;
		break;
	case 'E':
		$cleanedEmail = $fsemail;
		break;
	case 'W':
		$cleanedEmail = $fsemail;
		break;	
}

		
echo $cleanedEmail;




//Insert results from Fresh Addres post to DB tabe DBA.dbo.FreshAddress
$sql =<<<EOD
INSERT INTO FreshAddress (FA_EMAIL, FA_VALID, FA_COMMENT, FA_SEMAIL, FA_ERROR)
VALUES ('$pemail','$finding', '$facomment', '$cleanedEmail', '$ferror')
EOD;
mssql_query($sql);



?>
<?php

//echo "needRepost";


$db_host = "OR-TSSQL01.response.corp";
$db_user = "dwWEIci16rGByGw8";
$db_pass= "R22tCY2kkK8UguFU";
$database = "DBA";

$dbhandle = mssql_connect($db_host, $db_user, $db_pass) or die("could not connect to sql server on $serverName");
$selected = mssql_select_db($database, $dbhandle) or die("could not connect to database $database");




$tourcode = $_REQUEST['tourcode'];
$fn	=	$_REQUEST['firstname'];
$ln	=	$_REQUEST['lastname'];
$add	=	$_REQUEST['address'];
$add2	=	$_REQUEST['address2'];
$city	=	$_REQUEST['city'];
$st	=	$_REQUEST['state'];
$zip	=	$_REQUEST['zipcode'];
$phone	=	$_REQUEST['phone'];
$cell	=	$_REQUEST['cellphone'];
$dnis	=	$_REQUEST['numberdialed'];
$date	=	$_REQUEST['dateofcall'];
$hotel	=	$_REQUEST['hotelname'];
$stime	=	$_REQUEST['showtime'];
$speaker	=	$_REQUEST['speaker'];
$meal	=	$_REQUEST['meal'];
$email	=	$_REQUEST['email'];
$mgid	=	$_REQUEST['marketgroupnumber'];
$semnum	=	$_REQUEST['seminarnumber'];
$guest	=	$_REQUEST['guest'];
$text	=	$_REQUEST['textreminder'];
$leadtype	=	$_REQUEST['leadtype'];
$ticket	=	$_REQUEST['ticketnum'];
$rsvp	=	$_REQUEST['rsvpcode'];
$agentid	=	$_REQUEST['agentid'];
$guestphone	=	$_REQUEST['guestphone'];
$pub	=	$_REQUEST['publisher'];
$hstatus	=	$_REQUEST['homestatus'];
$invest	=	$_REQUEST['investmentprop'];
$activityid	=	$_REQUEST['activityid'];
$control	=	$_REQUEST['control'];
$test	=	$_REQUEST['test'];
$rmgregid	=	$_REQUEST['rmgregid'];
$script	=	$_REQUEST['scriptname'];
$rmgtype	=	$_REQUEST['rmgtype'];
$projectid	=	$_REQUEST['rmgprojectid'];
$etemplate	=	$_REQUEST['emailtemplate'];

//Address Cleanup
$add = preg_replace('/(?:#[\w-]+\s*)+$/', '', $add);
$add2 = preg_replace('/(?:#[\w-]+\s*)+$/', '', $add2);

//JSON Posting info, create string and url to post to
$uri = 'http://10.40.32.200/api/registration.json';
$postString = 'vendor_token=RMG&api_token=7aaeec40ffe4c14d98fe9d0d81fc9aea&TourCode='.$tourcode.'&Firstname='.$fn.'&Lastname='.$ln.'&Address='.$add.'&Address2='.$add2.'&City='.$city.'&State='.$st.'&Zipcode='.$zip.'&Phone='.$phone.'&Cell Phone='.$cell.'&Number Dialed='.$dnis.'&Date of Call='.$date.'&Hotel Name='.$hotel.'&Show Time='.$stime.'&Speaker='.$speaker.'&Meal='.$meal.'&Email='.$email.'&Market Group Number='.$mgid.'&Seminar Number='.$semnum.'&Guest='.$guest.'&TextReminder='.$text.'&LeadType='.$leadtype.'&TicketNum='.$ticket.'&RSVP Code='.$rsvp.'&AgentID='.$agentid.'&GuestPhone='.$guestphone.'&Publisher='.$pub.'&HomeStatus='.$hstatus.'&InvestmentProp='.$invest.'&ActivityID='.$activityid.'&Control='.$control.'&Test='.$test.'&RMGregID='.$rmgregid.'&ScriptName='.$script.'&RMGType='.$rmgtype.'&RMGProjectID='.$projectid.'&EmailTemplate='.$etemplate.'';

$postString = preg_replace('/\r+/', '', $postString);

//VALIDATION OF DATA BEFORE POST
// TEST Phone Number Check
if ($phone == '8017178010' || $phone == '8017173131') {
	die('Test PhoneNum: Cannot Post');
}


echo $uri.$postString;

/*

//checking for all blank data and then will kill the post if needed.
if($fn == $ln) {
	die('Empty Data cannot post');
}
else {

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $uri);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);

	$result = curl_exec($ch);
	$array = json_decode($result);

	echo '<pre>', print_r($array), '</pre>';

	//Setting variable from results returned
	$error = $array->error;
	$id = $array->id;
	$reg_id = $array->registration_id;

	//Inserting results into local DB Table
	$sql =<<<EOD
INSERT INTO PostResult (RegID, error, id, registration_id)
VALUES ('$rmgregid','$error', '$id', '$reg_id')
EOD;
	mssql_query($sql);

}

echo "TEST completed - post result is ".$result."!";

*/

?>


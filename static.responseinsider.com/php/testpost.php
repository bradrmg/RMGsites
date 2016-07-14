<?php

//Database connection info
$db_host = "OR-TSSQL01.response.corp";
$db_user = "dwWEIci16rGByGw8";
$db_pass= "R22tCY2kkK8UguFU";
$database = "DBA";

$dbhandle = mssql_connect($db_host, $db_user, $db_pass) or die("could not connect to sql server on $db_host");
$selected = mssql_select_db($database, $dbhandle) or die("could not connect to database $database");

//Setting all post variables to postvars.
$postvars = ($_REQUEST);


//Setting specific variable from post for validation and insert into database table
$phone = $postvars['Phone'];
$fn = $postvars['Firstname'];
$ln = $postvars['Lastname'];
$rmgregid = $postvars['RMGregID'];

//Credentials to access Reg App
$creds = 'vendor_token=RMG&api_token=7aaeec40ffe4c14d98fe9d0d81fc9aea';

$spacearray = array(
	"Market Group Number" => "This is a test"
);

//JSON Posting info, create string and url to post to.
//TEST-STAGING URL
//$url = 'https://staging-reg.evtech.com/api/registration.json';
//PRODUCTION URL
$url = 'https://reg.evtech.com/api/registration.json';

//Building and cleaning variable for post.
$finalvars = http_build_query($postvars);
$jpostvars = json_encode($postvars);
//echo $finalvars;
echo "<br/>";
$newkeys = (array_keys($finalvars));
$spacevars = http_build_query($spacearray);
print_r(array_keys($postvars)); //$jpostvars;
//Strip underscores added by php to replace with white space
//$finalvars = str_replace("_"," ",$finalvars);
//Add back the under score to the tag fields.
//$finalvars = str_replace("tag ","tag_",$finalvars);

//Adding credentials to the vars so they can be passed in post fields.
$pfields = $creds.'&'.$finalvars;


//VALIDATION OF DATA BEFORE POST
// TEST Phone Number Check
if ($phone == '8017178010' || $phone == '8017173131') {
	die('Test PhoneNum: Cannot Post');
}
/*
//Checking for all blank data and then will kill the post if needed.
if($fn == $ln) {
	die('Empty Data cannot post');
}
else {

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $pfields);

	$result = curl_exec($ch);
	$array = json_decode($result);


	//Setting variable from results returned
	$error = $array->error;
	$id = $array->id;
	$reg_id = $array->registration_id;
	
	//Post type is New Reg, others are Reg Existing and WSOB (Workshop OB)
	$postype = 'NewReg';


//Insert Statement to capture CRM ID and post information
	$sql =<<<EOD
INSERT INTO PostResult (RegID, error, id, registration_id, PostType, PostVars)
VALUES ('$rmgregid','$error', '$id', '$reg_id', '$postype', '$pfields')
EOD;
	mssql_query($sql);
}

echo "completed - post result is ".$result."!";
*/


//HELPER CODE IF EVER NEEDED

//Test to print all variables if needed.
//echo "<pre>";
//print_r($postvars);
//echo "</pre>";


//CURL INFO post back
//echo '<pre>', print_r($array), '</pre>
//$info = curl_getinfo($ch);
//echo '<pre>', print_r($info), '</pre>';


//Address Cleanup to remove unwanted characters
//$add = preg_replace('/(?:#[\w-]+\s*)+$/', '', $add);
//$add2 = preg_replace('/(?:#[\w-]+\s*)+$/', '', $add2);
//$postString = preg_replace('/\r+/', '', $postString);

//END HELPER CODE

?>






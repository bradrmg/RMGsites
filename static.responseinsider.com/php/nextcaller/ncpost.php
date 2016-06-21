<?php

$db_host = "OR-TSSQL01.response.corp";
$db_user = "dwWEIci16rGByGw8";
$db_pass= "R22tCY2kkK8UguFU";
$database = "DBA";

$dbhandle = mssql_connect($db_host, $db_user, $db_pass) or die("could not connect to sql server on $serverName");
$selected = mssql_select_db($database, $dbhandle) or die("could not connect to database $database");


$api_user = "C9EE19C9356B3CB4B2E9";
$api_pass = "e4db836fa872765b046cd7f0d0e6a8e7f621010c";
$login = "C9EE19C9356B3CB4B2E9:e4db836fa872765b046cd7f0d0e6a8e7f621010c";

$phone = $_REQUEST['phonenum'];
$dialid = $_REQUEST['dialid'];


//Full URL with Keys included
$postURL = "https://api.nextcaller.com/v2/records/?phone={$phone}&format=json";



// Initialize cURL
$ch = curl_init();

// Configure the cURL command
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_USERPWD, "$api_user:$api_pass");
curl_setopt($ch, CURLOPT_URL, $postURL);
//curl_setopt($ch, CURLOPT_POSTFIELDS, $json_input);
//curl_setopt($ch, CURLOPT_POST, true);
//curl_setopt($ch, CURLOPT_HEADER, 0);
//curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
//curl_setopt($ch, CURLOPT_HTTPHEADER, array('x-standardize-only: true'));    // Enable this line if you want to only standardize addresses that are "good enough"
//curl_setopt($ch, CURLOPT_VERBOSE, 0);


// Output comes back as a JSON string.
$json_output = curl_exec($ch);

$result = json_decode($json_output, true);

//print "<pre>";
//print_r($result);
//var_dump($json_output);
//print "</pre>";

// Show results
//echo "<pre>";
//print_r($result);
//echo "</pre>";


//Setting varibale from result
	
	$nextcallerID = $result[records][0][id];
	$first_name = $result[records][0][first_name];
	$middle_name = $result[records][0][middle_name];
	$last_name = $result[records][0][last_name];
	$full_name = $result[records][0][name];
	$language = $result[records][0][language];
	$phonenumber = $phone;
	$carrier = $result[records][0][carrier];
	$address1 = $result[records][0][address][0][line1];
	$address2 = $result[records][0][address][0][line2];
	$city = $result[records][0][address][0][city];
	$state = $result[records][0][address][0][state];
	$country = $result[records][0][address][0][country];
	$zipcode = $result[records][0][address][0][zip_code];
	$extended_zip = $result[records][0][address][0][extended_zip];
	$email = $result[records][0][email];
	//$linked_email1 = $result[records][0]->; 
	//$linked_email2 = $result[records][0]->;
	//$linked_email3 = $result[records][0]->;
	//$linked_email4 = $result[records][0]->;
	$age = $result[records][0][age];
	$dob = $resultlt[records][0][dob];
	$education = $result[records][0][education];
	$gender = $result[records][0][gender];
	$high_net_worth = $result[records][0][high_net_worth];
	$home_owner_status = $result[records][0][home_owner_status];
	$household_income = $result[records][0][household_income];
	$length_of_residence = $result[records][0][length_of_residence];
	$line_type = $result[records][0][line_type];
	$marital_status = $result[records][0][marital_status];
	$market_value = $result[records][0][market_value];
	$occupation = $result[records][0][occupation];
	$presence_of_children = $result[records][0][presence_of_children];
	$department = $result[records][0][department];
	$resource_uri = $result[records][0][resource_uri];

	
//SQL INSERT Statement into the DBA.Nextcaller table
		$sql =<<<EOD
		INSERT INTO NextCaller 
				(
				DialID,
				nextcallerID,
				first_name,
				middle_name,
				last_name,
				full_name,
				language,
				phonenumber,
				carrier,
				address1,
				address2,
				city,
				state,
				country,
				zipcode,
				extended_zip,
				email,
				age,
				DOB,
				education,
				gender,
				high_net_worth,
				home_owner_status,
				household_income,
				length_of_residence,
				line_type,
				marital_status,
				market_value,
				occupation,
				presence_of_children,
				department,
				resource_uri
				)
		VALUES (
				'$dialid',
				'$nextcallerID',
				'$first_name',
				'$middle_name',
				'$last_name',
				'$full_name',
				'$language',
				'$phonenumber',
				'$carrier',
				'$address1',
				'$address2',
				'$city',
				'$state',
				'$country',
				'$zipcode',
				'$extended_zip',
				'$email',
				'$age',
				'$dob',
				'$education',
				'$gender',
				'$high_net_worth',
				'$home_owner_status',
				'$household_income',
				'$length_of_residence',
				'$line_type',
				'$marital_status',
				'$market_value',
				'$occupation',
				'$presence_of_children',
				'$department',
				'$resource_uri'
				
				)
EOD;
		mssql_query($sql);
		
	if($nextcallerID <> '') {
		echo 'Pass';
	}
	else {
		echo 'Fail';
	}
	


?>

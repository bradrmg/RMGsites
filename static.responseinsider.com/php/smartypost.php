<?php

$db_host = "OR-TSSQL01.response.corp";
$db_user = "dwWEIci16rGByGw8";
$db_pass= "R22tCY2kkK8UguFU";
$database = "DBA";

$dbhandle = mssql_connect($db_host, $db_user, $db_pass) or die("could not connect to sql server on $serverName");
$selected = mssql_select_db($database, $dbhandle) or die("could not connect to database $database");



// Your authentication ID/token (obtained in your SmartyStreets account)
$authId = urlencode("d657b971-edd5-43e1-9fdc-fd07d36b42b2");
$authToken = urlencode("5i0w3WOxHiQhxexJ0EepvzTNEnVBdPO28eDwfQb2okKKwHZiqF/4sQQBq9VOAeiV0lPS5Cps9kBE8JVUp483lA==");

$dialid = $_REQUEST['dialid'];
$add1 = $_REQUEST['add1'];
$add2 = $_REQUEST['add2'];
$city = $_REQUEST['city'];
$state = $_REQUEST['state'];
$zip = $_REQUEST['zip'];
$fname = $_REQUEST['fname'];

//Full URL with Keys included
$postURL = "https://api.smartystreets.com/street-address/?auth-id={$authId}&auth-token={$authToken}";

// input received from the webpage's POST request
$json_input = "[
    {
    	\"input_id\": \"$dialid\",
        \"street\": \"$add1\",
        \"city\": \"$city\",
        \"state\": \"$state\",
        \"zipcode\": \"$zip\",
        \"candidates\": 1
    }
]";


//print_r($json_input);

// Initialize cURL
$ch = curl_init();

// Configure the cURL command
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HEADER, 0);
//curl_setopt($ch, CURLOPT_HTTPHEADER, array('x-standardize-only: true'));    // Enable this line if you want to only standardize addresses that are "good enough"
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_URL, $postURL);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json_input);

// Output comes back as a JSON string.
$json_output = curl_exec($ch);

$result = json_decode($json_output);



// Show results
echo "<pre>";
print_r($result);
echo "</pre>";

//Setting varibale from result
	$recordtype = $result[0]->metadata->record_type;
	$homeadd1 = $result[0]->delivery_line_1;
	$homeadd2 = $result[0]->components->secondary_number;
	$homecity = $result[0]->components->city_name;
	$homestate = $result[0]->components->state_abbreviation;
	$homepostcode = $result[0]->components->zipcode;
	$inputid = $result[0]->input_id;
	
	if ($recordtype == 'S' ) {
		echo 'Pass';
		
		$sql =<<<EOD
		INSERT INTO SmartyStreets (record_type, delivery_line_1, secondary_number, city_name, state_abbreviation, zipcode, DialID)
		VALUES ('$recordtype','$homeadd1', '$homeadd2', '$homecity', '$homestate', '$homepostcode', '$inputid')
EOD;
		mssql_query($sql);
	}
	else {
		echo 'Fail';
	}
	
	//echo $recordtype.'--->'.$homeadd1.' '.$homeadd2.' '.$homecity.' '.$homestate.' '.$homepostcode;





?>

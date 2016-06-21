<?php 

//Address Fields from post
$StreetAddress = $_REQUEST['add1'];
$add2 = $_REQUEST['add2'];
$City = $_REQUEST['city'];
$State = $_REQUEST['state'];
$PostalCode = $_REQUEST['zip'];
$CountryCode = $_REQUEST['country'];


//API Key
$apiKey = 'av-618d30865309d04bf2fb0eac2c953d25';

//print_r($State);



// build API request
$APIUrl = 'http://api.address-validator.net/api/verify';

$Params = array('StreetAddress' => $StreetAddress,
		'City' => $City,
		'PostalCode' => $PostalCode,
		'State' => $State,
		'CountryCode' => $CountryCode,
		'Locale' => $Locale,
		'APIKey' => $apiKey);
$Request = @http_build_query($Params);
$ctxData = array(
		'method' => "POST",
		'header' => "Connection: close\r\n".
		"Content-Length: ".strlen($Request)."\r\n",
		'content'=> $Request);
$ctx = @stream_context_create(array('http' => $ctxData));

// send API request
$result = json_decode(@file_get_contents(
		$APIUrl, false, $ctx));

foreach ($_POST as $key => $entry)
{
	print $key . ": " . $entry . "<br>";
}

echo "<pre>";
print_r($result);
echo "</pre>";


/*
// check API result
if ($result && $result->{'status'} == 'VALID') {
	$formattedaddress = $result->{'formattedaddress'};
} else {
	echo $result->{'info'};
}
*/


?>
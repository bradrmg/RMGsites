<?php

$db_host = "OR-TSSQL01.response.corp";
$db_user = "dwWEIci16rGByGw8";
$db_pass= "R22tCY2kkK8UguFU";
$database = "DBA";

$dbhandle = mssql_connect($db_host, $db_user, $db_pass) or die("could not connect to sql server on $serverName");
$selected = mssql_select_db($database, $dbhandle) or die("could not connect to database $database");

//GET PHONE FROM POST
$phone = "+18018367839";

//?Type=carrier \
-u 'AC3094732a3c49700934481addd5ce1659:{AuthToken}'


// CREDS
$accountsid = "ACffe9e05faedce8a958779973a1a3b0e3";
$authtoken = "56c180813c1aaf94a6dfd4ee5b8d8ad6";


$url = "https://lookups.twilio.com/v1/PhoneNumbers/".$phone."?Type=carrier \ -u";

//$post_fields = $accountsid.":".$authtoken;

 $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST,true);
    //curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_exec ($ch);
    curl_close ($ch);
	
    $result = curl_exec($ch);

    echo "<pre>";
    print_r($result);
    echo "</pre>";


?>

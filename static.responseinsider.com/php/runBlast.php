<?php

$db_host = "OR-TSSQL01.response.corp";
$db_user = "dwWEIci16rGByGw8";
$db_pass= "R22tCY2kkK8UguFU";
$database = "LEADS";

$dbhandle = mssql_connect($db_host, $db_user, $db_pass) or die("could not connect to sql server on $serverName");
$selected = mssql_select_db($database, $dbhandle) or die("could not connect to database $database");

	$sql =<<<EOD
	SELECT *
	FROM PHX2
		

EOD;

	
$result = mssql_query($sql);
//$rows = mssql_fetch_assoc($leads);

$tempArray = array();
for ($i = 0; $i < mssql_num_rows($result); ++$i)
{
         $roe = mssql_fetch_assoc($result);
	array_push($tempArray, $roe);
}

//$start = 'Starting batch......';
//echo "<pre>";
//print_r($start);
//print_r($temp);
//echo "</pre>";

$uri = 'pl-rmgstaticws01.response.corp/php/gonudge/sendTxtBlast.php?';



foreach($tempArray as $key => $val)
{
	//$vars =  http_build_query($val);
	
	
	$url = $uri.$val['sendString'];
	$result = post_content($url, count($val));
	
	//echo "<pre>";
	//echo $url.$val['sendString'];
	//echo "</pre>";
}

/*

foreach($tempArray as $key => $val)
{
	$vars =  http_build_query($val);
	$result = mypost($url, count($val), $vars);
}

function mypost($url, $nfields, $stuff)
{
	echo "<pre>";
	return print_r();
	echo "</pre>";
}
*/


function post_content($url,$nfields)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST,true);
	//curl_setopt($ch,CURLOPT_POSTFIELDS, $post_fields);
	//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
	//curl_setopt ($ch, CURLOPT_HTTPHEADER, array("Content-type: multipart/form-data"));
	//curl_setopt($ch, CURLOPT_HEADER, true);
	//curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; MSIE 7.0; Windows NT 6.0; en-US)');
	curl_exec ($ch);
	curl_close ($ch);
	echo "<pre>";
	return $result;
	echo "</pre>";
}


?>
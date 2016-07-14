<?php

$db_host = "OR-TSSQL01.response.corp";
$db_user = "dwWEIci16rGByGw8";
$db_pass= "R22tCY2kkK8UguFU";
$database = "DBA";

$dbhandle = mssql_connect($db_host, $db_user, $db_pass) or die("could not connect to sql server on $serverName");
$selected = mssql_select_db($database, $dbhandle) or die("could not connect to database $database");



	$sql =<<<EOD
		SELECT 
		'registration_id=' + ExternalID
		+ '&TourCode=' + TourCode
		+ '&RMGregID=' + CONVERT(varchar(15),ID)
		+ '&Date of Call=' + CONVERT(varchar(30),[Registerdate], 121)
		+ '&RMGType=' + RMGType
		+ '&LeadType=' + LeadType
		+ '&AgentID=' + AffiliateID
		AS 'pString'
		FROM Registration r
		WHERE 1=1
		AND ExternalID <> ''
		AND ProjectID IN  (949, 935, 980, 982, 997, 981)
		AND CAST(Registerdate AS DATE) = '2016-07-13'  
		
			
		
EOD;
//	$databaseResult = mssql_fetch_assoc(mssql_query($sql));
	//$databaseResult = $database->fetchAll($sql);

$result = mssql_query($sql);
//$rows = mssql_fetch_assoc($leads);

	
$tempArray = array();
for ($i = 0; $i < mssql_num_rows($result); ++$i)
{
        $roe = mssql_fetch_assoc($result);
	array_push($tempArray, $roe);
}



$start = 'Starting workshop posts......';
echo "<pre>";
//print_r($start);
//print_r($tempArray);
echo "</pre>";

//$field = $array[0]->array->pString;
//print_r($field);

$url =  'pl-rmgstaticws01.response.corp/php/workRepost.php';

foreach($tempArray as $key => $val)
{
	$vars =  http_build_query($val);
	$result = post_content($url, count($val), $vars);
}



function post_content($url, $nfields, $post_fields)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $post_fields);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
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

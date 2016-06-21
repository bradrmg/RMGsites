<?php

$db_host = "OR-TSSQL01.response.corp";
$db_user = "dwWEIci16rGByGw8";
$db_pass= "R22tCY2kkK8UguFU";
$database = "DBA";

$dbhandle = mssql_connect($db_host, $db_user, $db_pass) or die("could not connect to sql server on $serverName");
$selected = mssql_select_db($database, $dbhandle) or die("could not connect to database $database");




	$sql =<<<EOD
	SELECT 
	'http://crm.hadhaus.com/api/send_lead.php?'
	+ 'provider=1569'
	+ '&campaign=9'
	+ '&list=3'
	+ '&first_name=' + C.FName
	+ '&last_name=' + C.LName
	+ '&phone=' + C.HomePhone
	+ '&email=' + C.Email
	FROM Contact C
		JOIN ProjectGroups P 
		ON C.ProjectID = P.ProjectID
		JOIN Dial D
		ON C.DialID = D.DialID 
		LEFT JOIN CRC 
		ON D.CRC = CRC.CRC
		WHERE C.ProjectID IN (763, 883, 849, 841, 895)
		AND CONVERT(varchar(30), (DATEADD(DAY, DATEDIFF(day, 0, C.Importdate), 0)), 101) 
			= CONVERT(varchar(30), (DATEADD(DAY, DATEDIFF(day, 0, (GETDATE() -151)), 0)), 101)
		--AND ImportDate >= '2012-09-10'
		--AND ImportDate <= '2012-09-16'
		AND D.CRC NOT IN ('DNC',  'IDNC', 'EIS', 'APP3', 'APP2', 'APP1', 'APP4')
		--AND CRC.Contact = 1
		AND CRC.Success = 0
	
EOD;
//	$databaseResult = mssql_fetch_assoc(mssql_query($sql));
	//$databaseResult = $database->fetchAll($sql);

$result = mssql_query($sql);
//$rows = mssql_fetch_assoc($leads);

$tempArray = array();
for ($i = 0; $i < mssql_num_rows( $result ); ++$i)
{
         $roe = mssql_fetch_assoc($result);
	array_push($tempArray, $roe);
}

$start = 'Starting batch......';
echo "<pre>";
print_r($start);
//print_r($roe);
echo "</pre>";


$url =  'http://crm.hadhaus.com/api/send_lead.php';


foreach($tempArray as $key => $val)
{
	$vars =  http_build_query($val);
	$result = post_content($url, count($val), $vars);
}


function post_content($url,$nfields, $post_fields)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $post_fields);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
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

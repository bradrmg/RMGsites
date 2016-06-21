<?php

$db_host = "OR-TSSQL01.response.corp";
$db_user = "dwWEIci16rGByGw8";
$db_pass= "R22tCY2kkK8UguFU";
//$db_host = "AV-RMGDEVDB01.blooms.corp";
//$db_user = "8v8UzAEARNFIVNo2";
//$db_pass = "NzdYc5oh4XeDXecY";
$database = "LEADS";

$dbhandle = mssql_connect($db_host, $db_user, $db_pass) or die("could not connect to sql server on $serverName");
$selected = mssql_select_db($database, $dbhandle) or die("could not connect to database $database");

$sql = "select * from PrefCustAM WHERE Phone1 = '5302445928' order by RegDate desc";

$result = mssql_query($sql);
//$rows = mssql_fetch_assoc($leads);
//$url = "http://pl-rmgstaticws01.blooint.com/php/winadvantage.php";
$url = "http://pl-rmgstaticws01.blooint.com/php/winadvantageSendemail.php";
$tempArray = array();
for ($i = 0; $i < mssql_num_rows( $result ); ++$i)
{
         $roe = mssql_fetch_assoc($result);
        array_push($tempArray, $roe);
}

echo "<pre>";
print_r($tempArray);
echo "</pre>";

foreach($tempArray as $key => $val){
	$vars = http_build_query($val);
	$result = post_content($url, count($val), $vars);
        error_log("sending " . $val['Phone1']);
}

function post_content($url,$nfields,$fields_string)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST,$nfields);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
    //curl_setopt($ch, CURLOPT_HEADER, 1);
    //curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; MSIE 7.0; Windows NT 6.0; en-US)');
    ob_start();
    curl_exec ($ch);
    curl_close ($ch);
    $string = ob_get_contents();
    ob_end_clean();
    return $string;
}

?>

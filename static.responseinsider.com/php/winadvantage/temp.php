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

$sql =<<<EOD
        select * from ApiPostResults
where date between '03/14/2012 08:11:41' and '03/15/2012 19:56:24'
order by date desc
EOD;
$result = mssql_query($sql);
//$rows = mssql_fetch_assoc($leads);
//$url = "http://pl-rmgstaticws01.blooint.com/php/winadvantage.php";
//$url = "http://pl-rmgstaticws01.blooint.com/php/winadvantageSendemail.php";
$tempArray = mssql_fetch_array($result);


error_log(print_r($tempArray, true));

?>

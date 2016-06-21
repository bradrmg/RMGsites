<?php

$db_host = "OR-TSSQL01.response.corp";
$db_user = "dwWEIci16rGByGw8";
$db_pass= "R22tCY2kkK8UguFU";
$database = "LEADS";


$dbhandle = mssql_connect($db_host, $db_user, $db_pass) or die("could not connect to sql server on $serverName");
$selected = mssql_select_db($database, $dbhandle) or die("could not connect to database $database");

$sql = "SELECT * FROM PrefCustAM WHERE RegDate between dateadd(day, datediff(day, 0 ,getdate())-30, 0) and getdate() order by RegDate desc";
$result = mssql_query($sql);
$tempArray = array();
for ($i = 0; $i < mssql_num_rows( $result ); ++$i)
{
         $roe = mssql_fetch_assoc($result);
        array_push($tempArray, $roe);
}

echo "<pre>";
print_r($tempArray);
echo "</pre>";
?>

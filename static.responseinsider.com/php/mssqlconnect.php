<?php
//$db_host = "OR-TSSQL01.response.corp";
//$db_user = "dwWEIci16rGByGw8";
//$db_pass= "R22tCY2kkK8UguFU";
//$database = "DBA";

$db_host = "AV-RMGDEVDB01.blooms.corp";
$db_user = "8v8UzAEARNFIVNo2";
$db_pass = "NzdYc5oh4XeDXecY";
$database = "DBA";


$dbhandle = mssql_connect($db_host, $db_user, $db_pass) or die("could not connect to sql server on $serverName");

$selected = mssql_select_db($database, $dbhandle) or die("could not connect to database $database");
$sql = "SELECT * FROM Seminars";

$result = mssql_query($sql);

$numRows = mssql_num_rows($result);
echo "<h1>" . $numRows . " Row" . ($numRows == 1 ? "" : "s") . " Returned </h1>";

//display the results
$retArr = mssql_fetch_array($result);

echo("<pre>");
print_r($retArr);
echo("</pre>");
//close the connection
mssql_close($dbhandle);

?>


<?php

$db_host = "OR-TSSQL01.response.corp";
$db_user = "dwWEIci16rGByGw8";
$db_pass= "R22tCY2kkK8UguFU";
$database = "LEADS";

$dbhandle = mssql_connect($db_host, $db_user, $db_pass) or die("could not connect to sql server on $serverName");
$selected = mssql_select_db($database, $dbhandle) or die("could not connect to database $database");

$email = $_REQUEST['Email'];
$uid = $_REQUEST['uid'];
$sql = "INSERT INTO EmailDNC(Email, EmailUID) VALUES('$email','$uid')";
mssql_query($sql);

error_log("Successfully Unsubscribed Email: $email.");
echo "Successfully Unsubscribed Email: $email.";

?>

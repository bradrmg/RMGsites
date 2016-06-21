<?php
$db_host = "OR-TSSQL01.response.corp";
$db_user = "dwWEIci16rGByGw8";
$db_pass= "R22tCY2kkK8UguFU";
$database = "LEADS";

$dbhandle = mssql_connect($db_host, $db_user, $db_pass) or die("3");
$sql = "INSERT INTO LEADS.dbo.EmailTracker(Email, EmailUID) VALUES('" . $_REQUEST['email'] . '\',\'' . $_REQUEST['uid'] . "')";
$result = mssql_query($sql);
mssql_close($dbhandle);

//error_log("email tracker detected that " . $_REQUEST['email'] . " just opend the email and the uid is: " . $_REQUEST['uid']);
?>


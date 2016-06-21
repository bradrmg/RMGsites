<?php

$db_host = "OR-TSSQL01.response.corp";
$db_user = "dwWEIci16rGByGw8";
$db_pass= "R22tCY2kkK8UguFU";
$database = "TOUCHSTAR";


$dbhandle = mssql_connect($db_host, $db_user, $db_pass) or die("could not connect to sql server on $serverName");
$selected = mssql_select_db($database, $dbhandle) or die("could not connect to database $database");


$sql = "Select * from ImportTotals";
$query = mssql_query($sql);
// Check if there were any records
if (!mssql_num_rows($query)) {
    echo 'No records found';
}
else
{
    // Print a nice list of users in the format of:
    // * name (username)

    echo '<ul>';

    while ($row = mssql_fetch_assoc($query)) {
        echo '<li>' . print_r($row)  . '</li>';
    }

    echo '</ul>';
}

?>

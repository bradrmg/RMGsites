<html>
<body>
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

$lovipsql = "SELECT * FROM PrefCustAM where RegType = 'LOVIP' ORDER BY RegDate desc";
$obvipsql = "SELECT * FROM PrefCustAM where RegType = 'OBVIP' ORDER BY RegDate desc";
$dtmvipsql = "SELECT * FROM PrefCustAM where RegType = 'DTMVIP' ORDER BY RegDate desc";
$obsql = "SELECT * FROM PrefCustAM where RegType = 'OB' ORDER BY RegDate desc";

$ob = mssql_fetch_array(mssql_query($obsql));
$dtmvip = mssql_fetch_array(mssql_query($dtmvipsql));
$obvip = mssql_query($obvipsql);
$lovip = mssql_fetch_array(mssql_query($lovipsql));

$obvip2 = mssql_fetch_assoc(mssql_num_rows($obvip));


?>
<pre>
<?php print_r($obvip2); ?>
</pre>
<div>
	<b>DTM VIP</b>
	<table>
	
	</table>
</div>


</body>
</html>

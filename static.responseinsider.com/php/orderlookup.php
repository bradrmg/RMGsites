<head>
<style>
#customers {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    width: 55%;
    border-collapse: collapse;
}

#customers td, #customers th {
    font-size: 1em;
    border: 1px solid #98bf21;
    padding: 3px 3px 3px 7px;
}

#customers th {
    font-size: 1.1em;
    text-align: left;
    padding-top: 5px;
    padding-bottom: 4px;
    background-color: #A7C942;
    color: #ffffff;
}

#customers tr.alt td {
    color: #000000;
    background-color: #EAF2D3;
}
</style>
</head>

<body>
<html>

<H2>Call Log Results</H2>

<form action="orderlookup.php" method="post">
Phone: <input type="text" name="phone" value=""><br>
<input type="submit">
</form>
</body>
</html>


<?php 
//			START OF THE PHP SCRIPT
$db_host = "OR-TSSQL01.response.corp";
$db_user = "dwWEIci16rGByGw8";
$db_pass= "R22tCY2kkK8UguFU";
$database = "DBA";

$dbhandle = mssql_connect($db_host, $db_user, $db_pass) or die("could not connect to sql server on $serverName");
$selected = mssql_select_db($database, $dbhandle) or die("could not connect to database $database");


$phone = $_POST["phone"];


	$sql =<<<EOD
	Select 
	OrderStatus,
	OrderedFrom,
	CSnumber,
	ConfID
	From OrderFulfillment
	where HomePhone = '{$phone}'
	
EOD;

	$result = mssql_query($sql);
	
	
	$tempArray = array();
	for ($i = 0; $i < mssql_num_rows( $result ); ++$i)
	{
		$row = mssql_fetch_assoc($result);
		array_push($tempArray, $row);
	}
	
	//cho "<pre>";
	//print_r($row);
	//echo "</pre>";
	?>
	
<table id ="customers">
	<thead>
	<th>Status</th>
	<th>Ordered From</th>
	<th>Store Phone</th>
	<th>Order Confirmation #</th>
	</thead>
	<tbody>
	
	<?php 
	foreach ($tempArray as $key => $value)
	{
	?>
	
	<tr>
	<td><?php print_r($value['OrderStatus']);?></td>
	<td><?php print_r($value['OrderedFrom']);?></td>
	<td><?php print_r($value['CSnumber']);?></td>
	<td><?php print_r($value['ConfID']);?></td>
	</tr>
	<?php 
	}
	?>
	</tbody>
	</table>
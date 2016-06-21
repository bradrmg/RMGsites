<head>
<style>
#agents {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    width: 55%;
    border-collapse: collapse;
}

#agents td, #agents th {
    font-size: 1em;
    border: 1px solid #98bf21;
    padding: 3px 3px 3px 7px;
}

#agents th {
    font-size: 1.1em;
    text-align: left;
    padding-top: 5px;
    padding-bottom: 4px;
    background-color: #A7C942;
    color: #ffffff;
}

#agents tr.alt td {
    color: #000000;
    background-color: #EAF2D3;
}
</style>

	<script type="text/javascript" src="jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="functions.js"></script>
	
	
</head>

<body>
<html>

<H2>Agent Payroll ID's</H2>


</body>
</html>


<?php 
//<form action="out.php" method="post">
//Phone: <input type="text" name="phone" value=""><br>
//<input type="submit">
//</form>

//			START OF THE PHP SCRIPT
$db_host = "OR-TSSQL01.response.corp";
$db_user = "dwWEIci16rGByGw8";
$db_pass= "R22tCY2kkK8UguFU";
$database = "Touchstar";

$dbhandle = mssql_connect($db_host, $db_user, $db_pass) or die("could not connect to sql server on $serverName");
$selected = mssql_select_db($database, $dbhandle) or die("could not connect to database $database");


$phone = $_POST["phone"];


	$sql =<<<EOD
	SELECT
	pFname, 
	pLname, 
	pAgentID, 
	TSagentID 
	From AgentPayroll
	
	
EOD;
//where PhoneNum = '{$phone}'

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
	
<table id ="agents">
	<thead>
	<th>FirstName</th>
	<th>LastName</th>
	<th>PayRollID</th>
	<th>DialerID</th>
	<th></th>
	</thead>
	<tbody>
	
	<?php 
	foreach ($tempArray as $key => $value)
	{
	?>
	
	<tr>
	<td><?php print_r($value['pFname']);?></td>
	<td><?php print_r($value['pLname']);?></td>
	<td><?php print_r($value['pAgentID']);?></td>
	<td><?php print_r($value['TSagentID']);?></td>
	<td><img src="images/delete.png" class="btnExcluir"/><img src="images/pencil.png"/ class="btnEditar"></td>
	</tr>
	<?php 
	}
	?>
	</tbody>
	</table>

<?php

if($_POST)
   $data = $_POST;
else if($_GET)
   $data = $_GET;


if($data['x_test']){
	$db_host = "AV-RMGDEVDB01.blooms.corp";
	$db_user = "8v8UzAEARNFIVNo2";
	$db_pass = "NzdYc5oh4XeDXecY";
}else{
	$db_host = "OR-TSSQL01.response.corp";
	$db_user = "dwWEIci16rGByGw8";
	$db_pass= "R22tCY2kkK8UguFU";

}

$database = "LEADS";

$dbhandle = mssql_connect($db_host, $db_user, $db_pass) or die("3");
$selected = mssql_select_db($database, $dbhandle) or die("4");

$sqlValues = "";
$sqlValues .= ($data['x_first_name']) ? "'" . $data['x_first_name'] . "'" : "''";
$sqlValues .= ($data['x_last_name']) ? ", '" . $data['x_last_name'] . "'" : ", ''";
$sqlValues .= ($data['x_address1']) ? ", '" . $data['x_address1'] . "'" : ", ''";
$sqlValues .= ($data['x_address2']) ? ", '" . $data['x_address2'] . "'" : ", ''";
$sqlValues .= ($data['x_city']) ? ", '" . $data['x_city'] . "'" : ", ''";
$sqlValues .= ($data['x_state']) ? ", '" . $data['x_state'] . "'" : ", ''";
$sqlValues .= ($data['x_zipcode']) ? ", '" . $data['x_zipcode'] . "'" : ", ''";
$sqlValues .= ($data['x_country']) ? ", '" . $data['x_country'] . "'" : ", ''";
$sqlValues .= ($data['x_email']) ? ", '" . $data['x_email'] . "'" : ", ''";
$sqlValues .= ($data['x_home_phone']) ? ", '" . $data['x_home_phone'] . "'" : ", ''";
$sqlValues .= ($data['x_cell_phone']) ? ", '" . $data['x_cell_phone'] . "'" : ", ''";
$sqlValues .= ($data['x_alt_phone']) ? ", '" . $data['x_alt_phone'] . "'" : ", ''";
$sqlValues .= ($data['x_ip']) ? ", '" . $data['x_ip'] . "'" : ", ''";
$sqlValues .= ($data['x_url']) ? ", '" . $data['x_url'] . "'" : ", ''";
$sqlValues .= ", '" . date("Y-m-d H:i:s", time()) . "'";

$sql = "INSERT INTO Debt ";
$sql .= "(Fname, Lname, HomeAdd1, HomeAdd2, HomeCity, HomeState, HomePostcode, HomeCountry, Email, Homephone, CellPhone, AltPhone, IP, URL, DateCreated) ";
$sql .= "VALUES(" . $sqlValues . ")";

try{
	mssql_query($sql);
	echo 1;
}catch(Exception $e){
	echo 2;
}

?>

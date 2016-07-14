<?php 
//Database connection info
$db_host = "OR-TSSQL01.response.corp";
$db_user = "dwWEIci16rGByGw8";
$db_pass = "R22tCY2kkK8UguFU";
$database = "DBA";

$dbhandle = mssql_connect($db_host, $db_user, $db_pass) or die("could not connect to sql server on $db_host");
$selected = mssql_select_db($database, $dbhandle) or die("could not connect to database $database");


function get_crm_guid($rmgID){
	if($rmgID <> "" && $rmgID <> "NULL"){
		$sql = <<<EOD
		SELECT 
		registration_id
		FROM PostResult
		WHERE 1=1
		AND RegID = {$rmgID}
EOD;

		$returnguid = mssql_query($sql);
		$rcount = mssql_num_rows($returnguid);
		while($row = mssql_fetch_array($returnguid)){
			$guid = $row['registration_id'];
		}
		return $guid;
	}
	else {
		$ret = "Error: No ID passed";
	}
	return $ret;
}


function is_valid_luhn($number) {
	settype($number, 'string');
	$sumTable = array(
			array(0,1,2,3,4,5,6,7,8,9),
			array(0,2,4,6,8,1,3,5,7,9));
	$sum = 0;
	$flip = 0;
	for ($i = strlen($number) - 1; $i >= 0; $i--) {
		$sum += $sumTable[$flip++ & 0x1][$number[$i]];
	}
	return $sum % 10 === 0;
}

?>
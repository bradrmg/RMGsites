<?php

//Database connection info
$db_host = "OR-TSSQL01.response.corp";
$db_user = "dwWEIci16rGByGw8";
$db_pass= "R22tCY2kkK8UguFU";
$database = "DBA";

$dbhandle = mssql_connect($db_host, $db_user, $db_pass) or die("could not connect to sql server on $db_host");
$selected = mssql_select_db($database, $dbhandle) or die("could not connect to database $database");


$postvars = ($_REQUEST);

$test = "true";

$token = "41ef1e1f12d0f3fb37d617622b710ee4ac85d564dbeaa152c9bee2f2e7390ef643a67e5d6e29e3347d80544b07aafd02f9a42d24f76478c8e3bed4fb57fae778";
$pricelist = "D9E199DC-FB12-4C0A-BBF4-FC3E8661E3C9";
$merch_acct = "6DDE79FC-2CEA-E515-80C6-10AD3734101C";

$products = array(
    "product_id" => $productid,
    "quantity" => $qty,
    "price" => $price,
);

$paymentdets = array(
    "credit_card" => $ccnum, 
	"expiry" => $ccexpire, 
	"name_on_card" => $ccname, 
	"zip_code" => $cczip, 
	"merchant_account_id" => $merch_acct,
);

print json_encode($paymentdets);


?>
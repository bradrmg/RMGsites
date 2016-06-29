<?php
include 'luhn.php';
//API SCRIPT FOR SENDING ORDERS TO CRM
//API Documentation - https://staging-orders.responsemg.com/apipie/1.0/orders.html

//Database connection info
$db_host = "OR-TSSQL01.response.corp";
$db_user = "dwWEIci16rGByGw8";
$db_pass = "R22tCY2kkK8UguFU";
$database = "DBA";

$dbhandle = mssql_connect($db_host, $db_user, $db_pass) or die("could not connect to sql server on $db_host");
$selected = mssql_select_db($database, $dbhandle) or die("could not connect to database $database");

//Setting variable from post
$postvars = ($_REQUEST);
$regid = $_REQUEST['regid'];

//Test post is true of false
$test = "true";

//Auth Token
$apitoken = "41ef1e1f12d0f3fb37d617622b710ee4ac85d564dbeaa152c9bee2f2e7390ef643a67e5d6e29e3347d80544b07aafd02f9a42d24f76478c8e3bed4fb57fae778";

//************PRODCUTS***************************
//All product ID's will come from CRM and will be managed from what the person buys
//Currently only one product and price list given when more are added a case statement will be used to supply items
$pricelist = "D9E199DC-FB12-4C0A-BBF4-FC3E8661E3C9";
$productid = "89F28C02-4A85-E411-A57D-524400B60873"; //vip

//Setting variable data from post for products array
$qty = $_REQUEST['qty'];
$price = $_REQUEST['price'];

//Products array to pass what has been purchased
$products = array(
    "product_id" => $productid,
    "quantity" => $qty,
    "price" => $price
);
//********END PRODCUTS***************************

//********PAYMENTS*******************************
//Setting variable for payment details from post
$ccnum = $postvars['ccnum'];
$ccexpire = $postvars['ccexpire'];
$ccname = $postvars['ccname'];
$cczip = $postvars['cczip'];

//Merchant account info will be specified from CRM/Dev Team
$merch_acct = "6DDE79FC-2CEA-E515-80C6-10AD3734101C";
//Payemnt array to pass billing details
$paymentdets = array(
    "credit_card" => $ccnum, 
	"expiry" => $ccexpire, 
	"name_on_card" => $ccname, 
	"zip_code" => $cczip, 
	"merchant_account_id" => $merch_acct
);
$cleanbrack = array("[", "]");
$cpayments = str_replace($cleanbrack," ",$paymentdets);
$payment = json_encode($cpayments);
//********END PAYMENTS***************************

//Build findal post array and post fields
$postarray = array(
	"test" => $test,
	"price_list_id" => $pricelist,
	"api_token" => $apitoken,
	"registration_id" => $regid,
		"payment" => str_replace(array( '(', ')' ), '', $paymentdets),
		"products" => array($products)
);


$url = "https://staging-orders.responsemg.com/orders";
$postfields = json_encode($postarray);
//print_r($postfields);

//VALIDATION OF DATA BEFORE POST
//Checking if cc num is valid via luhn check, returns true or false
$ccvalid = is_valid_luhn($ccnum);

//If card is not valid return error and die
if ($ccvalid == false){
	die('Credit Card Number is Invalid');
}

//Checking for all blank reg ID
//IF Card is calid and there is a reg id then the curl post will initiate
if($regid == "") {
	die('Empty registration_id cannot post');
}
else {

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);

	$result = curl_exec($ch);
	$array = json_decode($result);

	//echo "<pre>";
	print_r($result);
	//echo "</pre>";
}




?>
<?php
//Including the functions script
include 'fn.php';
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

//Call function to get the CRM GUID from the Reg ID passed
$guid = get_crm_guid($regid);
//echo $guid;
$tguid = "8C9C9519-2045-E611-80CB-000D3A32101C";

//Test post is true of false
$test = "false";

//Auth Token
$apitoken = "e8e6a9e418949672beb56c9f49e42ef9d9bcb72cd676afb325fc222730d6051ded0ad1758b390635b292e892f295765c028398da003e4dabc96c94681606811a";

//************PRODCUTS***************************
//All product ID's will come from CRM and will be managed from what the person buys
//Currently only one product and price list given when more are added a case statement will be used to supply items
$pricelist = "E3108C82-2745-E611-80CB-000D3A32101C";
$productid = "FFDA8F4B-2745-E611-80CB-000D3A32101C"; //vip

//Setting variable data from post for products array
$qty = $_REQUEST['qty'];
$price = $_REQUEST['price'];

//IF qty or price not set, default to 1 and $10.00
if($qty == ""){
	$quantity = 1;
}else{
	$quantity = $qty;
}

if($price == ""){
	$fprice = "10.00";
}else{
	$fprice = $price;
}


//Products array to pass what has been purchased
$products = array(
    "product_id" => $productid,
    "quantity" => $quantity,
    "price" => $fprice
);
//********END PRODCUTS***************************

//********PAYMENTS*******************************
//Setting variable for payment details from post
$ccnum = $postvars['ccnum'];
$ccexpire = $postvars['ccexpire'];
$ccname = $postvars['ccname'];
$cczip = $postvars['cczip'];

//Merchant account info will be specified from CRM/Dev Team
$merch_acct = "FB1C8A08-9110-E611-80C8-000D3A32101C";
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


//Build final post array and post fields
$postarray = array(
	"test" => $test,
	"price_list_id" => $pricelist,
	"api_token" => $apitoken,
	"registration_id" => $guid,
		"payment" => str_replace(array( '(', ')' ), '', $paymentdets),
		"products" => array($products)
);

$url = "https://orders.responsemg.com/orders";
//$url = "https://staging-orders.responsemg.com/orders";
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
//IF Card is valid and there is a reg id then the curl post will initiate
if($guid == "") {
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
	
//Return result variables	
	$errors = json_encode($array->errors);
	$errorsi = str_replace("'"," ",$errors);
	$errorcheck = $array->errors;
	$pay_id = $array->payment_id;
	$order_id = $array->order_id;
	$credit_card = $array->credit_card;
	//if($errors == "[]"){
		//$errori = "Success";
	//}else{
		//$errori = $errors;
	//}
	

	$postype = "CC_VIP";

//Insert Statement to capture CRM ID and post information
	$sql =<<<EOD
INSERT INTO OrderAPI (RegID, OrderType, registration_id, errors, payment_id, order_id, credit_card, product_id, merchant_account, quantity, price)
VALUES ('$regid','$postype', '$guid', '$errorsi', '$pay_id', '$order_id', '$credit_card', '$productid', '$merch_acct', '$quantity', '$fprice')
EOD;
	mssql_query($sql);
	//echo "<pre>";
	print_r($result);
	echo "<br/>";
	if(count($errorcheck) > 0)
	{
		echo 'Error';
	}
	else
	{
		echo 'Success';
	}

}




?>
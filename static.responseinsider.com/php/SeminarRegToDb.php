<?php

//$postData = $_GET;
$postData = $_POST;
$db_host = "OR-TSSQL01.response.corp";
$db_user = "dwWEIci16rGByGw8";
$db_pass= "R22tCY2kkK8UguFU";
//$db_host = "AV-RMGDEVDB01.blooms.corp";
//$db_user = "8v8UzAEARNFIVNo2";
//$db_pass = "NzdYc5oh4XeDXecY";
$database = "DBA";

$sqlValues = "";
$sqlValues .= "'" . $postData['lead_id'] . "'";
$sqlValues .= ", '" . $postData['parentId'] . "'";
$sqlValues .= ", '" . $postData['fName'] . "', '" . $postData['lName'] . "', '" . $postData['address'] . "'";
$sqlValues .= ", '" . $postData['address2'] . "'";
$sqlValues .= ", '" . $postData['city'] . "'";
$sqlValues .= ", '" . $postData['state'] . "'";
$sqlValues .= ", '" . $postData['zipcode'] . "'";
$sqlValues .= ", '" . $postData['email'] . "'";
$sqlValues .= ", '" . $postData['phone'] . "'";
$sqlValues .= ", '" . $postData['offerName'] . "'";
$sqlValues .= ", '" . $postData['publisher'] . "'";
$sqlValues .= ", '" . $postData['affiliateId'] . "'";
$sqlValues .= ", '" . $postData['guestName'] . "'";
$sqlValues .= ", '" . $postData['seminar'] . "'";
$sqlValues .= ", '" . $postData['dateCaptured'] . "'";
$sqlValues .= ", '" . $postData['cellPhone'] . "'";
$sqlValues .= ", '" . $postData['textReminder'] . "'";
$sqlValues .= ", '" . $postData['futureTexts'] . "'";
$sqlValues .= ", '" . $postData['tourcode'] . "'";
$sqlValues .= ", '" . $postData['inboundNumber'] . "'";
$sqlValues .= ", '" . $postData['marketgroup'] . "'";
$sqlValues .= ", '" . $postData['originator'] . "'";

//echo $sqlValues;

$dbhandle = mssql_connect($db_host, $db_user, $db_pass) or die("could not connect to sql server on $serverName");
$selected = mssql_select_db($database, $dbhandle) or die("could not connect to database $database");



$sql = "INSERT INTO Registration ";
$sql .= "(Leadid, ParentID, FirstName, LastName, Address, Address2, City, State, Zipcode, Email, Phone, Offername, Publisher, AffiliateID, Guest, SeminarName, Registerdate, CellPhone, TextReminder, FutureTexts, TourCode, Inbound_Number, MarketGroupId, LeadType)";
$sql .= "VALUES(" . $sqlValues . ")";

$result = mssql_query($sql);
//echo $sql;
echo 0;
mssql_close($dbhandle);




$vars = http_build_query($postData);
post_content("pl-rmgstaticws01.blooint.com/php/sendconfemail.php", count($postVars), $vars);

function post_content($url,$nfields,$fields_string)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST,$nfields);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
    //curl_setopt($ch, CURLOPT_HEADER, 1);
    //curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; MSIE 7.0; Windows NT 6.0; en-US)');
    ob_start();
    curl_exec ($ch);
    curl_close ($ch);
    $string = ob_get_contents();
    ob_end_clean();
    return $string;
}




?>

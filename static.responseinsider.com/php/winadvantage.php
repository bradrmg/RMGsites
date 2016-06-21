<?php
$url1 = "trseducation.com/blooskyregistration.asp";
$vars = '';

$postVars = array();
if($_POST)
	$postVars = $_POST;
else if($_GET)
	$postVars = $_GET;

foreach($postVars as $key => $value){
        switch($key){
                case "Email":
                        if(($value != "none@nonemail.com") && (!empty($value)))
                                $vars .= "&Email=" . urlencode($value);
                        break;
		case "Phone1":
			($value != "") ? $vars .= "&Phone1=" . $value : $vars .= "&phone1=" . $postVars['Phone2'];
			break;
                case "RegDate":
			if($value != ""){
                                $vars .= "&RegDate=" . urlencode(date("m/d/Y g:i a", strtotime($value)));
                                $_REQUEST['RegDate'] = date("m/d/Y g:i a", strtotime($value));
                        }else{
                                $vars .= "&RegDate=" . urlencode(date("m/d/Y g:i a"));
                                $_REQUEST['RegDate'] = date("m/d/Y H:i:s");
                        }
                        break;
                case "ConfAuthCode":
                        $val =  (strlen($value) > 4) ? urlencode($value) : urlencode("");
                        $vars .= "&ConfAuthCode=" . $val;
                        break;
		case "Corrected":
		case "ConfID":
			break;
                default:
                        $vars.= "&" . $key . "=" . urlencode($value);
                        break;
        }
}
$vars = substr($vars, 1);
echo $vars;

//need to hardcode this so winadvantage can authenticate this
$vars .= "&unme=wec5ubEzeyAfRADe&pwd=DR-yew-Va6etREPr";
//error_log($vars);


if(($postVars['SeminarSKU'] == "") || ($postVars['PostalCode'] == "") || ($postVars['Phone1'] == "") || ($postVars['City'] == "") || ($postVars['FirstName'] == "") 
		|| ($postVars['LastName'] == "") || ($postVars['StreetAddress1'] == "") || ($postVars['State'] == "") || ($postVars['RegType'] == "")){
	echo " missing Fields sending email to doug long";
        post_content("pl-rmgstaticws01.response.corp/php/winadvantageSendemail.php", count($postVars), $vars);
	$result = "Emailed doug for missing data";
}else{
	$result = post_content($url1, count($postVars), $vars);
        //echo $result;
        echo "<pre>";
        echo " Post result: ";
        echo($result);
        echo "</pre> result completed<br/>";
}


error_log($result);


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



//following code will write to the local database with this lead
$postData = $_REQUEST;
$postData['PostResult'] = str_replace("'", "''", strip_tags($result));

$db_host = "OR-TSSQL01.response.corp";
$db_user = "dwWEIci16rGByGw8";
$db_pass= "R22tCY2kkK8UguFU";
//$db_host = "AV-RMGDEVDB01.blooms.corp";
//$db_user = "8v8UzAEARNFIVNo2";
//$db_pass = "NzdYc5oh4XeDXecY";
$database = "LEADS";


$dbhandle = mssql_connect($db_host, $db_user, $db_pass) or die("could not connect to sql server on $serverName");
$selected = mssql_select_db($database, $dbhandle) or die("could not connect to database $database");

if(isset($postData['ConfID'])){
	unset($postData['ConfID']);
}

$sql = "INSERT INTO PrefCustAM ";
$sql .= "(";
$sqlValues = "";
foreach($postData as $key => $val){
   $sql .= $key . ",";
   $sqlValues .= "'" . $val . "',";
}
$sql = substr($sql, 0, strlen($sql)-1);
$sqlValues = substr($sqlValues, 0, strlen($sqlValues)-1);;

$sql .= ") ";
$sql .= "VALUES(" . $sqlValues . ")";
//error_log("this is the error log for pref cust am insert");
//error_log($sql);
mssql_query($sql);



?>

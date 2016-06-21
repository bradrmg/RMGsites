<?php

$db_host = "OR-TSSQL01.response.corp";
$db_user = "dwWEIci16rGByGw8";
$db_pass= "R22tCY2kkK8UguFU";
//$db_host = "AV-RMGDEVDB01.blooms.corp";
//$db_user = "8v8UzAEARNFIVNo2";
//$db_pass = "NzdYc5oh4XeDXecY";
$database = "DBA";

$dbhandle = mssql_connect($db_host, $db_user, $db_pass) or die("could not connect to sql server on $serverName");
$selected = mssql_select_db($database, $dbhandle) or die("could not connect to database $database");

$tourcode = $_REQUEST['TourCode'];

//RMGO lead_type is default
if(($tourcode != "") && ($_REQUEST['Email'] != "") && ($_REQUEST['lead_type'] != "")){
	$emailRequest = $_REQUEST['Email'];
	$emaildncsql = "SELECT * FROM LEADS.dbo.EmailDNC WHERE Email = '$emailRequest'";
	$queryemail = mssql_query($emaildncsql);
	if(!mssql_num_rows($queryemail)){
		$sql = "SELECT * FROM Seminars WHERE TourCode = '$tourcode'";

		$query = mssql_query($sql);
		$result = array();
		if(!mssql_num_rows($query)){
			echo "No Seminar Found for tourcode: $tourcode";
		}else{
			echo "Sending email to: " . $_REQUEST['Email'];
			$result = mssql_fetch_assoc($query);
			$vars = http_build_query($result);
			$vars .= "&" . http_build_query($_REQUEST);
			$returnRslt = post_content("realestateeventregistration.a-rmgws01.blooint.com/script/testConfemails2.php", count($result), $vars);
			error_log("send conf email message " . print_r($returnRslt));
			echo $returnRslt;
			$lead_type = $_REQUEST['lead_type'];
			$speaker = $_REQUEST['SpeakerName'];
			
//			$saveSql = "INSERT INTO LEADS.dbo.EmailHistory (TourCode, Speaker, Email, lead_type, Success) VALUES ('$tourcode', '$speaker', '$emailRequest', '$lead_type', '$returnRslt')";
//			mssql_query($saveSql);
		}
	}else {
		echo "Email DNC";
	}

if($returnRslt != "")
	$successMsg = $returnRslt;
else
	$successMsg = "Email DNC";
$saveSql = "INSERT INTO LEADS.dbo.EmailHistory (TourCode, Speaker, Email, lead_type, Success) VALUES ('$tourcode', '$speaker', '$emailRequest', '$lead_type', '$successMsg')";
mssql_query($saveSql);


}else{
	echo "Either tourcode, or Email or lead_type is missing";
}



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

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

$sql =<<<EOD
SELECT 
r.Firstname, 
r.Lastname, 
r.Address, 
r.Address2, 
r.City, 
r.State, 
Zipcode, 
Phone,
CellPhone, 
r.TourCode,
Inbound_Number, 
Registerdate, 
Seminars.SeminarName, 
SeminarTime, 
SpeakerName, 
Email,  
Guest,
LeadType,
AffiliateID,
Leadid,
AMDM.AuthorizationCode as 'ConfAuthCode'
FROM Registration r
LEFT JOIN Seminars
ON r.TourCode = Seminars.TourCode
LEFT JOIN LEADS.dbo.AMDM
ON r.Address = AMDM.Address1 and r.Firstname = AMDM.FirstName and r.Lastname = AMDM.LastName
and r.Zipcode = AMDM.Zip
Where r.Registerdate > '2011-11-21 00:01:00'
AND LeadType = 'IB'
order by Registerdate
EOD;

$result = mssql_query($sql);
//$rows = mssql_fetch_assoc($leads);
$url = "http://pl-rmgstaticws01.blooint.com/php/winadvantage.php";
$tempArray = array();
for ($i = 0; $i < mssql_num_rows( $result ); ++$i)
{
         $roe = mssql_fetch_assoc($result);
	array_push($tempArray, $roe);
}

foreach($tempArray as $key => $line){
	$arr = array();
        $arr["City"] = $line["City"];
        $arr["ConfAuthCode"] = $line["ConfAuthCode"];
        $arr["Country"] = "";
        $arr["Email"] = $line["Email"];
        $arr["FirstName"] = $line["Firstname"];
        $arr["LastName"] = $line["Lastname"];
        $arr["Phone1"] = $line["Phone"];
        $arr["Phone2"] = $line["CellPhone"];
        $arr["PostalCode"] = $line["Zipcode"];
        $arr["RegDate"] = $line["Registerdate"];
        $arr["RegType"] = $line["LeadType"];
        $arr["SeminarSKU"] = $line["TourCode"];
        $arr["State"] = $line["State"];
        $arr["StreetAddress1"] = $line["Address"];
        $arr["StreetAddress2"] = $line["Address2"];
        $vars = http_build_query($arr);
        $result = post_content($url, count($arr), $vars);
        error_log("sending " . $line["Phone"]);
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
}
error_log("sending data COMPLETED!!!!");

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

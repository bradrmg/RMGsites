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


//this sql statement will get all the registrants from two days from today
$sql =<<<EOD
	SELECT 
		Firstname as 'Fname', 
		Lastname as 'Lname', 
		r.TourCode, 
		r.LeadType as 'lead_type',  
		r.Email,
		s.SpeakerName,
		s.SeminarDate
	FROM Registration r
		JOIN Seminars s	on r.TourCode = s.TourCode
	WHERE r.LeadType = 'RMGTL'
		AND r.TourCode <> ''
		AND Email <> ''
		AND s.SeminarDate = CONVERT(date, DATEADD(day, 2, getdate()), 112)
EOD;

$resultSet = mssql_query($sql);
$registrants = array();
while($row = mssql_fetch_assoc($resultSet)){
	$TourCode = $row['TourCode'];
	$Type = $row['lead_type'];
	$Speaker = $row['SpeakerName'];
	$Date = $row['SeminarDate'];
	array_push($registrants, $row);
}

//add any static emails you need to send emails to every time we send the cronjob
$staticEmails = array(
			'Fname' 	=> 'Doug',
			'Lname' 	=> 'Lname',
			'TourCode'	=> $TourCode,
			'lead_type'	=> $Type,
			'Email'		=> 'dlong@responsemg.com',
			'SpeakerName'	=> $Speaker,
			'SeminarDate'	=> $Date
		);
$staticEmails2 = array(
                        'Fname'         => 'Tarun',
                        'Lname'         => 'Kanagala',
                        'TourCode'      => $TourCode,
                        'lead_type'     => $Type,
                        'Email'         => 'tkanagala@responsemg.com',
                        'SpeakerName'   => $Speaker,
                        'SeminarDate'   => $Date
                );

//adds the static emails to the cronjob list of recipients
if(!empty($registrants)){
	array_push($registrants, $staticEmails);
	array_push($registrants, $staticEmails2);
	echo "<pre>";
	print_r($registrants);
	echo "</pre>";
	foreach($registrants as $val){
		$vars = http_build_query($val);
		$returnRslt = post_content("http://pl-rmgstaticws01.blooint.com/php/sendemail/sendTaxReminderEmail.php", count($vars), $vars);
		error_log("sending Reminder Tax email to: " . $val['Email']);
	}
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

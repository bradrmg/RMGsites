<?php

include("../MiscClasses/Database.php");

$db_host = "OR-TSSQL01.response.corp";
$db_user = "dwWEIci16rGByGw8";
$db_pass= "R22tCY2kkK8UguFU";
$database = "DBA";

$dbhandle = mssql_connect($db_host, $db_user, $db_pass) or die("could not connect to sql server on $serverName");
$selected = mssql_select_db($database, $dbhandle) or die("could not connect to database $database");



	function testFunction($vary)
	{
		if ($vary == '1') 
		{ 
			return "Returning Function";
		}
		else 
		{
			return "You did not pass the number 1";
		}
	}
	
	//$input = '2';
	//$print = testFunction($input);
	//echo $print;
	
	function getQaTotals($sdate, $edate)
	{
	
		$sql =<<<EOD
		SELECT distinct(m.ComplianceAgentUserName), COUNT(m.ID) as Calls,
			SUM(his.TalkTime) as TotalTalkTime, AVG(his.TalkTime) as AverageTalkTime
		FROM Monitoring m
		LEFT JOIN Touchstar.dbo.History his on m.HistoryID = his.HistoryID
		WHERE m.DateCreated between '{$sdatedate}' and '{$edate}'
		GROUP BY m.ComplianceAgentUserName
EOD;
	
		$result = $db->fetchAll($sql);
		return $result;
		echo $result;
	}

	getQaTotals('2015-07-29', '2015-07-30');



?>

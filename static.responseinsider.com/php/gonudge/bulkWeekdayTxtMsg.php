<?php
include("/var/www/static.responseinsider.com/php/MiscClasses/TxtMsgContent.php");
//include("../MiscClasses/Database.php");
	$_WSDL_URI = "https://portal.ziplingo.com/GoNudgeSMS.svc?wsdl";
        $client = new SoapClient($_WSDL_URI, array('trace' => true));
        $arrParameters_Login = array('username' => 'apiuser', 'password' => 'apiuser1234');
        $securityTokenObj = $client->GetSecurityToken($arrParameters_Login);
        $token = $securityTokenObj->GetSecurityTokenResult;
        //$result = $soap->QueueMessageForDelivery();

	$database = new Database();
	$db = $database->getInstance('production','DBA');
	$sql =<<<EOD
		SELECT 
			r.Firstname as 'Fname', 
			r.Lastname as 'Lname',
			r.CellPhone,
			s.SpeakerName, 
			s.SeminarDate,
			s.SeminarName,
			s.SeminarTime,
			s.SeminarAddress
		FROM Registration r 
		JOIN Seminars s on r.TourCode = s.TourCode 
		WHERE r.LeadType in ('RMGO', 'RMGI', 'RMGPB', 'LiveOps', 'RMGTL', 'Online') 
			AND r.TourCode <> '' 
			AND CellPhone <> '' 
			AND TextReminder = 1 
			AND s.SeminarDate = CONVERT(date, getdate(), 112)
			AND r.Test NOT IN ('Mobile Coach', 'Mobile Coach v2')
			AND s.SpeakerName NOT IN ('RE Workshop', 'Stock Workshop', 'RE Intensive MMI')
			AND r.Publisher NOT IN ('CASTLINEMEDIA', 'DTM', 'TGR')
	
			
EOD;
//	$databaseResult = mssql_fetch_assoc(mssql_query($sql));
	$databaseResult = $database->fetchAll($sql);

/********************************************************************
* last element in array used to copy brad and mariah on the txt msgs
********************************************************************/
	$lastElem = end($databaseResult);
//	$databaseResult = array();
	$temp1 = $lastElem;
	$temp2 = $lastElem;
	$temp3 = $lastElem;
	$temp1['CellPhone'] = '8018367839'; //brad
	$temp2['CellPhone'] = '8013605884'; //mariah
	array_push($databaseResult, $temp1);
	array_push($databaseResult, $temp2);
	
/*******************************************************************/

	$smsrecipients = array();
	$count = 0;
	foreach($databaseResult as $row){
		$row['StartTime'] = getStartTime($row['SeminarTime']);
		$me = new SMSRecipient();
	        $me->ContactId = '';
	        $me->Email = '';
	        $me->FirstName = $row['Fname'];
	        $me->IsValid = False;
	        $me->IsWireless = IsWireless::Unknown;
	        $me->LastName = $row['Lname'];
	        $me->MessageId = $count;
	        $me->MobilePhoneNumber = $row['CellPhone'];
	        $me->Status = '';
	        $me->Drilldown = '';
	        $me->ErrorDescription = '';
	        $me->Id = 0;
	        $me->ResultCode = ResultCode::Success;
		$count++;
		array_push($smsrecipients, $me);

		$cont = new TxtMsgContent();
	        $msg = $cont->getContent($row, 'reminderWeekday');
		
		error_log(print_r($row, true));
		error_log($msg);

		$arrParameters_QueueMessageForDelivery = array(
	                'smsMessage' => $msg,
	                'recipients' => array($me),
	                'token' => $token,
	                'deliveryMethod' => DeliveryMethod::ShortCode);

	        $sendResult = $client->QueueMessageForDelivery2($arrParameters_QueueMessageForDelivery);
	}
// Helper code
			function getStartTime($str){
				$startTime = date("h:i" , strtotime('-30 minutes', strtotime($str)));
				return $startTime;
			}
			class DeliveryMethod
			{
				const _Default   = 'Default';
				const ShortCode = 'ShortCode';
				const LongCode  = 'LongCode';
			}

			class ResultCode 
			{ 
				const Success      = 'Success';	
				const InvalidToken = 'InvalidToken';	
				const ExpiredToken = 'ExpiredToken';	
				const GeneralError = 'GeneralError';	
				const SQLError     = 'SQLError';    	
			} 

			class IsWireless
			{
				const Unknown =  'Unknown';
				const True    =  'True';
        		const False   =  'False';
			}

			class BaseResult
			{
				public $Drilldown;
				public $ErrorDescription;
				public $Id;
				public $ResultCode;
			}

			class SMSRecipient extends BaseResult
			{
				public $ContactId; 
				public $Email; 
				public $FirstName; 
				public $IsValid; 
				public $IsWireless; 
				public $LastName; 
				public $MessageId; 
				public $MobilePhoneNumber; 
				public $Status;
			} 

?>

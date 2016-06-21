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
			r.TourCode,
			r.Firstname as 'Fname', 
			r.Lastname as 'Lname',
			r.CellPhone,
			s.SpeakerName, 
			s.SeminarDate,
			s.SeminarName,
			s.SeminarTime,
			s.SeminarAddress,
			s.ConfSMS,
			s.ReminderSMS as ReminderSMS
		FROM Registration r 
		JOIN Seminars s on r.TourCode = s.TourCode 
		WHERE r.LeadType in ('WSOB') 
			AND r.TourCode <> '' 
			AND CellPhone <> '' 
			AND TextReminder = 1 
			AND s.SeminarDate = CONVERT(date, DATEADD(day, 1, getdate()), 112)
			AND s.SpeakerName IN ('Stock Workshop')
			AND r.TourCode <> 'MG150921-CHIC-CHICAGOSTOCK'
	
EOD;
//	$databaseResult = mssql_fetch_assoc(mssql_query($sql));
	$databaseResult = $database->fetchAll($sql);
	

/********************************************************************
* last element in array used to copy doug and mariah on the txt msgs
********************************************************************/
        $lastElem = end($databaseResult);
//        $databaseResult = array();
        $temp1 = $lastElem;
        $temp2 = $lastElem;
        $temp3 = $lastElem;
        $temp1['CellPhone'] = '8018367839'; //brad stewart
        $temp2['CellPhone'] = '8013605884'; //mariah
        array_push($databaseResult, $temp1);
        array_push($databaseResult, $temp2);
/*******************************************************************/

//error_log(print_r($databaseResult, true));
//die;

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
	
		//echo $mName;
		$cont = new TxtMsgContent();
	        $msg = $cont->getContent($row, 'stockReminder');
	
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

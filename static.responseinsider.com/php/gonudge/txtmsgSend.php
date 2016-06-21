<?php
include("../MiscClasses/TxtMsgContent.php");
//Database connection info for local database
$db_host = "OR-TSSQL01.response.corp";
$db_user = "dwWEIci16rGByGw8";
$db_pass= "R22tCY2kkK8UguFU";
$database = "DBA";

$dbhandle = mssql_connect($db_host, $db_user, $db_pass) or die("could not connect to sql server on $serverName");
$selected = mssql_select_db($database, $dbhandle) or die("could not connect to database $database");

$user = "RMG";

//GoNudge-Zipligo connection credentials. 
	//$_WSDL_URI = "https://www.gonudge.com/GoNudgeSMS.svc?wsdl";
	$_WSDL_URI = "https://portal.ziplingo.com/GoNudgeSMS.svc?wsdl";
	
	$postVars = $_REQUEST;
        $client = new SoapClient($_WSDL_URI, array('trace' => true));
        $arrParameters_Login = array('username' => 'apiuser', 'password' => 'apiuser1234');
        $securityTokenObj = $client->GetSecurityToken($arrParameters_Login);
        $token = $securityTokenObj->GetSecurityTokenResult;
        //$result = $soap->QueueMessageForDelivery();

    // Setting message varibale from tourcode and message name
	$tcode = $_REQUEST['tourcode'];
    $mType = '';
    $rsend = $_REQUEST['resend'];
    $mName = $_REQUEST['msgname'];
    $rId = $_REQUEST['regid'];
    
//switch statement to populate the message name for the outgoing message content.
    switch ($mName)
    {
    	case "conf":
    		$mType = "confirmation";
    	break;
    	case "rweekday":
    		$mType = "reminderWeekday";
    	break;
    	case "rweekend":
    		$mType = "reminderWeekend";
    	break;
    	case "conline":
    		$mType = "onlineConf";
    	break;
    	case "resend":
    		$mType = "resend";
    	break;
    	case "cstockwork":
    		$mType = "stockConf";
    	break;
    	case "rstockwork":
    		$mType = "stockReminder";
    	break;
    	case "crework":
    		$mType = "reConf";
    	break;
    	case "rrework":
    		$mType = "reReminder";
    	break;
    	case "ddvideo":
    		$mType = "doortodoorVideo";
    	break;
    	default:
    		$mType = "confirmation";
    	break;
    }
    
        
    // Parameters for composing content and type of message to send.    
	$phoneNumber = $postVars['phone'];
	$fname = $postVars['Fname'];
	$lname = $postVars['Lname'];
	$cont = new TxtMsgContent();
	$content = $cont->getEvent($postVars);
	$content['Fname'] = $fname;
	$msg = $cont->getContent($content, $mType);
	
	//echo $mName.' and '.$mType. ' msg  '.$msg.'  ';

	if(!empty($phoneNumber)){
	        $me = new SMSRecipient();
		$me->ContactId = '';
		$me->Email = '';
		$me->FirstName = $fname;
		$me->IsValid = False;
		$me->IsWireless = IsWireless::Unknown;
		$me->LastName = '';
		$me->MessageId = 0;
		$me->MobilePhoneNumber = $phoneNumber;
		$me->Status = '';
		$me->Drilldown = '';
		$me->ErrorDescription = '';
		$me->Id = 0;
		$me->ResultCode = ResultCode::Success;

		$arrParameters_QueueMessageForDelivery = array(
			'smsMessage' => $msg, 
	       		'recipients' => array($me), 
			'token' => $token,
			'deliveryMethod' => DeliveryMethod::ShortCode,
			'skipWelcome' => TRUE); 
		$sendResult = $client->QueueMessageForDelivery3($arrParameters_QueueMessageForDelivery);
		echo "Success";
		$res = "Success";
    	}else{
		echo "Phone Number Empty";
		$res = "Phone Number Empty";
	}
	
	$sql =<<<EOD
INSERT INTO SMSPostResult (RegID, PostResult, MessageName, APIuser)
VALUES ('$rId','$res', '$mType', '$user')
EOD;
	mssql_query($sql);    

// Helper code
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

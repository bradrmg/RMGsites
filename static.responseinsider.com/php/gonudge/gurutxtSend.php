<?php
include("../MiscClasses/TxtMsgContent.php");
// SAMPLE POST STRING FOR TESTING
// http://pl-rmgstaticws01.response.corp/php/gonudge/gurutxtSend.php?phone=8018367839&tourcode=CON160422B&msgname=conf&regid=1234&brand=sy
// END SAMPLE STRING

//Database connection info for local database
	$db_host = "OR-TSSQL01.response.corp";
	$db_user = "dwWEIci16rGByGw8";
	$db_pass= "R22tCY2kkK8UguFU";
	$database = "DBA";
	$dbhandle = mssql_connect($db_host, $db_user, $db_pass) or die("could not connect to sql server on $serverName");
	$selected = mssql_select_db($database, $dbhandle) or die("could not connect to database $database");

// STARTING VARIABLES
	$user = "RMG";
	$postVars = $_REQUEST;
	$senderid = "";
	$phone = $postVars['phone'];

// GoNudge-Zipligo switching connection credentials based on guru/offername.
	$brand = $_REQUEST['brand'];
		switch  ($brand)
		{
			case "sy":
				$uname = "rmgyancey";
				$pword = "abc123";
				$senderid = "MG480e8fda3f96cae7aa3c4c322aea08f3";
				break;
			default:
				$uname = "apiuser";
				$pword = "apiuser1234";
			break;
		}

		$_WSDL_URI = "https://portal.ziplingo.com/GoNudgeSMS.svc?wsdl";
		$client = new SoapClient($_WSDL_URI, array('trace' => true));
		$arrParameters_Login = array('username' => $uname, 'password' => $pword);
		$securityTokenObj = $client->GetSecurityToken($arrParameters_Login);
		$token = $securityTokenObj->GetSecurityTokenResult;
/// END OF CONNECTION PARAM AND SETUP		
		
// CHECK TO VALIDATE THAT THE PHONE NUMBER PASSED IS WIRELESS
		$ptocheck = array ('mobilePhone' => $phone, 'token' => $token);
		$phonecheck = $client->IsWireless($ptocheck);
		$phonepass = $client->__getLastResponse();
		var_dump($phonepass);
		
		if ($phonepass == TRUE){
			$valid = 1;
		}
		else {
			$valid = 0;
			$res = "Failed: Not Wireless";
		}
	
		
		echo $valid;

// Setting message varibale from tourcode and message name
	$tcode = $_REQUEST['tourcode'];
    $mType = '';
    $rsend = $_REQUEST['resend'];
    $mName = $_REQUEST['msgname'];
    $rId = $_REQUEST['regid'];
    
//	Switch statement to populate the message name for the outgoing message content.
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
	$phoneNumber = $phone;
	$fname = $postVars['Fname'];
	$lname = $postVars['Lname'];
	$cont = new TxtMsgContent();
	$content = $cont->getEvent($postVars);
	$content['Fname'] = $fname;
	$msg = $cont->getContent($content, $mType);
	

	if($valid == 1){
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
			'deliveryMethod' => DeliveryMethod::SMS,
			'skipWelcome' => TRUE,
			'FromNumber' => $senderid); 
		$sendResult = $client->QueueMessageForDelivery7($arrParameters_QueueMessageForDelivery);
		echo "Success";
		$res = "Success";
    	}else{
		echo "Failed: Not Wireless";
		$res = "Failed: Not Wireless";
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
				const SMS  = 'SMS';
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

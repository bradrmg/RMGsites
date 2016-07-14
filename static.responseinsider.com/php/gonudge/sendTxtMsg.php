<?php
include("../MiscClasses/TxtMsgContent.php");
	//$_WSDL_URI = "https://www.gonudge.com/GoNudgeSMS.svc?wsdl";
	$_WSDL_URI = "https://portal.ziplingo.com/GoNudgeSMS.svc?wsdl";
	
	$postVars = $_REQUEST;
        $client = new SoapClient($_WSDL_URI, array('trace' => true));
        $arrParameters_Login = array('username' => 'apiuser', 'password' => 'apiuser1234');
        $securityTokenObj = $client->GetSecurityToken($arrParameters_Login);
        $token = $securityTokenObj->GetSecurityTokenResult;
        //$result = $soap->QueueMessageForDelivery();

    // Setting message name from tourcode passed
	$tcode = $_REQUEST['tourcode'];
    $mType = '';
    $rsend = $_REQUEST['resend'];

    //case "reminderWeekday";
    //$mType = "reminderWeekday";
    //break;
    //case "reminderWeekend";
    //$mType = "reminderWeekend";
    //break;
    if($rsend <> 1){
    	die('Can only Resend');
    }
    
    if($rsend == 1)
    {
    	$mType = "resend";
    }
    //elseif ()
    else 
    {
    	switch ($tcode)
    	{
    		case "ATL151219A";
    		case "ATL151220A";
    			$mType = "reminderDBS";
    		break;
    		default;
    			$mType = "confirmation";
    		break;
    	}
    }
   
    
        
    // Parameters for composing content and type of message to send.    
	$phoneNumber = $postVars['phone'];
	$fname = $postVars['Fname'];
	$lname = $postVars['Lname'];
	$cont = new TxtMsgContent();
	$content = $cont->getEvent($postVars);
	$content['Fname'] = $fname;
	$msg = $cont->getContent($content, $mType);

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
    	}else{
		echo "Phone Number Empty";
	}
    

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

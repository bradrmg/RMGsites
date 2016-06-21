<?php
include("../MiscClasses/TxtMsgContent.php");
	$_WSDL_URI = "https://portal.ziplingo.com/GoNudgeSMS.svc?wsdl";
	$postVars = $_REQUEST;
        $client = new SoapClient($_WSDL_URI, array('trace' => true));
        $arrParameters_Login = array('username' => 'apiuser', 'password' => 'apiuser1234');
        $securityTokenObj = $client->GetSecurityToken($arrParameters_Login);
        $token = $securityTokenObj->GetSecurityTokenResult;
        //$result = $soap->QueueMessageForDelivery();

    //This is the key as to which content type or message is going to be sent.
    $msgName = $_REQUEST['msg'];
    
    //List-Array of valid message names
    $msgList = array(
    	'reminderWeekend',
    	'reminderWeekday',
    	'confirmation',
    	'cNAREI',
    	'rNAREI',
    	'resend'
    );
    
    //Message name check
    if (in_array($msgName, $msgList)){
    	//echo 'Valid Message Name';
    	$msgPass = 'pass';
    }
    else {
    	//echo 'That message name does not exist dummy';
    	$msgPass = 'fail';
    }

    
    If ($msgPass == 'fail'){
    	echo $msgName.' is not a valid message name';
    }
    else {
    	$phoneNumber = $postVars['phone'];
    	$fname = $postVars['Fname'];
    	$lname = $postVars['Lname'];
    	$cont = new TxtMsgContent();
    	$content = $cont->getEvent($postVars);
    	$content['Fname'] = $fname;
    	$msg = $cont->getContent($content, $msgName);
    	
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
    		$sendResult = $client->QueueMessageForDelivery7($arrParameters_QueueMessageForDelivery);
    		echo "Success";
    	}else{
    		echo "Phone Number Empty";
    	}
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

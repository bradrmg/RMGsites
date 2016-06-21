<?php
include("../MiscClasses/TxtBlastContent.php");
	$_WSDL_URI = "https://portal.ziplingo.com/GoNudgeSMS.svc?wsdl";
	$postVars = $_REQUEST;
        $client = new SoapClient($_WSDL_URI, array('trace' => true));
        $arrParameters_Login = array('username' => 'apiuser', 'password' => 'apiuser1234');
        $securityTokenObj = $client->GetSecurityToken($arrParameters_Login);
        $token = $securityTokenObj->GetSecurityTokenResult;
        //$result = $soap->QueueMessageForDelivery();

	$phoneNumber = $postVars['phone'];
	$fname = $postVars['Fname'];
	$lname = $postVars['Lname'];
	$city = $postVars['City'];
	$cont = new TxtBlastContent();
	$content = $cont->getEvent($postVars);
	$content['Fname'] = $fname;
	$msg = $cont->getContent($content, 'v2');
	$fromnum = $postVars['fnumber'];

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
			'deliveryMethod' => DeliveryMethod::SMS,
			'skipWelcome' => TRUE,
		    'FromNumber' => '+1'.$fromnum);
		$sendResult = $client->QueueMessageForDelivery7($arrParameters_QueueMessageForDelivery);
		echo "Success";
    	}else{
		echo "Phone Number Empty";
	}
	
	//print_r(array_values($arrParameters_QueueMessageForDelivery));
	//Show results
	//echo "<pre>";
	//print_r($arrParameters_QueueMessageForDelivery);
	//echo "</pre>";
	
    

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
				public $FromNumber;
				public $City;
			} 

?>
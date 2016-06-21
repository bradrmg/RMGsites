<?php
//include("../MiscClasses/TxtMsgContent.php");
class Validate{
	public $res = "working";
//Database connection info for local database
//$db_host = "OR-TSSQL01.response.corp";
//$db_user = "dwWEIci16rGByGw8";
//$db_pass= "R22tCY2kkK8UguFU";
//$database = "DBA";

//$dbhandle = mssql_connect($db_host, $db_user, $db_pass) or die("could not connect to sql server on $serverName");
//$selected = mssql_select_db($database, $dbhandle) or die("could not connect to database $database");



	public function phoneCheck($phone){

		$uname = "apiuser";
		$pword = "apiuser1234";
		$_WSDL_URI = "https://portal.ziplingo.com/GoNudgeSMS.svc?wsdl";
		
		$client = new SoapClient($_WSDL_URI, array('trace' => true));
		$arrParameters_Login = array('username' => $uname, 'password' => $pword);
		$securityTokenObj = $client->GetSecurityToken($arrParameters_Login);
		$token = $securityTokenObj->GetSecurityTokenResult;
		//$result = $soap->QueueMessageForDelivery();

		$ptocheck = array ('mobilePhone' => $phone, 'token' => $token);
		$phonecheck = $client->IsWireless($ptocheck);
		$result = $client->__getLastResponse();
		$res = $result;
		
		return $res;
	}
	
}
	
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

<?php
include("../MiscClasses/TxtMsgContent.php");
	$_WSDL_URI = "https://portal.ziplingo.com/GoNudgeSMS.svc?wsdl";
	$postVars = $_REQUEST;
        $client = new SoapClient($_WSDL_URI, array('trace' => true));
        $arrParameters_Login = array('username' => 'apiuser', 'password' => 'apiuser1234');
        $securityTokenObj = $client->GetSecurityToken($arrParameters_Login);
        $token = $securityTokenObj->GetSecurityTokenResult;
        //$result = $soap->QueueMessageForDelivery();

$list = array();

$list = $client->GetRecentlySentMessages($token);

//echo "<pre>";
//print_r($list);
//echo "</pre>";

foreach ($list as $key => $entry)
{
	print $key . "=" . $entry . "<br>";
}
echo"</pre>";


?>
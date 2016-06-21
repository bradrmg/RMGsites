<?php

//ini_set('display_errors','on');
//error_reporting(E_ALL);

require_once "Mail.php";
require_once "Mail/mime.php";

// $from = "autoreporting@responsemg.com";
// $to = "tkanagala@responsemg.com";
 $from = "Auto Reporting <autoreporting@responsemg.com>";
 $to = "Doug Long <dlong@responsemg.com>";
// $to = "Tarun Kanagala <tkanagala@responsemg.com>";

 $subject = "Missing Fields in vip stuff";
 $html = "<html><body>hi how are you? the following data is what i got... think somethign is missing in here... check it out <br/><br/>";
$urlVals = "";
foreach($_REQUEST as $key => $val){
	$html .= $key . " => " . $val . "<br/>";
	$urlVals .= "&$key=$val";
}
$urlVals = substr($urlVals, 1);
$html .= "<a href=\"http://pl-rmgstaticws01.blooint.com/html/winadvantageCorrections.php?$urlVals\">Click here to do the corrections</a>";
$html .= "</body></html>";


// Creating the Mime message
$mime = new Mail_mime();

// Setting the body of the email
$mime->setHTMLBody($html);
// Set body and headers ready for base mail class
$body = $mime->get();


 $host = "smtp.collaborationhost.net";
 $port = "587";
 $username = "autoreporting@responsemg.com";
 $password = "St3wi3123";

 $headers = array ('From' => $from,
   'To' => $to,
   'Subject' => $subject);

$headers = $mime->headers($headers);

 $smtp = Mail::factory('smtp',
   array ('host' => $host,
     'port' => $port,
     'auth' => true,
     'username' => $username,
     'password' => $password
   )
 );

 $mail = $smtp->send($to, $headers, $body);

 if (PEAR::isError($mail)) {
   echo("Error sending mail to doug long: <p>" . $mail->getMessage() . "</p>");
  } else {
   echo("<p>Send Email to Doug Long Successfully</p>");
  }

?>

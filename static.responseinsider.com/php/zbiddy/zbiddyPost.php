<?php


$vars = "";
//$postUrl = $_REQUEST[''];
unset($_REQUEST['CompanyID']);
unset($_REQUEST['ApiID']);
$postVars = $_REQUEST;
$vars = "uid=responsemg&pass=iab-3{z5-[wo{6b1?lcf";
$rid = ""; //these two are special case variables, we need to combine these two and send it as a json object under upscaleval variable
$prodId = ""; //this gets its data from upsaleval variable for now... then the upsaleval variable gets reset
foreach($postVars as $key => $value){
        switch($key){
                case "upscale":
                        if($value == 3){
				echo "this is a unable to contact customer so not sending lead.";
				die;
			}
			$vars.= "&" . $key . "=" . urlencode($value);
			break;
		case "upsaleval":
			$prodId = $value;
			break;
		case "rid":
			$rid = $value;
			break;
                default:
                        $vars.= "&" . $key . "=" . urlencode($value);
                        break;
        }
}
if(strpos($prodId, ",") == true){
	$prodId = explode(',', $prodId);
}else{
	$prodId = array($prodId);
}

$tempArr = array(
		"rid" => $rid,
		"prod_id" => $prodId
	);
$vars .= "&upsaleval=" . json_encode($tempArr);

//error_log($vars);
//echo "this is what im sending them: ";
//echo $vars;

//$result = post_content("http://testapi.zbiddy.com/api/leads/ack", count($postVars), $vars, 'responsemg', 'iab-3{z5-[wo{6b1?lcf');
$result = post_content("https://leadsapi.zbiddy.com/api/leads/ack", count($postVars), $vars, 'responsemg', 'iab-3{z5-[wo{6b1?lcf');
$result = json_decode($result);

//error_log(print_r($result, true));



if(isset($result->success)){
	echo $result->success;
}elseif(isset($result->error)){
	echo $result->error . " error reason: " . $result->errorDesc;
}






$db_host = "OR-TSSQL01.response.corp";
$db_user = "dwWEIci16rGByGw8";
$db_pass= "R22tCY2kkK8UguFU";
$database = "LEADS";


$dbhandle = mssql_connect($db_host, $db_user, $db_pass) or die("could not connect to sql server on $serverName");
$selected = mssql_select_db($database, $dbhandle) or die("could not connect to database $database");


$sql = "INSERT INTO LEADS.dbo.ZbiddyPostResult ";
$sql .= "(";
$sqlValues = "";

foreach($result as $key => $val){
	switch($key){
		case "success":
			$sql .= "success" . ",";
                        $sqlValues .= "'" . $result->success . "',";
                        break;
		case "error":
			$sql .= "error,errorDesc,";
                        $sqlValues .= "'" . $result->error . "','" . $result->errorDesc . "',";
                        break;
		case "request":
			(array)$tempVal = $val;
                        foreach($tempVal as $k=>$v){
				switch($k){
					case "uid":
					case "pass":
						break;
					default:
						$sql .= $k . ",";
		                                $sqlValues .= "'" . $v . "',";
						break;
				}
                        }
                        break;
	}
}






$sql = substr($sql, 0, strlen($sql)-1);
$sqlValues = substr($sqlValues, 0, strlen($sqlValues)-1);;

$sql .= ") ";
$sql .= "VALUES(" . $sqlValues . ")";
//error_log($sql);
mssql_query($sql);

//error_log(preg_replace("/[\t\r\n]+/", " ", $sql));
//echo " this is the sql statement im using to write stuff to the database: " . $sql;

function post_content($url,$nfields,$fields_string, $uname, $pass)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
	//show header and verbose
//    curl_setopt($ch, CURLOPT_VERBOSE, '1');
//    curl_setopt($ch, CURLOPT_HEADER, true);

    curl_setopt($ch,CURLOPT_POST,$nfields);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
    curl_setopt($ch, CURLOPT_USERPWD, 'responsemg:iab-3{z5-[wo{6b1?lcf') ;
    //curl_setopt($ch, CURLOPT_HEADER, 1);
    //curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; MSIE 7.0; Windows NT 6.0; en-US)');
   $data = curl_exec($ch);
    if (curl_errno($ch)) {
        return "Error: " . curl_error($ch);
    } else {
        // Show me the result
        return $data;
        curl_close($ch);
    }



    /*ob_start();
    curl_exec ($ch);
    curl_close ($ch);
    $string = ob_get_contents();
    ob_end_clean();
    return $string;*/
}

?>

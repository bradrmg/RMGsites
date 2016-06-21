<?php
function curl_post($url,$nfields,$fields_string)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST,$nfields);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
    //curl_setopt($ch, CURLOPT_HEADER, 1);
    //curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; MSIE 7.0; Windows NT 6.0; en-US)');
    ob_start();
    curl_exec ($ch);
    curl_close ($ch);
    $string = ob_get_contents();
    ob_end_clean();
    return $string;
}


function curl_get($url,$nfields,$fields_string)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url . '?' . $fields_string);
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}
?>

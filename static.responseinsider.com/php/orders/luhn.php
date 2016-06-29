<?php 

//$ccnum = "8111111111111111";
//$ccvalid = is_valid_Luhn("4111111111111111");

//var_dump($ccvalid);
/*
if ($ccvalid == true){
	echo "Valid";
}
else {
	echo "Not Valid";
}
*/

function is_valid_luhn($number) {
	settype($number, 'string');
	$sumTable = array(
			array(0,1,2,3,4,5,6,7,8,9),
			array(0,2,4,6,8,1,3,5,7,9));
	$sum = 0;
	$flip = 0;
	for ($i = strlen($number) - 1; $i >= 0; $i--) {
		$sum += $sumTable[$flip++ & 0x1][$number[$i]];
	}
	return $sum % 10 === 0;
}





?>
<?php

echo "this is your data you posted to me: <br/>";
echo "<pre>";
print_r($_REQUEST);
echo "</pre>";

echo "This is second: <br/>";
foreach ($_REQUEST as $key => $entry)
{
	print $key . "=" . $entry . "<br>";
}
echo"</pre>";

$p = $_REQUEST['phone'];
echo $p;

echo "This is third: <br/>";
printArray($_REQUEST);
function printArray($array){
	foreach ($array as $key => $value){
		echo "$key => $value";
		if(is_array($value)){ //If $value is an array, print it as well!
		printArray($value);
		}
		}
		}


//$new = implode (",",$_REQUEST);

print_r(json_encode($_REQUEST));



//$insertstring = ($_REQUEST);
//print_r($insertstring);

//header( 'Location: closewindow.html' ) ;
?>

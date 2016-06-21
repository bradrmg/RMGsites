<?php
echo "done";
/*
$db_host = "OR-TSSQL01.response.corp";
$db_user = "dwWEIci16rGByGw8";
$db_pass= "R22tCY2kkK8UguFU";
$database = "DBA";

//$dbhandle = mssql_connect($db_host, $db_user, $db_pass) or die("could not connect to sql server on $serverName");
//$selected = mssql_select_db($database, $dbhandle) or die("could not connect to database $database");

$template = $_REQUEST['Template'];
$tourcode = $_REQUEST['TourCode'];
$emailRequest = $_REQUEST['Email'];
$fname = $_REQUEST['Fname'];

$location = $_REQUEST['Location'];
$eventdate = $_REQUEST['Date'];
$eventtime = $_REQUEST['Time'];
$eventadd = $_REQUEST['Address'];
//$eventstate = $_REQUEST['State'];
//$eventcity = $_REQUEST['City'];


$uri = 'https://mandrillapp.com/api/1.0/messages/send-template.json';

$postString = '{
    "key": "wJf2fer91Ek7GHpx8Ckk0A",
    "template_name": "'.$template.'",
    "template_content": [
        {
            "name": "example name",
            "content": "example content"
        }
    ],
    "message": {
        "to": [
            {
                "email": "'.$emailRequest.'",
                "name": "'.$fname.'"
            }
        ],
        "headers": {
        },
        "track_opens": true,
        "track_clicks": true,
        "auto_text": null,
        "auto_html": null,
        "inline_css": true,
        "url_strip_qs": null,
        "preserve_recipients": null,
        "tracking_domain": null,
        "signing_domain": null,
        "merge": true,
        "merge_vars": [
            {
                "rcpt": "'.$emailRequest.'",
                "vars": [
                    {
                        "name": "venue_location",
                        "content": "'.$location.'"
                    },
                    {
                        "name": "venue_date",
                        "content": "'.$eventdate.'"
                    },
                    {
                        "name": "venue_time",
                        "content": "'.$eventtime.'"
                    },
                    {
                        "name": "venue_address",
                        "content": "'.$eventadd.'"
                    },
				    {
                        "name": "venue_city",
                        "content": " "
                    },
                    {
                        "name": "venue_state",
                        "content": " "
                    },
                    {
                        "name": "venue_zip",
                        "content": " "
                    }
                ]
            }
        ],
        "tags": [
            "confirmation"
        ]
    },
    "async": false
}';


//echo $postString;


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $uri);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);

$result = curl_exec($ch);

echo $result;

*/

      
?>

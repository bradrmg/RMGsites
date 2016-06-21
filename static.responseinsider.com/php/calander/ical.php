<?php
header('Content-Type: text/calendar;name="meeting.ics";method=PUBLISH');
$from_address = "myname@mydomain.com";
$meeting_subject = "Seminar Reservation"; //Doubles as email subject and meeting subject in calendar
$meeting_description = "Here is a brief description of my meeting";
$meeting_location = "Park Hyatt, San Francisco"; //Where will your meeting take place
$meeting_date = '2011-11-25';
$meeting_duration = 3600;

//Convert MYSQL datetime and construct iCal start, end and issue dates
$meetingstamp = strtotime($meeting_date . " UTC");    
$dtstart= gmdate("Ymd\THis\Z",$meetingstamp);
$dtend= gmdate("Ymd\THis\Z",$meetingstamp+$meeting_duration);
$todaystamp = gmdate("Ymd\THis\Z");

//Create unique identifier
$cal_uid = date('Ymd').'T'.date('His')."-".rand()."@mydomain.com";

$ical = 'BEGIN:VCALENDAR
PRODID:-// Seminar Confirmation //EN
VERSION:2.0
METHOD:PUBLISH
BEGIN:VEVENT
ORGANIZER:MAILTO:' . $from_address . '
DTSTART:' . $dtstart . '
DTEND:' . $dtend . '
LOCATION:' . $meeting_location . '
TRANSP:OPAQUE
SEQUENCE:0
UID:' . $cal_uid . '
DTSTAMP:' . $todaystamp . '
DESCRIPTION:' . $meeting_description . '
SUMMARY:' . $meeting_subject . '
ATTENDEE;CUTYPE=INDIVIDUAL;ROLE=REQ-PARTICIPANT;PARTSTAT=ACCEPTED;CN=' . $from_address . ':mailto:' . $from_address . '
PRIORITY:5
CLASS:PUBLIC
END:VEVENT
END:VCALENDAR'; 
echo $ical;
?>


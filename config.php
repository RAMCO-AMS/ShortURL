<?php
// PEM file is the cert file I use to validate certificate authenticity.
const PEM_FILE = 'cacert.pem';

// API URL is the same for every single API v2 request.
const API_URL = 'https://api.ramcoams.com/api/v2/';

// This is a fake, non-working API key, yours has to be substituted here.
const API_KEY = 'NAR-Stage-Fake-11bd0b401ae37118509d99949587f3ec0122e3de';

//update to your portal links
const CLASS_URL = "https://marealtorportal.ramcoams.net/Education/Registration/Details.aspx?cid=";
const MEETING_URL = "https://marealtorportal.ramcoams.net/Meetings/Registration/MeetingDetails.aspx?mid=";


//this project is maintained here:
//https://github.com/RAMCO-AMS/Mobile-Checkin
const MOBILE_CHECKIN_LINK = "http://localhost:8888/ramco/mobile-checkin/mobile_checkin.php";


date_default_timezone_set('America/New_York');

error_reporting(E_ALL);
ini_set("display_errors", 1);

?>

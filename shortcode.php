<?php
  /**
  * API Written by Jason Normandin and Mark Lesswing
  * Class Design  Dave Conroy
  * Inspired by Luis Pena V1-API Class
  * dconroy@marealtor.com March 2015
  * API Doc URL : https://api.ramcoams.com/api/v2/ramco_api_v2_doc.pdf
  */

require_once 'config.php';
require_once 'RamcoAPI.php';

$api_config = array("key" => API_KEY,
                   "url" => API_URL,
                   "cert" => PEM_FILE,
                   "timezone_offset" => '+4 Hours'); //Eastern is GMT-4, this cancels out the time difference
       
//create our class       
$RamcoAPI = new RamcoAPI($api_config);

// only uncomment if you need to clear the cache one time after adding a custom field
//$RamcoAPI->clearVars();
//$RamcoAPI->setOperation("clearCache");
//$json = $RamcoAPI->sendMessage();


//set our time window to check, I am going back two days here
$server_time= new DateTime('NOW'); //GMT
$modified = $server_time->modify('-2 days');  // Get Records Modified in Past 2 Days
$modified = $modified->modify($RamcoAPI->getTimezoneOffset());  //Cancel out Timezone Offset


//Generate Shortcodes for all meetings created in the past 2 days
$RamcoAPI->clearVars();
$RamcoAPI->setOperation("GetEntities");
$RamcoAPI->setEntity("cobalt_meeting");
$RamcoAPI->setAttributes("cobalt_name,cobalt_meetingId,modifiedon,MAR_short_url,MAR_checkinlink");
//$RamcoAPI->setMaxResults("300");
$RamcoAPI->setFilter("statecode<eq>0 and ModifiedOn<ge>".$modified->format(DateTime::RFC3339));
$json = $RamcoAPI->sendMessage();
$meetings = json_decode($json,true);


if (isset($meetings["Data"])){
 for ($i=0;$i<sizeof($meetings["Data"]);$i++){
        if (!isset($meetings["Data"][$i]["MAR_short_url"])){
          
            $guid=$meetings['Data'][$i]['cobalt_meetingId'];
            $shorturl=shortURL(MEETING_URL.$guid);
            $RamcoAPI->clearVars();
            $RamcoAPI->setOperation("UpdateEntity");
            $RamcoAPI->setGUID($guid);
            $RamcoAPI->setEntity("cobalt_meeting");
            $RamcoAPI->setAttributeValues("MAR_short_url=$shorturl");
            $json = $RamcoAPI->sendMessage();
            $meeting_result = json_decode($json,true);
            
        
        }
        
        
         if (!isset($meetings["Data"][$i]["MAR_CheckInLink"])){
          
            $guid=$meetings['Data'][$i]['cobalt_meetingId'];
            $shorturl_mobile=shortURL(MOBILE_CHECKIN_LINK."?guid=".$guid."&type=meeting");
            echo "<br>Short Mobile Checkin link: $shorturl_mobile  <br>";
            $RamcoAPI->clearVars();
            $RamcoAPI->setOperation("UpdateEntity");
            $RamcoAPI->setGUID($guid);
            $RamcoAPI->setEntity("cobalt_meeting");
            $RamcoAPI->setAttributeValues("MAR_checkinlink=$shorturl_mobile");
            $json = $RamcoAPI->sendMessage();
            $meeting_result = json_decode($json,true);
        
        }
  }
 
 
 }
 
 

 //Generate Shortcodes registration link for all classes created in the past 2 days
$RamcoAPI->clearVars();
$RamcoAPI->setOperation("GetEntities");
$RamcoAPI->setEntity("cobalt_class");
$RamcoAPI->setAttributes("cobalt_name,cobalt_classId,modifiedon,MAR_short_url,MAR_checkinlink");
//$RamcoAPI->setMaxResults("300");
$RamcoAPI->setFilter("statecode<eq>0 and ModifiedOn<ge>".$modified->format(DateTime::RFC3339));
$json = $RamcoAPI->sendMessage();
$classes = json_decode($json,true);


if (isset($classes["Data"])){
 for ($i=0;$i<sizeof($classes["Data"]);$i++){
        if (!isset($classes["Data"][$i]["MAR_short_url"])){
          
            $guid=$classes['Data'][$i]['cobalt_classId'];
            $shorturl=shortURL(CLASS_URL.$guid);
            $RamcoAPI->clearVars();
            $RamcoAPI->setOperation("UpdateEntity");
            $RamcoAPI->setGUID($guid);
            $RamcoAPI->setEntity("cobalt_class");
            $RamcoAPI->setAttributeValues("MAR_short_url=$shorturl");
            $json = $RamcoAPI->sendMessage();
            $class_result = json_decode($json,true);
           
        
        }
        
         if (!isset($classes["Data"][$i]["MAR_checkinlink"])){
            echo "wheres my checkin";
            $guid=$classes['Data'][$i]['cobalt_classId'];
            
            $shorturl_mobile=shortURL(MOBILE_CHECKIN_LINK."?guid=".$guid."&type=class");
            echo "<br>Short Mobile Checkin link: $shorturl_mobile  <br>";
            $RamcoAPI->clearVars();
            $RamcoAPI->setOperation("UpdateEntity");
            $RamcoAPI->setGUID($guid);
            $RamcoAPI->setEntity("cobalt_class");
            $RamcoAPI->setAttributeValues("MAR_checkinlink=$shorturl_mobile");
            $json = $RamcoAPI->sendMessage();
            $class_mobile_result = json_decode($json,true);
            pretty_print( $class_mobile_result);
        
        
        
        
         }
        
        
    }
}
 
 
 
// Output a message at the end of the file
echo "<br>End of file.<br />";


function shortURL($longURL){
    // This is the URL you want to shorten


// Get API key from : http://code.google.com/apis/console/
$apiKey = '';

$postData = array('longUrl' => $longURL, 'key' => $apiKey);

$jsonData = json_encode($postData);

$curlObj = curl_init();

curl_setopt($curlObj, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url?key='.$apiKey);
curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curlObj, CURLOPT_HEADER, 0);
curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
curl_setopt($curlObj, CURLOPT_POST, 1);
curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);

$response = curl_exec($curlObj);
//pretty_print ($response);
// Change the response json string to object
$json = json_decode($response);
curl_close($curlObj);

$short_url = $json->id;

//echo $short_url;
return $short_url;
    
}





?>

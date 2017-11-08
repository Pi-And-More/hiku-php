<?php
//
// This is a PHP implementation of the webhook for the hiku scan device
// http://hiku.us
// 
// You can find the API documentation on github:
// https://github.com/hikuinc/hiku_shared
// In the folder API, you can find the most recent version of this API
//
// This PHP code implements a webhook which can receive the 4
// different event types and then uses the other part of the API
// to update the shopping cart in the hiku app.
// Please note that at the time of writing, the hiku app is only
// available in the US iTunes store. Don't know about the Google app store.
// https://itunes.apple.com/us/app/hiku-mobile/id721935991?mt=8
// You can order the hiku online, for example at Amazon
//
// A more detailed explanation of this code can be found in several
// blog articles:
// https://piandmore.wordpress.com/tag/hiku/
// starting at the first article:
// https://piandmore.wordpress.com/2017/11/06/online-groceries-barcodes-and-hiku/
//

//
// Start by loading default variables for database, passwords etc
//
include("MyVARS.php");
include("CommonFunctions.php");

//
// When timestamping events, we want one time for all events in this 'beep' so
// save the time and create a string version which will be used in the database.
//
$currenttime = time();
$time = strftime("%Y-%m-%d %H:%M:%S",$currenttime);

//
// Save the body part of the POST
//
$postBody = file_get_contents('php://input');

//
// When we send an OK response, we can include some extra information
// which will be stored in the $replyData variable.
//
$replyData = array();

//
// We expect the body of the POST to our webhook to have content as explained
// in the API documentation
//
if (strlen($postBody)>0) {
  //
  // Convert the POST body into an array. We assume it is a json file
  //
  $hikuData = json_decode($postBody,true);
  //
  // If it is from hiku then it will start with a data section as describe in the API
  //
  if (isset($hikuData['data'])) {
    //
    // Move one layer down into the data section
    //
    $hikuData = $hikuData['data'];
    //
    // A properly formatted hiku json will have an eventId
    //
    if (isset($hikuData['eventId'])) {
      $eventId = $hikuData['eventId'];
      switch ($eventId) {
        case PRODUCT_ENTRY:
          //
          // We've received a PRODUCT_ENTRY 'beep' which we process in a function
          // We need 2 arrays as an answer so this is done through global variables
          // $beep and $replyData
          //
          hikuPRODUCT_ENTRY($hikuData);
          break;
        case EAN_NOT_FOUND:
        // #### BEGIN DEV
        case EAN_INPUT_MANUALLY:
        // #### END DEV
          //
          // We've received an EAN_NOT_FOUND 'beep' which we process in a function
          // We need 2 arrays as an answer so this is done through global variables
          // $beep and $replyData
          // During development/testing, you can also enter a EAN manually which
          // will be treated as an EAN_NOT_FOUND
          //
          hikuEAN_NOT_FOUND($hikuData);
          break;
        case DEVICE_BATTERY_LEVEL:
          //
          // We've received a DEVICE_BATTERY_LEVEL event which we process in a function
          // We need 2 arrays as an answer so this is done through global variables
          // $beep and $replyData
          //
          hikuDEVICE_BATTERY_LEVEL($hikuData);
          break;
        case DEVICE_REGISTERED:
          //
          // We've received a DEVICE_REGISTERED event which we process in a function
          // We need 2 arrays as an answer so this is done through global variables
          // $beep and $replyData
          //
          hikuDEVICE_REGISTERED($hikuData);
          break;
        default:
          $error = "HIKU:Undefined event id";
      }
    } else {
      //
      // If it was a properly formatted hiku json file, it would have a data section.
      // Apparently it doesn't so log an error
      //
      $eventId = 0;
      $error = "HIKU:No event id";
    }
  } else {
    //
    // If it was a properly formatted hiku json file, it would have a data section.
    // Apparently it doesn't so log an error
    //
    $eventId = 0;
    $error = "HIKU:Expected data field";
  }
} else {
  //
  // The webhook was called without any data in the POST body. This should not
  // happen under normal circumstances.
  //
  $eventId = 0;
  $error = "HIKU:Expected data in body";
}

//
// If we encountered an error, the $error variable will be set.
// We will send an error reply and log the error in the database.
//
if (isset($error)) {
  //
  // Log the error in the database
  //

  //
  // Send an error message as defined in the API
  //
  $json = array("response"=>array("status"=>"error","errMg"=>$error));
} else {
  //
  // Send an OK message with the data we gathered during processing
  //
  $json = array("response"=>array("status"=>"ok","data"=>$replyData));
}
//
// Whether it was a success or not, we send a JSON reply message.
//
$jsonstring = json_encode($json);
header('Content-type: application/json');
print $jsonstring;
?>

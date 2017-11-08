<?php
//
// The function library for the hiku-php implementation
//

//
// Define the different event triggers so we can use a name
// rather than a number
//
define("PRODUCT_ENTRY",1);
define("EAN_NOT_FOUND",2);
define("DEVICE_BATTERY_LEVEL",3);
define("DEVICE_REGISTERED",4);
define("EAN_INPUT_MANUALLY",5);

//
// During development this function will send a push message
// so that you can verify your hiku code works.
//
// #### BEGIN DEV
function sendPushMessage ($message) {

//
// Some function to send you a push message or some other way to notify you it worked
//

}
// #### END DEV

function hikuPRODUCT_ENTRY ($hikuData) {
  //
  // A PRODUCT_ENTRY event. This is triggered when hiku recognizes the code
  // you scan or when you select add a product in the app.
  //
  // The data received:
  // Parameter     Req/Opt  Type     Description/Value
  // eventId       Req      Int      1
  // eventDesc     Req      String   PRODUCT_ENTRY
  // token         Req      HString  Unique token identifying the user in the third party system
  // id            Req      Int      Shoplist entry id of the product
  // name          Req      UString  Product name/description
  // ean           Req      String   Barcode/EAN, variable length. Set to the EAN when the item was scanned, set to NULL otherwise.
  // audioPath     Req      String   URL to a WAV file containing the voice recording for playback.
  //                                 Set when voice entry was used, set to the empty string otherwise.
  // serialNumber  Opt      HString  hiku serial number, only present if a hiku device was used for product entry
  //
  global $replyData, $beep, $time;
  $ean = isset($hikuData['ean']) ? $hikuData['ean'] : "";
  
  //
  // This is where you do something with the scan received knowing the hiku recognized the product
  //
  
  $replyData['message'] = "Got it";
}

function hikuEAN_NOT_FOUND ($hikuData) {
  //
  // An EAN_NOT_FOUND event is send when the hiku scans a code it does not recognize
  //
  // Parameter    Req/Opt  Type     Description/Value
  // eventId      Req      Int      2
  // eventDesc    Req      String   EAN_NOT_FOUND
  // token        Req      HString  Unique token identifying the user in the third party system
  // eanNotFound  Req      String   Unrecognized barcode, variable length
  //
  global $replyData, $beep, $time;
  $ean = $hikuData['eanNotFound'];

  //
  // This is where you do something with the scan received knowing the hiku did not recognize the product
  //
  
  $replyData['message'] = "Found it in my database";
}

function hikuDEVICE_BATTERY_LEVEL ($hikuData) {
  //
  // A DEVICE_BATTERY_LEVEL event is send periodically and gives an update on the battery life of the device
  //
  // Parameter     Req/Opt  Type     Description/Value
  // eventId       Req      Int      3
  // eventDesc     Req      String   DEVICE_BATTERY_LEVEL
  // token         Req      HString  Unique token identifying the user in the third party system
  // batteryLevel  Req      String   Battery level, percentage from 0-100
  // serialNumber  Req      HString  hiku serial number
  //
  global $replyData, $time;
  
  //
  // This is the event telling you the battery level of a hiku of a user
  //
}

function hikuDEVICE_REGISTERED ($hikuData) {
  //
  // A DEVICE_REGISTERED event is send when a hiku successfully registers to an app
  //
  // Parameter     Req/Opt  Type     Description/Value
  // eventId       Req      Int      4
  // eventDesc     Req      String   DEVICE_REGISTERED
  // token         Req      HString  Unique token identifying the user in the third party system
  // serialNumber  Req      HString  hiku serial number
  //
  global $replyData, $time;

  //
  // This is the event that is triggered for new users. Save the user and it's token
  //
}
?>

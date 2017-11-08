<?php
//
// Set the location of your data directory
//
$dirData = "./";

//
// Retrieve the body part of the POST to our webhook
//
$postBody = file_get_contents('php://input');

//
// String with date/time for use in the filename
//
$time = strftime("%Y%m%d-%H.%M.%S");

//
// Save the body of the POST to a file
//
file_put_contents($dirData.$time.".body.txt",$postBody);

//
// Prepare a json string in the hiku format for ok
//
$json = array("response"=>array("status"=>"ok"));
$jsonstring = json_encode($json);

//
// Send the json file as response
//
header('Content-type: application/json');
print $jsonstring;
?>

<?php
 
//Make sure that it is a POST request.
if(strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') != 0){
    throw new Exception('Request method must be POST!');
}
 
//Make sure that the content type of the POST request has been set to application/json
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
if(strcasecmp($contentType, 'application/json') != 0){
    throw new Exception('Content type must be: application/json');
}
 
//Receive the RAW post data.
$content = trim(file_get_contents("php://input"));
 
//Attempt to decode the incoming RAW post data from JSON.
$decoded = json_decode($content, true);
 
//If json_decode failed, the JSON is invalid.
if(!is_array($decoded)){
    throw new Exception('Received content contained invalid JSON!');
}
 
//Process the JSON.
$code = $decoded['code'];
$command = $decoded['command'];
$doorState = getDoorState();

//exec("echo 'command sent: " . $command . ", door state: " . $doorState . "' >> log.log");

if ($code == "xxxx")
{//ok to proceed
    if ($command == "open" && $doorState == "closed")
    {
        exec("gpio write 1 0; sleep 1; gpio write 1 1; sleep 1;", $toggle_output, $toggle_return_var);
    }
    
    if ($command == "close" && $doorState =="open")
    {
        exec("gpio write 1 0; sleep 1; gpio write 1 1; sleep 1;", $toggle_output, $toggle_return_var);
    }
}

function getDoorState() {
        exec("gpio read 0", $output, $return_var);
        if (trim(implode(" ",$output)) == "0")
        {
          $doorState = "open";
        }
        elseif (trim(implode(" ",$output)) == "1") {
          $doorState = "closed";
        }
        else
        {
          $doorState = "unknown";
        }
        return $doorState;
}

?>

<?php

$token = $_POST['Token'];

$newToken = VerfiyToken($token);

if($newToken != false){
    echo json_encode(array("message" => "Token Refreshed", "token" => $newToken));
}else{
    http_response_code(401);
    echo json_encode(array("message" => "Token Not Refreshed"));
}

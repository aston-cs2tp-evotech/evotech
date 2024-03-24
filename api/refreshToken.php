<?php

if (!isset($_POST["Token"])) {
    http_response_code(418);
    echo json_encode(array("message" => "Invalid token specified"));
    exit();
}

$token = $_POST['Token'];

$newToken = VerfiyToken($token);

if($newToken != false){
    echo json_encode(array("message" => "Token Refreshed", "token" => $newToken));
}else{
    http_response_code(401);
    echo json_encode(array("message" => "Token Not Refreshed"));
}

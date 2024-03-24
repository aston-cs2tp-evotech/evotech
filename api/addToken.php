<?php

if (!isset($_POST["Token"])) {
    http_response_code(418);
    echo "Invalid token";
    return;
}
// only TokenAdminID is required for a valid response, as the 
// default values for the other fields don't terminate GenerateToken
$tokenName = (isset($_POST["TokenName"])) ? $_POST["TokenName"] : null;
$tokenAdmin = $_POST["Token"];
$tokenExp = isset($_POST["TokenExpiry"]) ? $_POST["TokenExpiry"] : null;

// Try get admin ID from token
$tokenAdmin = GetAdminByToken($tokenAdmin);

try {
    $tokenExp = new DateTime($tokenExp);
}
catch (Exception $e) {
    http_response_code(418);
    echo "Error parsing token expiry date"; 
    return;
}


//GenerateToken handles all the checks, no type checks needed
$tk = GenerateToken($tokenAdmin, $tokenExp,  $tokenName);

if (!empty($tk)) {
    $tkArr = array ("Token" => $tk);
    json_encode($tkArr);
}
else {
    http_response_code(418);
    echo "Error creating token";
}
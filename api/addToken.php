<?php
// only TokenAdminID is required for a valid response, as the 
// default values for the other fields don't terminate GenerateToken
$tokenName = $_SESSION["TokenName"];
$tokenAdmin = $_SESSION["TokenAdminID"];
$tokenExp = $_SESSION["TokenExpiry"];


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
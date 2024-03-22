<?php
// only TokenAdminID is required for a valid response, as the 
// default values for the other fields don't terminate GenerateToken
$tokenName = $_POST["TokenName"];
$tokenAdmin = $_POST["Token"];
$tokenExp = $_POST["TokenExpiry"];

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
<?php

if (!isset($_POST["removalToken"])) {
    http_response_code(418);
    echo "removalToken not specified";
    exit();
}

$token = $_POST["removalToken"];

$revoked = RevokeToken($token);

if ($revoked) {
    echo "Token " . $token . " has been revoked.";
}
else {
    http_response_code(418);
    echo "Error revoking token " . $token;
}
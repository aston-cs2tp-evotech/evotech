<?php

if (!isset($_POST["adminID"])) {
    http_response_code(418);
    echo json_encode(array("message" => "adminID not specified"));
    exit();
}

$adminID = $_POST['adminID'];

// Get admin details from database
$adminDetails = GetAdminByID($adminID);

$admin = array();
$admin['adminID'] = $adminDetails->getUID();
$admin['adminUsername'] = $adminDetails->getUsername();

echo json_encode($admin);
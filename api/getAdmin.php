<?php

$adminID = $_POST['adminID'];

// Get admin details from database
$adminDetails = GetAdminByID($adminID);

$admin = array();
$admin['adminID'] = $adminDetails->getUID();
$admin['adminUsername'] = $adminDetails->getUsername();

echo json_encode($admin);
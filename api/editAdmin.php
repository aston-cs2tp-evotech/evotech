<?php

$message = "";
$keys = array("adminID", "adminUsername", "adminPassword");

foreach ($keys as $key) {
    if (!isset($_POST[$key])) {
        $message = $key . " not specified";
        echo $message;
        exit();
    }
}

$adminID = $_POST['adminID'];
$adminUsername = $_POST["adminUsername"];
$adminPassword = $_POST["adminPassword"];

$updateList = array();
$updateList["AdminID"] = $adminID;

// Check if admin exists
$admin = GetAdminByID($adminID);

if ($admin == null) {
    $message = "Admin does not exist.";
    echo $message;
    exit();
}

// Compare all details to see what has changed
if ($admin->getUsername() != $adminUsername) {
    $updateList["Username"] = $adminUsername;
}

if ($adminPassword != "" && $admin->getPasswordHash() != password_hash($adminPassword, PASSWORD_DEFAULT)) {
    $updateList["PasswordHash"] = password_hash($adminPassword, PASSWORD_DEFAULT);
}

$result = UpdateAdminByAdmin($updateList);

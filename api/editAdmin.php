<?php

$adminID = $_POST['adminID'];
$adminUsername = $_POST["adminUsername"];
$adminPassword = $_POST["adminPassword"];

$message = "";
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

<?php

$adminUsername = $_POST["adminUsername"];
$adminPassword = $_POST["adminPassword"];

$message = "";
$details = array(
    "Username" => $adminUsername,
    "Password" => $adminPassword
);

$adminID = AddAdmin($details);

if ($adminID === "") {
    header("Location: /admin?addAdminSuccess= " . urlencode($message));
    exit();
} else {
    header("Location: /admin?addAdminError=" . urlencode($message));
    exit();
}
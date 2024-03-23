<?php
$message = "";

$keys = array("adminUsername", "adminPassword");

foreach ($keys as $key) {
    if (!isset($_POST[$key])) {
        $message = $key ." not specified";
        header("Location: /admin?addAdminError=" . urlencode($message));
        exit();
    }    
}

$adminUsername = $_POST["adminUsername"];
$adminPassword = $_POST["adminPassword"];

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
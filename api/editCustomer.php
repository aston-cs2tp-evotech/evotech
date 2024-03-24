<?php

$message = "";
$keys = array("customerID", "customerUsername", "customerEmail", "customerAddress", "customerPassword");

foreach ($keys as $key) {
    if (!isset($_POST[$key])) {
        $message = $key . " not specified";
        echo $message;
        exit();
    }
}

$customerID = $_POST['customerID'];
$customerUsername = $_POST["customerUsername"];
$customerEmail = $_POST["customerEmail"];
$customerAddress = $_POST["customerAddress"];
$custommerPassword = $_POST["customerPassword"];
$updateList = array();
$updateList["CustomerID"] = $customerID;
// Check if customer exists

$customer = GetCustomerByID($customerID);

if ($customer == null) {
    $message = "Customer does not exist.";
    echo $message;
    exit();
}

// Compare all details to see what has changed
if ($customer->getUsername() != $customerUsername) {
    $updateList["Username"] = $customerUsername;
}

if ($customer->getEmail() != $customerEmail) {
    $updateList["Email"] = $customerEmail;
}

if ($customer->getAddress() != $customerAddress) {
    $updateList["CustomerAddress"] = $customerAddress;
}

if ($custommerPassword != "" && $customer->getPasswordHash() != password_hash($custommerPassword, PASSWORD_DEFAULT)) {
    $updateList["PasswordHash"] = password_hash($custommerPassword, PASSWORD_DEFAULT);
}

$result = UpdateCustomerByAdmin($updateList);



echo $result;
<?php

if (!isset($_POST["customerID"])) {
    http_response_code(418);
    echo json_encode(array("message" => "customerID not specified"));
    exit();
}

$customerID = $_POST['customerID'];

// Get customer details from database
$customerDetails = GetCustomerByID($customerID);

$customer = array();
$customer['customerID'] = $customerDetails->getUID();
$customer['customerEmail'] = $customerDetails->getEmail();
$customer['customerUsername'] = $customerDetails->getUsername();
$customer['customerAddress'] = $customerDetails->getAddress();

echo json_encode($customer);
<?php

$customerID = $_POST['customerID'];

// Get customer details from database
$customerDetails = GetCustomerByID($customerID);

$customer = array();
$customer['customerID'] = $customerDetails->getUID();
$customer['customerEmail'] = $customerDetails->getEmail();
$customer['customerUsername'] = $customerDetails->getUsername();
$customer['customerAddress'] = $customerDetails->getAddress();

echo json_encode($customer);
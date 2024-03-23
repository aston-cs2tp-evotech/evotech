<?php

if (!isset($_POST["customerID"])) {
    http_response_code(400);
    echo "Customer ID not specified";
    exit();
}

$customerID = $_POST['customerID'];

$message = DeleteCustomerByAdmin($customerID);

if ($message == "") {
    echo "Customer " . $customerID . " has been deleted.";
} else {
    http_response_code(400);
    echo $message;
}
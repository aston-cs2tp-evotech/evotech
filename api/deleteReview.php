<?php

if (!isset($_POST["customerID"])) {
    http_response_code(418);
    echo "Customer ID not specified";
    exit();
}
if (!isset($_POST["productID"])) {
    http_response_code(418);
    echo "Product ID not specified";
    exit();
}

$prodID = $_POST["productID"];
$custID = $_POST["customerID"];

$success = DeleteReview($prodID, $custID);

if ($success) {
    echo "Successfully deleted review";
    exit();
}
else {
    http_response_code(418);
    echo "Database error deleting review";
    exit();
}
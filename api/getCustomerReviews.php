<?php

if (!isset($_POST["customerID"])) {
    http_response_code(418);
    echo "Customer ID not specified";
    exit();
}

$reviews = GetAllReviewsByCustomer($_POST["customerID"]);
if (!$reviews) {
    http_response_code(418);
    echo "Error retrieving reviews";
    exit();
}
else {
    echo json_encode($reviews);
    exit();
}
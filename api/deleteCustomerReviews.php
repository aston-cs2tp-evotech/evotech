<?php

if (!isset($_POST["customerID"])) {
    http_response_code(418);
    echo "Customer ID not specified";
    exit();
}

$success = DeleteReviewsByCustomer($_POST["customerID"]);
if ($success) {
    echo "Deleted all reviews";
    exit();
}
else {
    http_response_code(418);
    echo "Error deleting reviews";
    exit();
}
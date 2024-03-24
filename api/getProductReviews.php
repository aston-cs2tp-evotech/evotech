<?php

if (!isset($_POST["productID"])) {
    http_response_code(418);
    echo "Product ID not specified";
    exit();
}

$success = GetAllReviewsByProduct($_POST["productID"]);
if (!$success) {
    http_response_code(418);
    echo "Error retrieving reviews";
    exit();
}
else {
    echo json_encode($success);
    exit();
}
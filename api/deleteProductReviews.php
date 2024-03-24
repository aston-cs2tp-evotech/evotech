<?php

if (!isset($_POST["productID"])) {
    http_response_code(418);
    echo "Product ID not specified";
    exit();
}

$success = DeleteReviewsByProduct($_POST["productID"]);
if ($success) {
    echo "Deleted all reviews";
    exit();
}
else {
    http_response_code(418);
    echo "Error deleting reviews";
    exit();
}
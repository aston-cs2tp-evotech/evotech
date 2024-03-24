<?php

if (!isset($_POST["customerID"])) {
    http_response_code(418);
    echo "Customer ID not specified";
    exit();
}
if (!isset($_POST["productID"])) {
    http_response_code(418);
    echo "Customer ID not specified";
    exit();
}
if (!isset($_POST["rating"])) {
    http_response_code(418);
    echo "Customer ID not specified";
    exit();
}
if (!isset($_POST["review"])) {
    http_response_code(418);
    echo "Customer ID not specified";
    exit();
}

$prodID = $_POST["productID"];
$custID = $_POST["customerID"];
$rating = $_POST["rating"];
$review = $_POST["review"];

$err = UpdateReview($prodID, $custID, $rating, $review);
if (!$err) {
    echo "Updated review";
    exit();
}
else {
    http_response_code(418);
    echo $err;
    exit();
}
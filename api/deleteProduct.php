<?php

$productID = $_POST['productID'];

$message = DeleteProduct($productID);

if ($message == "") {
    echo "Product " . $productID . " has been deleted.";
} else {
    http_response_code(400);
    echo $message;
}
<?php

if (!isset($_POST["OrderID"])) {
    http_response_code(418);
    echo "Order ID not specified";
    exit();
}

$success = CancelOrReturnOrder($_POST["OrderID"], "returned");

if ($success) {
    echo "Order successfully returned";
} 
else {
    http_response_code(418);
    echo "Error returning order";
    exit();
}
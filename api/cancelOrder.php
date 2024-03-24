<?php

if (!isset($_POST["OrderID"])) {
    http_response_code(418);
    echo "Order ID not specified";
    exit();
}

$success = CancelOrReturnOrder($_POST["OrderID"], "cancelled");

if ($success) {
    echo "Order successfully cancelled";
} 
else {
    http_response_code(418);
    echo "Error cancelling order";
    exit();
}
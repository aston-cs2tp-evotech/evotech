<?php

$keys = array("orderID", "newStatusID");

foreach ($keys as $key) {
    if (!isset($_POST[$key])) {
        http_response_code(418);
        echo $key . " not specified";
    }
}

$orderID = $_POST['orderID'];
$newStatusID = $_POST['newStatusID'];

// Get the order from the database
$order = GetOrderByID($orderID);

// Check if the order exists
if (!$order) {
    http_response_code(404);
    echo "Order not found";
    exit();
}

$currentStatus = $order->getOrderStatusName();

// Get the new status from the database

// Update the order status
$result = UpdateOrderStatus($orderID, $newStatusID);

// Check if the update was successful
if ($result) {
    $response = "Order $orderID status updated from $currentStatus to $newStatusID";
} else {
    http_response_code(500);
    $response = "OrderID : $orderID status update failed";
}

// Output the response
echo $response;

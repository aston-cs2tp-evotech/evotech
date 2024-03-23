<?php

if (!isset($_POST["productID"])) {
    http_response_code(418);
    echo json_encode(array("message" => "productID not specified"));
    exit();
}

$productID = $_POST['productID'];

// Get product details from database
$productDetails = GetProductByID($productID);

$product = array();
$product['productID'] = $productDetails->getProductID();
$product['productName'] = $productDetails->getName();
$product['productPrice'] = $productDetails->getPrice();
$product['productStock'] = $productDetails->getStock();
$product['productDescription'] = $productDetails->getDescription();
$product['productCategory'] = $productDetails->getCategoryID();
$product['productImage'] = $productDetails->getMainImage();

echo json_encode($product);
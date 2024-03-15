<?php

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
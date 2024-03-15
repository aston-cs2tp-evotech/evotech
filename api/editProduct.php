<?php

$productID = $_POST['productID'];
$productName = $_POST["productName"];
$productPrice = $_POST["productPrice"];
$productStock = $_POST["productStock"];
$productDescription = $_POST["productDescription"];
$productCategory = $_POST["productCategory"];
$message = "";

// Check if product exists

$product = GetProductByID($productID);

if ($product == null) {
    $message = "Product does not exist.";
    header("Location: /admin?editProductError=" . urlencode($message));
    exit();
}

// Compare all details to see what has changed
if ($product->getName() != $productName) {
    UpdateProductDetail($productID, "Name", $productName);
}

if ($product->getPrice() != $productPrice) {
    UpdateProductDetail($productID, "Price", $productPrice);
}

if ($product->getStock() != $productStock) {
    UpdateProductDetail($productID, "Stock", $productStock);
}

if ($product->getDescription() != $productDescription) {
    UpdateProductDetail($productID, "Description", $productDescription);
}

if ($product->getCategoryID() != $productCategory) {
    UpdateProductDetail($productID, "CategoryID", $productCategory);
}

if ($product->getMainImage() != $_FILES["productImage"]["name"] && $_FILES["productImage"]["name"] != "") {

    $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/view/images/products/" . $productID . "/";
    $targetFile = $targetDir . basename($_FILES["productImage"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));


    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["productImage"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        $message = "File is not an image.";
        $uploadOk = 0;
        header("Location: /admin?editProductError=" . urlencode($message));
        exit();
    }

    // Check file size is less than 5MB
    if ($_FILES["productImage"]["size"] > 5000000) {
        $message = "Sorry, your file is too large.";
        $uploadOk = 0;
        header("Location: /admin?editProductError=" . urlencode($message));
        exit();
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif"  && $imageFileType != "webp") {
        $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
        header("Location: /admin?editProductError=" . urlencode($message));
        exit();
    }

    // Image into GdImage
    $image = imagecreatefromstring(file_get_contents($_FILES["productImage"]["tmp_name"]));

    // Convert to WEBP
    imagepalettetotruecolor($image);
    // Replace the file extension with webp
    $newTargetFile = str_replace($imageFileType, "webp", $targetFile);
    imagewebp($image, $newTargetFile, 80);

    // Free memory
    imagedestroy($image);

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $message = "Sorry, your file was not uploaded.";
        header("Location: /admin?editProductError=" . urlencode($message));
        exit();

    } else {
        if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $newTargetFile)) {

            // Update product image
            $imageName = basename($newTargetFile);
            UpdateProductImage($productID, $imageName, true);
            $message = "The file ". htmlspecialchars( basename( $_FILES["productImage"]["name"])). " has been uploaded.";
            
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

if ($message == "") {
    $message = "The product " . $productName . " has been updated.";
}
header("Location: /admin?editProductSuccess=" . urlencode($message));
exit();

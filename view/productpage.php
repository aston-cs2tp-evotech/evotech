<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

global $userInfo;
if (isset($_SESSION["uid"])) {
    ReLogInUser(); 
}

// Check if Username is set in $userInfo and then set $username
if (isset($userInfo["Username"])) {
    $username = $userInfo["Username"];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Page</title>
    <link rel="stylesheet" href="/view/css/productpage.css">
</head>

<body>
  <?php include __DIR__ . '/nav.php'?>
    <header>
        <h1> </h1>
    </header>

    <main>
        <div class="product-box">
            <div class="product-image">
                <img src="/view/images/products/<?php echo $productDetails['ProductID'];?>/<?php echo $productDetails["MainImage"]?>">
            </div>

            <div class="product-details">
                <h2><?php echo $productDetails['Name']; ?></h2>
                <p class="product-price">£<?php echo $productDetails['Price']; ?></p>

                <div class="product-description">
                    <h3>Description</h3>
                    <p><?php echo $productDetails['Description']; ?></p>
                </div>

                <form action="/add-to-basket" method="post" class="mt-3">
                    <input type="hidden" name="productID" value="<?php echo $productDetails['ProductID']; ?>">
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity:</label>
                        <input type="number" name="quantity" value="1" min="1" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary" <?php echo isset($_SESSION['uid']) ? '' : 'disabled'; ?>>
                        <?php echo isset($_SESSION['uid']) ? 'Add to Basket' : 'Log in to Add to Basket'; ?>
                    </button>
                </form>
            </div>
        </div>



        <div class="recommended-products">
            <div class="product-box">
                <div class="product-image">
                    <img src="recommended_product1.jpg" alt="Recommended Product 1">
                </div>

                <div class="product-details">
                    <h2>Recommended Product 1</h2>
                </div>
            </div>

            <div class="product-box">
                <div class="product-image">
                    <img src="recommended_product2.jpg" alt="Recommended Product 2">
                </div>

                <div class="product-details">
                    <h2>Recommended Product 2</h2>
                </div>
            </div>

            <div class="product-box">
                <div class="product-image">
                    <img src="recommended_product3.jpg" alt="Recommended Product 3">
                </div>

                <div class="product-details">
                    <h2>Recommended Product 3</h2>
                </div>
            </div>
        </div>
    </main>

</body>
<footer>
        <div class="footer-content">
            <p>&copy; 2023 EvoTech</p>
        </div>
    </footer>
</html>

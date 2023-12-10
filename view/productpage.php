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

// Get recommended products
$recommendedProducts = GetRecommendedProducts($productDetails["ProductID"]);

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


      
        <div class="recommendation-section">
            <h2>Recommended Products</h2>

            <div class="recommended-products">
                <?php foreach ($recommendedProducts as $recommendedProduct) : ?>
                    <a href="/product?productID=<?php echo $recommendedProduct['ProductID']; ?>" class="product-link">
                        <div class="product-box">
                            <div class="product-image ">
                                <img src="/view/images/products/<?php echo $recommendedProduct['ProductID']; ?>/<?php echo $recommendedProduct["MainImage"] ?>" alt="Recommended Product Image">
                            </div>
                            <div class="product-details">
                                <h5><?php echo $recommendedProduct['Name']; ?></h5>
                                <p class="product-price">£<?php echo $recommendedProduct['Price']; ?></p>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

    </main>


</body>
<footer>
    <?php include __DIR__ . '/footer.php'?>

</footer>
</html>

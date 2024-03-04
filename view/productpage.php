<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

global $userInfo;
if (isset($_SESSION["uid"])) {
    ReLogInUser(); 
}

// Check if $userInfo is set, and then set the username
if (isset($userInfo)) {
    $username = $userInfo->getUsername();
}

// Get recommended products
$recommendedProducts = GetRecommendedProducts($productDetails->getProductID());

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $productDetails->getName(); ?> - EvoTech</title>
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
                <img src="/view/images/products/<?php echo $productDetails->getProductID();?>/<?php echo $productDetails->getMainImage()?>">
            </div>

            <div class="product-details">
                <h2><?php echo $productDetails->getName();?></h2>
                <p class="product-price">£<?php echo $productDetails->getPrice(); ?></p>

                <div class="product-description">
                    <h3>Description</h3>
                    <p><?php echo $productDetails->getDescription(); ?></p>
                </div>

                <form action="/add-to-basket" method="post" class="mt-3">
                    <input type="hidden" name="productID" value="<?php echo $productDetails->getProductID(); ?>">
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
                    <a href="/product?productID=<?php echo $recommendedProduct->getProductID(); ?>" class="product-link">
                        <div class="product-box">
                            <div class="product-image ">
                                <img src="/view/images/products/<?php echo $recommendedProduct->getProductID(); ?>/<?php echo $recommendedProduct->getMainImage(); ?>" alt="Recommended Product Image">
                            </div>
                            <div class="product-details">
                                <h5><?php echo $recommendedProduct->getName(); ?></h5>
                                <p class="product-price">£<?php echo $recommendedProduct->getPrice(); ?></p>
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

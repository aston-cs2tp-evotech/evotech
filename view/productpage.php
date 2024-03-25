<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

global $userInfo;
if (isset ($_SESSION["uid"])) {
    ReLogInUser();
}

// Check if $userInfo is set, and then set the username
if (isset ($userInfo)) {
    $username = $userInfo->getUsername();
}

// Get recommended products
$recommendedProducts = GetRecommendedProducts($productDetails->getProductID());

//get reviews and choose 3
$revs = $productDetails->getProductReviews();
$reviews = array();
if (count($revs) > 3) {
    for ($i = 0; $i < 3; $i++) {
        //choose review
        $randIndex = random_int(0, count($revs) - 1);
        array_push($reviews, $revs[$randIndex]);
        //fix array
        unset($revs[$randIndex]);
        $revsNew = array();
        foreach ($revs as $rev)
            array_push($revsNew, $rev);
        $revs = $revsNew;
    }
} else {
    $reviews = $revs;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $productDetails->getName(); ?> - EvoTech
    </title>
    <link rel="stylesheet" href="/view/css/productpage.css">
</head>

<nav>
    <?php
    $currentPage = "products";
    include __DIR__ . '/nav.php';
    ?>
</nav>

<body>

    <main>
        <div class="main-product-box">
            <div class="product-image">
                <img
                    src="/view/images/products/<?php echo $productDetails->getProductID(); ?>/<?php echo $productDetails->getMainImage() ?>">
            </div>

            <div class="product-details">
                <h2>
                    <?php echo $productDetails->getName(); ?>
                </h2>
                <p class="product-price">£
                    <?php echo $productDetails->getPrice(); ?>
                </p>

                <div class="product-description">
                    <h4>
                        <?php echo $productDetails->getDescription(); ?>
                    </h4>
                </div>

                <div class="product-stock">
                    <p>Available Stock:
                        <?php echo $productDetails->getStock(); ?>
                    </p>
                </div>

                <form action="/add-to-basket" method="post" class="add-to-basket-form" id="add-to-basket-form">
                    <input type="hidden" name="productID" value="<?php echo $productDetails->getProductID(); ?>">
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="quantity">Quantity</label>
                        <input id="quantityInput" type="number" name="quantity" class="form-control flex-nowrap"
                            value="1" min="1" max="<?php echo $productDetails->getStock(); ?>" placeholder="Quantity"
                            aria-label="Quantity" aria-describedby="quantiy-addon">
                        <button type="submit" class="btn btn-<?= isset ($_SESSION['uid']) ? 'primary' : 'secondary'; ?>"
                            type="button" id="button-addon2" <?php echo isset ($_SESSION['uid']) ? '' : 'disabled'; ?>>
                            <?php echo isset ($_SESSION['uid']) ? 'Add to Basket' : 'Log in to Add to Basket'; ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <section class="p-4">
            <?php if (isset ($_SESSION["uid"]) && CheckCanLeaveReview($_SESSION["uid"], $productDetails->getProductID())) { ?>
                <div class="review-form">
                    <h3>Submit a Review</h3>
                    <p>Let us know what you think of the product!</p>
                    <form action="/leaveReview" method="post">
                        <div class="form-group stars">
                            <label id="ratingText" for="Rating">Star Rating:</label>
                            <div class="star-rating">
                                <input type="radio" id="star5" name="Rating" value="5" required>
                                <label for="star5" title="5 stars">&#9733;</label>
                                <input type="radio" id="star4" name="Rating" value="4">
                                <label for="star4" title="4 stars">&#9733;</label>
                                <input type="radio" id="star3" name="Rating" value="3">
                                <label for="star3" title="3 stars">&#9733;</label>
                                <input type="radio" id="star2" name="Rating" value="2">
                                <label for="star2" title="2 stars">&#9733;</label>
                                <input type="radio" id="star1" name="Rating" value="1">
                                <label for="star1" title="1 star">&#9733;</label>
                            </div>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control" id="review" name="Review" style="height: 100px" required></textarea>
                            <label for="review">Your Review:</label>
                        </div>
                        <div class="form-group button">
                            <input type="hidden" name="ProductID" value="<?php echo $productDetails->getProductID(); ?>">
                            <button type="submit" class="btn btn-primary">Submit Review as <?= $username ?></button>
                        </div>
                    </form>
                </div>
            <?php } ?>
            </div>
            <?php if (count($reviews) > 0) { ?>
                <div class="customer-reviews">
                    <div class="container">
                        <h2>Product Reviews</h2>
                        <p>See what our customers have to say about the product </p>
                        <div class="row">
                            <?php foreach ($reviews as $review): ?>
                                <div class="card">
                                    <div class="stars">
                                        <?= str_repeat("★", $review->getRating()) ?>
                                    </div>
                                    <p>
                                        <?= $review->getReview() ?>
                                    </p>
                                    <p>Date:
                                        <?= $review->getUpdatedAt() ?>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </section>
        <div class="recommendation-section">
            <h2 class="recommendation-title">YOU MIGHT ALSO LIKE</h2>
        </div>

        <div class="recommendation-section">

            <div class="recommended-products-wrapper">
                <?php foreach ($recommendedProducts as $recommendedProduct): ?>
                    <a href="/product?productID=<?php echo $recommendedProduct->getProductID(); ?>"
                        class="recommended-product-link">
                        <div class="recommended-product-box">
                            <div class="recommended-product-image">
                                <img src="/view/images/products/<?php echo $recommendedProduct->getProductID(); ?>/<?php echo $recommendedProduct->getMainImage(); ?>"
                                    alt="Recommended Product Image">
                            </div>
                            <div class="recommended-product-details">
                                <h5>
                                    <?php echo $recommendedProduct->getName(); ?>
                                </h5>
                                <p class="recommended-product-price">£
                                    <?php echo $recommendedProduct->getPrice(); ?>
                                </p>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

    </main>

    <?php include __DIR__ . '/footer.php' ?>
    <script>
        window.addEventListener('load', function () {
            var productImage = document.querySelector('.product-image img');
            var productDetails = document.querySelector('.product-details');

            // Set the height of product details to match the height of the image
            productDetails.style.height = productImage.offsetHeight + 'px';
        });
    </script>
</body>

</html>
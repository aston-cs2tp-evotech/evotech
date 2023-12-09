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
    <title>Basket</title>
    <!-- Link to Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/view/css/basket.css">
</head>

<body>

    <?php include __DIR__ . '/nav.php' ?>
    <div class="container">
        <header class="mt-4">
            <h1>Basket</h1>
        </header>

        <main class="mt-4">
            <div class="row">
                <div class="col-lg-8">
                    <h2>Your basket</h2>

                    <?php
                    $totalAmount = 0;

                    // Get basket items using the GetCustomerBasket function
                    $basketItems = GetCustomerBasket($totalAmount);

                    if ($basketItems) {
                        foreach ($basketItems as $item) :
                    ?>
                            <div class="card mb-3">
                                <div class="row no-gutters">
                                    <div class="col-md-4">
                                        <img src="<?php echo $item['ProductID']; ?>" class="card-img" alt="Product image">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body">
                                            <h3 class="card-title"><?php echo $item['ProductName']; ?></h3>
                                            <p class="card-text">Quantity: <?php echo $item['Quantity']; ?></p>
                                            <p class="card-text">Total Stock: <?php echo $item['TotalStock']; ?></p>
                                            <form>
                                                <div class="form-group">
                                                    <label for="quantity">Quantity</label>
                                                    <input type="number" class="form-control" name="quantity" value="<?php echo $item['Quantity']; ?>" min="1" max="<?php echo $item['TotalStock']; ?>" required />
                                                </div>
                                                <button type="button" class="btn btn-danger">Delete</button>
                                                <input type="submit" class="btn btn-primary" value="Update">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                </div>

                <div class="col-lg-4">
                    <div class="card mt-4">
                        <div class="card-body">
                            <h3 class="card-title">Subtotal</h3>
                            <h2 class="card-text">Total: £<?php echo number_format($totalAmount, 2); ?></h2>
                            <a href="checkout" class="btn btn-success btn-block">Go to checkout</a>
                            <a href="products" class="btn btn-secondary btn-block">Return to products</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <p>Your basket is empty.</p>
        <?php } ?>

        </div>

        </main>

        <!-- Link to Bootstrap JS -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    </body>

</html>

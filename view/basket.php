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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basket - EvoTech</title>
    <!-- Link to Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/view/css/basket.css">
</head>

<nav>
    <?php 
    $currentPage = "basket";
    include __DIR__ . '/nav.php';
    ?>
</nav>

<body>

    <div class="container" style= "background-color: #A499B3">
        <header class="mt-3">
            <h1>Basket</h1>
        </header>

        <main class="mt-4">
            <div class="row">
                <div class="col-lg-8">
                    <h2>Your Items</h2>

                    <?php
                    // Get basket items using the GetCustomerBasket function
                    $basketItems = GetCustomerBasket();
                    $totalPrice = null; //totalPrice is assigned after the check that basketItems exists

                    if ($basketItems) {
                        $totalPrice = 0;
                        foreach ($basketItems->getOrderLines() as $item) :
                            $totalPrice += $item->getTotalPrice();
                    ?>
                            <div class="card mb-3">
                                <div class="row no-gutters">
                                    <div class="col-md-4">
                                        <img src="/view/images/products/<?php echo $item->getProductID();?>/<?php echo $item->getMainImage();?>" class="card-img mx-auto d-block py-1 " style="margin-top: 5px;" alt="Product image">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body">
                                            <h3 class="card-title"><?php echo $item->getProductName(); ?></h3>
                                            <p class="card-text">Price: £<?php echo number_format($item->getUnitPrice(), 2); ?></p>
                                            <p class="card-text">Total Stock: <?php echo $item->getTotalStock(); ?></p>
                                            <h5 class="card-text">Subtotal: £<?php echo number_format($item->getTotalPrice(), 2); ?></h4>
                                            <form action="/update-basket" method="post">
                                                <input type="hidden" name="productID" value="<?php echo $item->getProductID(); ?>">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <span class="input-group-text" id="basic-addon1">Quantity</span>
                                                        <input type="number" class="form-control" name="quantity" value="<?php echo $item->getQuantity(); ?>" min="0" max="<?php echo $item->getTotalStock(); ?>" required />
                                                        <div class="input-group-append">
                                                            <button type="submit" class="btn btn-primary text-light" style="background-color: #1b1725">Update</button>
                                                            <button type="button" class="btn btn-danger">Delete</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                
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
                            <h2 class="card-text">Total: £<?php echo number_format($totalPrice, 2); ?></h2>
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
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Get all delete buttons
                var deleteButtons = document.querySelectorAll('.btn-danger');

                // Attach click event listener to each delete button
                deleteButtons.forEach(function (button) {
                    button.addEventListener('click', function (event) {
                        // Find the associated quantity input in the same form
                        var quantityInput = event.currentTarget.closest('.card-body').querySelector('[name="quantity"]');

                        // Set the quantity to 0
                        if (quantityInput) {
                            quantityInput.value = 0;
                        }

                        event.currentTarget.closest('form').submit();
                    });
                });
            });
        </script>



 

 
</body>

</html>

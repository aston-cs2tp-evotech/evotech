<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

global $userInfo;
if (isset($_SESSION["uid"])) {
    ReLogInUser();
}

// Check if $userInfo is set, and then set the username and address
if (isset($userInfo)) {
    $username = $userInfo->getUsername();
    $address = $userInfo->getAddress();
}

$basketItems = GetCustomerBasket($totalAmount);
// check if basket is empty

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - EvoTech</title>
    <!-- Link to Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Link to your custom CSS -->
    <link rel="stylesheet" type="text/css" href="/view/css/login_register_checkout_customer.css">
</head>

<body>
<?php include __DIR__ . '/nav.php'?>
<header class="bg-dark text-white text-center py-4">
    <h1></h1>
</header>


<main>
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-8">
                <h2>Shipping address for <?php echo $username ?></h2>
                <form action="/checkoutProcess" method="POST">
                    <div class="form-group">
                        <label for="full_name">Full name</label>
                        <input type="text" class="form-control" name="full_name" required/>
                    </div>
                    <div class="form-group">
                        <label for="Full_address">Full address</label>
                        <textarea name="customer_address" placeholder="Address" rows="6" required><?php echo $address; ?></textarea>
                    </div>

                    <h2>Card details</h2>
                    <div class="form-group">
                        <label for="card_number">Card number</label>
                        <input type="text" class="form-control" name="card_number" required/>
                    </div>
                    <div class="form-group">
                        <label for="name_card">Name on card</label>
                        <input type="text" class="form-control" name="name_card" required/>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="expiration_month">Expiration Month</label>
                            <input type="number" class="form-control" name="expiration_month" min="1" max="12" required/>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="expiration_year">Expiration Year</label>
                            <input type="number" class="form-control" name="expiration_year" min="2023" max="2043" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="security_code">Security code</label>
                        <input type="text" class="form-control" name="security_code" required/>
                    </div>

                    <input type="submit" class="btn btn-primary mt-3" value="Check out"/>
                </form>
            </div>

            <div class="col-lg-4">
                <h2>Your Basket</h2>
                
                <?php
                $totalAmount = 0;
                $totalPrice = 0;
                // Get basket items using the GetCustomerBasket function

                foreach ($basketItems as $item) :
                    $totalPrice += $item["UnitPrice"] * $item["Quantity"];
                ?>

                    <div class="card mb-3">
                        <div class="card-body">
                            <h4 class="card-title"><?php echo $item['ProductName']; ?></h4>
                            <p class="card-text">Quantity: <?php echo $item['Quantity']; ?></p>
                            <p class="card-text">Price: £<?php echo $item['UnitPrice']; ?></p>
                            <h5 class="card-text">Subtotal: £<?php echo $item['UnitPrice'] * $item['Quantity']; ?></h5>
                        </div>
                    </div>

                <?php endforeach; ?>


                <div class="card mt-4">
                    <div class="card-body">
                        <h3 class="card-title">Subtotal</h3>
                        <h2 class="card-text">£<?php echo $totalPrice; ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>



<!-- Link to Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>

</html>
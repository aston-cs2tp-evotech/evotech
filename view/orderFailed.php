<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

global $userInfo;
if (isset($_SESSION["uid"])) {
    ReLogInUser();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Failed - EvoTech</title>
    <!-- Link to Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Link to your custom CSS -->
    <link rel="stylesheet" type="text/css" href="/view/css/orderFailed.css"/>
</head>

<body>
    <?php include __DIR__ . '/nav.php'?>
    <div class="container" style= "background-color: #A499B3">
        <header class="mt-3">
            <h1>Order Failure...</h1>
        </header>


    <main>
        <div class="container mt-4">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Your Order Has Failed</h2>
                    <p>We're sorry, but there was an issue processing your order. Please try again later or contact Customer Support.</p>

                    <a href="/checkout" class="btn btn-danger">Go Back to Checkout</a>
                </div>
            </div>
        </div>
    </main>

    <!-- Link to Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>

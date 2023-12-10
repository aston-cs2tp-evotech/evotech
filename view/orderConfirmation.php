<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Assuming ReLogInUser() initializes $userInfo
global $userInfo;
if (isset($_SESSION["uid"])) {
    ReLogInUser();
}

// Check if Username is set in $userInfo and then set $username
if (isset($userInfo["Username"])) {
    $username = $userInfo["Username"];
}

// Additional logic if needed

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <!-- Link to Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Link to your custom CSS -->
    <link rel="stylesheet" type="text/css" href="/view/css/login_register_checkout.css"/>
</head>

<body>
    <?php include __DIR__ . '/nav.php'?>
    <header class="bg-dark text-white text-center py-4">
        <h1><br>Order Confirmation</h1>
    </header>

    <main>
        <div class="container mt-4">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Your order has been confirmed</h2>
                    <p>Thank you, <?php echo $username; ?>, for your order!</p>
                    <a href="/customer" class="btn btn-primary">View account and order history</a>
                </div>
            </div>
        </div>
    </main>

    <!-- Link to Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>

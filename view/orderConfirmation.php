<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Assuming ReLogInUser() initializes $userInfo
global $userInfo;
if (isset($_SESSION["uid"])) {
    ReLogInUser();
}

// Check if $userInfo is set, and then set the username
if (isset($userInfo)) {
    $username = $userInfo->getUsername();
}

// Additional logic if needed

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Evotech</title>
    <!-- Link to Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Link to your custom CSS -->
    <link rel="stylesheet" type="text/css" href="/view/css/orderConfirmation.css"/>
</head>

<body>
    <?php include __DIR__ . '/nav.php'?>

    <div class="container" style= "background-color: #A499B3">
        <header class="mt-3">
            <h1>Order Confirmation</h1>
        </header>

    <main>
        <div class="container mt-4">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Your Order Has Been Confirmed</h2>
                    <p>Thank you <?php echo $username; ?>, for Your Order!</p>
                    <a href="/customer" class="btn btn-primary">View Account and Order History</a>
                </div>
            </div>
        </div>
    </main>

    <!-- Link to Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>

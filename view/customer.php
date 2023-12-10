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

// Get previous orders
$totalAmount = array();
$orders = GetPreviousOrders($totalAmount); // Assuming $orders is a 3D array: $orders[order][product]['KEY']
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Details</title>
    <!-- Link to Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Link to your custom CSS -->
    <link rel="stylesheet" type="text/css" href="/view/css/login_register_checkout_customer.css">
</head>

<body>
    <?php include __DIR__ . '/nav.php' ?>
    <header class="bg-dark text-white text-center py-4">
        <h1><br>Welcome, <?php echo $username; ?>!</h1>
        <h4>Not <?php echo $username; ?>? <a href="/logout">Log Out</a></h4>
    </header>

    <main class="container mt-4">
        <div class="row">
            <div class="col-lg-6">

        
                <h2>Customer Details</h2>
                <p><strong>Username:</strong> <?php echo $userInfo["Username"]; ?></p>
                <p><strong>Email:</strong> <?php echo $userInfo["Email"]; ?></p>
                <p><strong>Address:</strong> <?php echo $userInfo["CustomerAddress"]; ?></p>

            </div>
            
            <div class="col-lg-6">
                <h2>Change Password</h2>
                <!-- Check if passwordChangeResult is set -->
                <?php if (isset($passwordChangeResult)) : ?>
                    <?php if ($passwordChangeResult) : ?>
                        <div class="alert alert-success" role="alert">
                            Password changed successfully!
                        </div>
                    <?php else : ?>
                        <div class="alert alert-danger" role="alert">
                            Password change failed!
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <form action="/change-password" method="POST">
                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" class="form-control" name="current_password" required/>
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" class="form-control" name="new_password" required/>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" class="form-control" name="confirm_password" required/>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Change Password"/>
                </form>
            </div>
        </div>

        <div class="mt-4">
            <h2>Past Orders</h2>
            <?php if (!empty($orders)) : ?>
                <?php $orders = array_reverse($orders, true); // Reverse the order of the array ?>
                <?php foreach ($orders as $orderID => $order) : ?>
                    <?php $totalPrice = 0; ?>
                    <h3>Order <?php echo $orderID; ?></h3>
                    <h4>Status: <?php echo $order['Status']; ?></h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order as $product) : ?>
                                    <?php if (gettype($product) == "string") { continue; }?>
                                    <?php $totalPrice += $product['UnitPrice'] * $product['Quantity']; ?>
                                    <tr>
                                        <td><?php echo $product['ProductName']; ?></td>
                                        <td><?php echo $product['Quantity']; ?></td>
                                        <td>£<?php echo $product['UnitPrice'] * $product['Quantity']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <h3 class="text-right">Total: £<?php echo $totalPrice; ?></h3>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>No past orders found.</p>
            <?php endif; ?>
        </div>
    </main>
    <footer>
      <?php include __DIR__ . '/footer.php'?>

   </footer>

    <!-- Link to Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>

</html>

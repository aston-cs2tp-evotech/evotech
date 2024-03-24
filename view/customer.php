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
    $email = $userInfo->getEmail();
    $addr = $userInfo->getAddress();
}

// Get previous orders
$orders = GetPreviousOrders(); // Assuming $orders is a 3D array: $orders[order][product]['KEY']
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo "$username"; ?> - EvoTech</title>
    <!-- Link to Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Link to your custom CSS -->
    <link rel="stylesheet" type="text/css" href="/view/css/checkout_customer.css">
</head>

<body>
    <?php include __DIR__ . '/nav.php' ?>
    
    <header class="bg-dark text-white text-center my-5 py-4">
        <h1>Welcome, <?php echo $username; ?>!</h1>
        <h4>Not <?php echo $username; ?>? <a href="/logout">Log Out</a></h4>
    </header>

    <main class="container mt-4">
        <div class="row">
            <div class="col-lg-6">
                <h2>Customer Details</h2>
                <p><strong>Username:</strong> <?php echo $username; ?></p>
                <p><strong>Email:</strong> <?php echo $email; ?></p>
                <p><strong>Address:</strong> <?php echo $addr; ?></p>
            </div>
            
            <div class="col-lg-6">
                <h2>Change Password</h2>
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
                    <input type="submit" class="btn btn-primary mb-5" value="Change Password"/>
                </form>
            </div>
        </div>

        <div class="mt-4">
            <h2 class="mb-5">Past Orders</h2>
            <?php if (!empty($orders)) : ?>
                <?php $orders = array_reverse($orders, true); ?>
                <?php foreach ($orders as $order) : ?>
                    <?php $totalPrice = $order->getTotalAmount(); ?>
                   
                    <div class="card mb-5">
                        <div class="card-body ">
                        <h3>Order reference ID: #<?php echo $order->getOrderID(); ?></h3>
                        <h4>Status: <?php echo $order->getOrderStatusName(); ?></h4>
                        <h4>Created at: <?php echo $order->getCheckedOutAt(); ?></h4>
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
                                        <?php foreach ($order->getOrderLines() as $product) : ?>
                                            <tr>
                                                <td><?php echo $product->getProductName(); ?></td>
                                                <td><?php echo $product->getQuantity(); ?></td>
                                                <td>£<?php echo $product->getTotalPrice(); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <div class="col text-right"> 
                                    <h3>Total: £<?php echo $totalPrice; ?></h3>
                                    <form action="/cancelOrder" method="POST">
                                        <input type="hidden" name="OrderID" value="<?php echo $order->getOrderID(); ?>">
                                    <?php if ($order->getOrderStatusName() === "delivered") : ?>
                                        <button class="btn btn-primary mt-3" style="width: 200px;">Return</button>
                                    <?php elseif (!in_array($order->getOrderStatusName(), ["cancelled", "returned"])) : ?>
                                        <button type="submit" class="btn btn-danger mt-3" style="width: 200px;">Cancel Order</button>
                                    <?php endif; ?>
                                    </form>
                                </div>
                            </div>
                        </div>
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

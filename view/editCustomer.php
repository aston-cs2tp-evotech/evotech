<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['uid'])) {
    // Redirect to home page
    header("Location:/");
    exit();
}

global $userInfo;
if (isset($_SESSION["uid"])) {
    ReLogInUser();
}

$user = GetCustomerByID($_SESSION['uid']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit <?php echo "$username"; ?> - EvoTech</title>
    <link rel="stylesheet" type="text/css" href="/view/css/register.css"> 
    <script src="/view/js/register.js"></script>
</head>
<body>

<?php include __DIR__ . '/nav.php' ?>

<main>

    <div class="login">

        <div class="splash">


        </div>

        <div class="login-box">

            <div class="form-signin">
                <h3 class="welcome">update your data</h3>

                <?php
                // Check if there are any errors to display
                if (isset($registrationResult) && $registrationResult !== "") {
                    echo "<div class='alert alert-danger'>$registrationResult</div>";
                }
                ?>

                <form action="/processEditCustomer" method="POST" id="registerForm">
                    <div class="form-floating">
                        <input name="email" type="email" class="form-control" id="floatingEmail" placeholder="Email" value="<?php echo $user->getEmail(); ?>" required>
                        <label for="floatingEmail">Email</label>
                    </div>
                    <div class="form-floating">
                        <input name="username" type="text" class="form-control" id="floatingUsername" placeholder="Username" value="<?php echo $user->getUsername(); ?>" required>
                        <label for="floatingUsername">Username</label>
                    </div>
                    <div class="form-floating">
                        <textarea name="customer_address" style="margin-bottom: 10px; border-bottom-left-radius : 0.375rem; border-bottom-right-radius : 0.375rem;"
                        class="form-control" id="floatingAddress" placeholder="Address" rows="6" required><?php echo $user->getAddress(); ?></textarea>
                        <label for="floatingAddress">Address</label>
                    </div>
                    <button style="border-bottom-left-radius : 0.375rem; border-bottom-right-radius : 0.375rem;" id="registerButton" class="btn btn-primary w-100 py-2" type="submit">Update Details</button>
                </form>
            </div>

        </div>
    </div>
</main>

<?php include __DIR__ . '/footer.php' ?>

</body>
</html>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['uid'])) {
    // Redirect to home page
    header("Location:/");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - EvoTech</title>
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
                <h3 class="welcome">Start evolving your tech today</h3>

                <?php
                // Check if there are any errors to display
                if (isset($registrationResult) && $registrationResult !== "") {
                    echo "<div class='alert alert-danger'>$registrationResult</div>";
                }
                ?>

                <form action="/register" method="POST" id="registerForm">
                    <div class="form-floating">
                        <input name="email" type="email" class="form-control" id="floatingEmail" placeholder="Email" required>
                        <label for="floatingEmail">Email</label>
                    </div>
                    <div class="form-floating">
                        <input name="username" type="text" class="form-control" id="floatingUsername" placeholder="Username" required>
                        <label for="floatingUsername">Username</label>
                    </div>
                    <div class="form-floating">
                        <textarea name="customer_address" class="form-control" id="floatingAddress" placeholder="Address" rows="6" required></textarea>
                        <label for="floatingAddress">Address</label>
                    </div>
                    <div class="form-floating">
                        <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password" minlength="8" required>
                        <label for="floatingPassword">Password</label>
                    </div>
                    <div class="form-floating">
                        <input name="confirmpass" type="password" class="form-control" id="floatingConfirmPassword" placeholder="Confirm Password" required>
                        <label for="floatingConfirmPassword">Confirm Password</label>
                    </div>
                    <button id="registerButton" class="btn btn-primary w-100 py-2" type="submit">Register</button>
                    <button id="loginRedirectButton" class="btn btn-secondary w-100 py-2" type="button" onclick="window.location.href='/login'">Already have an account?<br>Log in here</button>
                </form>
            </div>

        </div>
    </div>
</main>

<?php include __DIR__ . '/footer.php' ?>

</body>
</html>

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

    <?php include __DIR__ . '/nav.php'?>

    <main>

    <div class="register">

        <div class="register-image">

            <img src="view/images/login_register_image.jpg" alt="Register image">

        </div>

        <div class="register-form">

            <br>
            <br>

            <h2 class="welcome">Welcome to Evotech</h2>

            <br>
            <br>
            <br>

            <?php
            // Check if there are any errors to display
            if (isset($registrationResult) && $registrationResult !== "") {
                echo "<div class='alert alert-danger'>$registrationResult</div>";
            }
            ?>

            <h2>Register</h2>
            <form id="register" action="register" method="POST">
                <input type="email" name="email" placeholder="Email" required/>
                <br>
                <input type="text" name="username" placeholder="Username" required/>
                <br>
                <textarea name="customer_address" placeholder="Address" rows="6" required></textarea>
                <br>
                <br>
                <input type="password" name="password" placeholder="Password" required/>
                <br>
                <input id="password_confirmation" type="password" name="confirmpass" placeholder="Confirm password" required/>
            </form>
            <br>
            <br>
            <br>
            <br>
                <a href="#" onclick="submitForm();" class="register-form-button">Register</a>
            <br>
            <br>
            <br>
            
            <h2>Already have an account?</h2>
                <br>
                <a href="login" class="register-login-button">Log in</a>

        </div>
    
    </div>

    </main>

    </body>

    <?php include __DIR__ . '/footer.php'?>

</html>

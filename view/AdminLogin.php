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
        <title>Admin login - EvoTech</title>
        <link rel="stylesheet" type="text/css" href="/view/css/login.css">
        <script src="/view/js/login.js"></script>
    </head>
    <body>
    
    <?php include __DIR__ . '/nav.php'?>

    <main>

    <div class="login">

        <div class="login-image">

            <img src="view/images/loginRegisterImage.jpg" alt="Login image">

        </div>

        <div class="login-form">

            <br>
            <br>

            <h2 class="welcome">Welcome to Evotech</h2>

            <br>
            <br>
            <br>

            <?php
            // Check if loginResult is False
            if (isset($loginResult) && $loginResult === False) {
                echo "<div class='alert alert-danger'>Incorrect username or password</div>";
            }
            ?>

            <h2>Login in as an admin</h2>

            <form id="login" action="adminLogin" method="POST">
                <input type="text" name="username" placeholder="Username" reqeuired/>
                <br>
                <input type="password" name="password" placeholder="Password" required/>
            </form>
            <br>
            <br>
            <br>
            <br>
                <a href="#" onclick="submitForm();" class="login-form-button">Login</a>
            <br>
            <br>
            <br>

        </div>

    </div>

    </main>

    </body>

    <?php include __DIR__ . '/footer.php'?>

</html>

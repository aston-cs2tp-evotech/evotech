<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset ($_SESSION['uid'])) {
    // Redirect to home page
    header("Location:/");
    exit();
}

if (isset ($_SESSION["loginMessage"])) {
    $loginMessage = $_SESSION["loginMessage"];
    unset($_SESSION["loginMessage"]);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in - EvoTech</title>
    <link rel="stylesheet" type="text/css" href="/view/css/login.css">
    <style>
        #signInButton {
            border-radius: 0.375rem;
        }
    </style>
    <script src="/view/js/login.js"></script>
</head>

<body>

    <?php include __DIR__ . '/nav.php' ?>

    <main>

        <div class="login">

            <div class="splash">

                <img src="view/images/loginRegisterImage.jpg" alt="splash">

            </div>

            <div class="login-box">

                <div class="form-signin">
                    <h3 class="welcome">admin panel access</h3>

                    <?php
                    // Check if loginResult is False
                    if (isset ($loginResult) && $loginResult === False) {
                        $loginMessage = "Invalid username or password";
                    }
                    if (isset ($loginMessage) && $loginMessage !== "") {
                        echo "<div class='alert alert-danger'>$loginMessage</div>";
                    }
                    ?>

                    <form action="/adminLogin" method="POST" id="loginForm">
                        <div class="form-floating">
                            <input name="username" type="text" class="form-control" id="floatingInput" placeholder="username/email">
                            <label for="username">Username</label>
                        </div>
                        <div class="form-floating">
                            <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password" minlength="8">
                            <label for="password">Password</label>
                        </div>
                        <button id="signInButton" class="btn btn-primary w-100 py-2" type="submit">Sign in</button>
                    </form>
                </div>

            </div>
        </div>
    </main>
</body>

<?php include __DIR__ . '/footer.php' ?>

</html>
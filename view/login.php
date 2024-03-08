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
        <title>Log in - EvoTech</title>
        <link rel="stylesheet" type="text/css" href="/view/css/login.css">
        
    </head>
    <body>
    <?php include __DIR__ . '/nav.php'?>
        
    <section class="bg-success p-5  py-4">

    </section>

        <header>
            <h1>Log in</h1>
        </header>

        <main>

        <section class="login">

        <div class="login-image">

            <img src="view/images/loginimage.jpg" alt="Login image">
        
        </div>

        <div class="login-form">

            <h2>Welcome to Evotech</h2>

            <br>
            <br>
            <br>

            <?php
            // Check if loginResult is False
            if (isset($loginResult) && $loginResult === False) {
                echo "<div class='alert alert-danger'>Incorrect username or password</div>";
            }
            ?>

            <h2>Log in</h2>
            <form action="login" method="POST">
                <input type="text" name="usernameOrEmail" placeholder="Username / email address" required/>
                <br>
                <input type="password" name="password" placeholder="Password" required/>
            <br>
            <br>
            <br>
            <br>
                <input type="submit" value="Log in"/>
            <br>
            </form>

            <br>
            <br>
            
            <h2>Don't have an account?</h2>
            <form action="/register" method="POST">
                <input type="submit" value="Register"/>
            </form>

            <br>
            
        </div>

        </section>

        </main>

    </body>

    <footer>
    <?php include __DIR__ . '/footer.php'?>

</footer>

</html>

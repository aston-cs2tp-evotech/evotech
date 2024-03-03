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
        <link rel="stylesheet" type="text/css" href="/view/css/login_register_checkout_customer.css">
    
    </head>
    <body>
      <?php include __DIR__ . '/nav.php'?>
      <section class="bg-success p-5  py-4">

       </section>

        <header>
            <h1>Log in</h1>
        </header>

        <main>

        <div class="container">

	    <h2>Log in to your Evotech account</h2>

            <?php
            // Check if loginResult is False
            if (isset($loginResult) && $loginResult === False) {
                echo "<div class='alert alert-danger'>Incorrect username or password</div>";
            }
            ?>

            <form action="login" method="POST">
                <p><b>Enter your username or email</b></p>
                <input type="text" name="usernameOrEmail" placeholder="usernameOrEmail" required/>
                <br>
                <p><b>Enter your password</b></p>
                <input type="password" name="password" placeholder="Password" required/>
            <br>
            <br>
            <br>
                <input class="btn btn-success" type="submit" value="Log in"/>
            <br>
            </form>

            <br>
            <h2>Don't have an account?</h2>
            <a href="register">Register</a>

            <br>
            <h2>Admin login</h2>
            <a href="AdminLogin">AdminLogin</a>
            


            </div>

        </main>

    </body>
    <footer>
    <?php include __DIR__ . '/footer.php'?>

</footer>

</html>

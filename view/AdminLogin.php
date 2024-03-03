<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin login- Evotech</title>
        <link rel="stylesheet" type="text/css" href="/view/css/login_register_checkout_customer.css">
    
    </head>
    <body>
      <?php include __DIR__ . '/nav.php'?>
      <section class="bg-success p-5  py-4">

       </section>

        <header>
            <h1>Admin Login</h1>
        </header>

        <main>

        <div class="container">

	    <h2>Login in as Admin.</h2>

          

            <form action="login" method="POST">
                <p><b>Admin's username:</b></p>
                <input type="text" name="username" placeholder="Enter username:" required/>
                <br>
                <p><b>Enter your password</b></p>
                <input type="password" name="password" placeholder="Enter Password:" required/>
            <br>
            <br>
            <br>
                <input class="btn btn-success" type="submit" value="Log in"/>
            <br>
            </form>

            <br>
            

            </div>

        </main>

    </body>
    <footer>
    <?php include __DIR__ . '/footer.php'?>

</footer>

</html>

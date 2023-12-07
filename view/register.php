<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Register</title>
        <link rel="stylesheet" type="text/css" href="view/css/login_register_checkout.css"/>

    </head>
    <body>

        <header>
            <h1>Register</h1>
        </header>

        <main>

        <div class="container">

        <h2>Register an Evotech account</h2>

            <form>
            <label for="email">Enter your email address</label>
            <input type="email" name="email" placeholder="Email" required/>
            <br>
            <label for="username">Enter a username</label>
            <input type="text" name="username" placeholder="Username" required/>
            <br>
            <label for="customer_address">Enter your address</label>
            <textarea name="customer_address" placeholder="Address" rows="6" required></textarea>
            <br>
            <label for="password">Enter your password</label>
            <input type="password" name="password" placeholder="Password" required/>
            <br>
            <label for="confirmpass">Re-enter password</label>
            <input id="password_confirmation" type="password" name="confirmpass" placeholder="Confirm password" required/>

            <br>
            <br>
            <br>
                <input type="submit" value="Register"/>
            <br>
            </form>

            <br>
            <h2>Already have an account?</h2>
            <a href="login">Log in</a>

            </div>

        </main>

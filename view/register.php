<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Register</title>
        <link rel="stylesheet" type="text/css" href="css/login_register.css"/>

    </head>
    <body>

        <header>
            <h1>Register</h1>
        </header>

        <main>

        <div class="container">

        <h2>Register an Evotech account</h2>

            <form>
                <p><b>Enter your first name</b></p>
                <input type="text" name="first_name" placeholder="First name" required/>   
                <br>
                <p><b>Enter your surname</b></p>  
                <input type="text" name="surname" placeholder="Surname" required/>
                <br>
                <p><b>Enter your email address</b></p>
                <input type="email" name="email" placeholder="Email" required/>
                <br>
                <p><b>Enter a secure password</b></p>
                <input type="password" name="password" placeholder="Password" required/>
                <br>
                <p><b>Re-enter password</b></p>
                <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirm password" required/>
            <br>
            <br>
            <br>
                <input type="submit" value="Register"/>
            <br>
            </form>

            <br>
            <h2>Already have an account?</h2>
            <a href="login.html">Log in</a>

            </div>

        </main>

    </body>

</html>

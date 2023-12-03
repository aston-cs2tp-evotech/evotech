<!DOCTYPE html>

<html lang="en">
<meta charset="utf-8"/>

    <head>

        <link rel="stylesheet" type="text/css" href="css/signup_login_style.css"/>
        
        <title>
            Register
        </title>

    </head>

    <body>

        <main>

            <h1>evotech;</h1>

            <div style="text-align:center">

            <h2>Create an account</h2>

            <form>
                <p><b>Enter your first name</b></p>
                <input type="text" name="first_name" placeholder="First name" required/>   
                <p><b>Enter your surname</b></p>  
                <input type="text" name="surname" placeholder="Surname" required/>
                <p><b>Enter your email address</b></p>
                <input type="email" name="email" placeholder="Email" required/>
                <p><b>Enter a secure password</b></p>
                <input type="password" name="password" placeholder="Password" required/>
                <p><b>Re-enter password</b></p>
                <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirm password" required/>
            <br>
            <br>
            <br>
                <input type="submit" value="Register"/>
            </form>

            <br>
            <h2>Already have an account?</h2>
            <a href="login.html">Log in</a>

            </div>

        </main>

    </body>

</html>

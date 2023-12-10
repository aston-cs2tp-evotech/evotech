<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer</title>
    <!-- Link to Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Link to custom CSS -->
    <link rel="stylesheet" type="text/css" href="/view/css/login_register_checkout_customer.css"/>
</head>

<body>
    <header class="bg-dark text-white text-center py-4">
        <h1>Customer</h1>
    </header>

    <main>
        <section class="Customer">
        <div class="shadow p-3 mb-5 bg-light body-tertiary rounded">
            <div class="container">
            <div class="card mb-3">
            <div class="no no-gutters">
            <div class="col-md-4">
            <h2>Change password</h2>
            <form action="change_password" method="POST">
                <label for="password">Enter a new password</label>
                <br>
                <input type="password" name="password" placeholder="Password" required/>
                <br>
                <br>
                <label for="confirmpass">Re-enter password</label>
                <br>
                <input id="password_confirmation" type="password" name="confirmpass" placeholder="Confirm password" required/>
                <br>
                <br>
                <input class="btn btn-primary" type="submit" value="Change password"/>
            </form>
            <br>
            <br>
            <h2>Change address</h2>
            <form action="change_address" method="POST">
                <label for="customer_address">Enter new address</label>
                <br>
                <textarea name="customer_address" placeholder="Address" rows="6" required></textarea>
                <br>
                <br>
                <input class="btn btn-primary" type="submit" value="Change address"/>
            </form>
            <br>
            <br>
            <h2>Your past orders</h2>
            </div>
            </div>
            </div>
            </div>
        </div>
        </section>
    </main>

    <!-- Link to Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>

                    
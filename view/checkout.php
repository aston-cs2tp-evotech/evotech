<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Checkout</title>
        <link rel="stylesheet" type="text/css" href="view/css/login_register_checkout.css">
    </head>

    <body>

        <header>
            <h1>Checkout</h1>
        </header>

        <main>

        <div class="container">
        
        <h2>Shipping address</h2>

            <form>
                <p><b>Full name</b></p>
                <input type="text" name="full_name" required/>
                <br>
                <p><b>Address line 1</b></p>
                <input type="text" name="address_1" required/>
                <br>
                <p><b>Address line 2</b></p>
                <input type="text" name="address_2"/>
                <br>
                <p><b>Town/city</b></p>
                <input type="text" name="town_city" required/>
                <br>
                <p><b>County</b></p>
                <input type="text" name="county"/>
                <br>
                <p><b>Country/region</b></p>
                <input type="text" name="country" required/>
            <br>

        <h2>Card details</h2>

                <p><b>Card number</b></p>
                <input type="text" name="card_number" required/>
                <br>
                <p><b>Name on card</b></p>
                <input type="text" name="name_card" required/>
                <br>
                <p><b>Expiration date</b></p>
                <p>Month</p>
                <input type="number" name="expiration_month" min="1" max="12" required/>
                <br>
                <p>Year</p>
                <input type="number" name="expiration_year" min="2023" max="2043" required/>
                <br>
                <p><b>Security code</b></p>
                <input type="text" name="security_code" required/>
            <br>
            <br>
            <br>
                <input type="submit" value="Check out"/>
            <br>
            </form>

            </div>

        </main>

    </body>
    
</html>
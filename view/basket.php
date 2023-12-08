<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Basket</title>
        <link rel="stylesheet" type="text/css" href="/view/css/basket.css">
    </head>

    <body>

    <?php include __DIR__ . '/nav.php'?>
        <header>
            <h1>Basket</h1>
        </header>

        <main>

        <div class="container">

        <h2>Your basket</h2>

        <form>
            <img src="product_image" alt="Product image">
            <br>
            <h3>Product name</h3>
            <br>
            <br>
            <br>
            <p>Quantity</p>
            <input type="number" name="quantity" min="1" max="10" required/>
            <br>
            <br>
            <button type="button">Delete</button>
            <br>
            <br>
            <input type="submit" value="Submit"/>
            <br>
            <br>
        </form>



        <h3>Subtotal</h3>
        <br>
        <br>
        <br>
        <h2>Total</h2>
        <br>
        <a href="checkout">Go to checkout</a>
        <a href="products">Return to products</a>

        </div>

        </main>

    </body>

</html>


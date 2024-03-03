<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - EvoTech</title>
    <link rel="stylesheet" href="/view/css/addproduct.css">
</body>

<body>
    <?php include __DIR__ . '/nav.php'?>
        <header>
            <h1>Add product</h1>
        </header>

        <main>

        <div class="add-products">
        <form action="add_product" method="POST">
            <p><b>Enter product name</b></p>
            <input type="text" name="productName" placeholder="Product name" required/>
            <br>
            <p><b>Enter description</b></p>
            <textarea name="description" placeholder="Description" rows="8" required>
            <br>
            <p><b>Enter quantity</b></p>
            <input type="text" name="quantity" placeholder="Quantity" required/>
            <br>
            <p><b>Enter price</b></p>
            <input type="text" name="price" placeholder="Price" required/>
        <br>
        <br>
        <br>
            <input class="btn btn-success" type="submit" value="Add product"/>
        <br>
        </form>

        </main>

</body>

</html>
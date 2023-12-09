<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
  <link rel="stylesheet" type="text/css" href="Basket.css">
  <title>EvoTech</title>
  <ion-icon name="desktop-outline"></ion-icon>


</head>
<header>

</header>

<body>
  <?php include __DIR__ . '/nav.php' ?>

  <!-- Box to prevent content being covered by navbar-->
  <section class="bg p-5 py-4">
  </section>
  <section class="bg-dark text-light p-5 text-center text-sm-start py-4">
    <div class="container">
      <div class="d-sm-flex allign-items-center justify-content-between">
        <div>
          <p class="lead my-3 text-center">
            Products
          </p>
        </div>
      </div>
    </div>

  </section>

  <section class="Products">
    <div class="shadow p-3 mb-5 bg-light body-tertiary rounded">
      <div class="container">
        <?php
        $products = GetAllProducts();
        foreach ($products as $item):
          ?>
          <div class="card mb-3">
            <div class="card-body">
              <h4 class="card-title">Product Name:
                <?php echo $item['Name']; ?>
              </h4>
              <p class="card-text">Description:
                <?php echo $item['Description']; ?>
              </p>
              <p class="card-text">Stock:
                <?php echo $item['Stock']; ?>
              </p>
              <p class="card-text">Price: £
                <?php echo $item['Price']; ?>
              </p>
              <a href="/product?productID=<?php echo $item['ProductID']; ?>" class='btn btn-primary'>Product Page</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</body>

</html>
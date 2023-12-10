<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

global $userInfo, $products;
if (isset($_SESSION["uid"])) {
    ReLogInUser(); 
}

// Check if Username is set in $userInfo and then set $username
if (isset($userInfo["Username"])) {
    $username = $userInfo["Username"];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
  <link rel="stylesheet" type="text/css" href="/view/css/products.css">
  <title>EvoTech</title>
  <ion-icon name="desktop-outline"></ion-icon>
</head>

<body>
  <?php include __DIR__ . '/nav.php' ?>

  <h1 class="text-center">Products</h1>

  <section class="Products">
    <div class="shadow p-3 mb-5 bg-light body-tertiary rounded">
      <div class="container">
        <?php
        foreach ($products as $item):
        ?>
          <div class="card mb-3">
            <div class="row no-gutters">
              <div class="col-md-4">
                <img src="/view/images/products/<?php echo $item['ProductID'];?>/<?php echo $item["MainImage"]?>" class="card-img" alt="Product Image">
              </div>
              <div class="col-md-8">
                <div class="card-body">
                  <h4 class="card-title">
                    <?php echo $item['Name']; ?>
                  </h4>
                  <p class="card-text">
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
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
  <link rel="stylesheet" type="text/css" href="/view/css/products.css">
  <title><?php if (isset($_GET["category"])) {echo $_GET["category"];} else {echo "Products";}?> - EvoTech</title>
</head>

<body>
  <?php include __DIR__ . '/nav.php' ?>

  <h1 class="text-center">Products</h1>

  <section class="Products">
    <div class="shadow p-3 mb-5 bg body-tertiary rounded">
      <div class="container">
        <div class="row">
          <div class="col">
            <div class="dropdown m-2">
              <button class="btn btn-secondary dropdown-toggle" type="button" id="categoryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                Category
              </button>
              <ul class="dropdown-menu" aria-labelledby="categoryDropdown">
                <li><a class="dropdown-item" href="/products?category=Motherboards">Motherboards</a></li>
                <li><a class="dropdown-item" href="/products?category=CPUs">CPUs</a></li>
                <li><a class="dropdown-item" href="/products?category=Graphics%20Cards">Graphics Cards</a></li>
                <li><a class="dropdown-item" href="/products?category=Cases">Cases</a></li>
                <li><a class="dropdown-item" href="/products?category=Memory">Memory</a></li>
                <li><a class="dropdown-item" href="/products?category=Storage">Storage</a></li>
              </ul>
            </div>
          </div>
          <div class="col"> 
            <input type="text" class="form-control m-2" id="search" placeholder="Search">
          </div>

          <?php
          foreach ($products as $item):
          ?>
            <div class="card mb-3 rounded">
              <div class="row no-gutters">
                <div class="col-md-4">
                  <img src="/view/images/products/<?php echo $item->getProductID();?>/<?php echo $item->getMainImage();?>" class="card-img" alt="Product Image">
                </div>
                <div class="col-md-8">
                  <div class="card-body">
                    <h4 class="card-title">
                      <?php echo $item->getName(); ?>
                    </h4>
                    <p class="card-text">
                      <?php echo $item->getDescription(); ?>
                    </p>
                    <p class="card-text">Stock:
                      <?php echo $item->getStock(); ?>
                    </p>
                    <p class="card-text">Price: £
                      <?php echo $item->getPrice(); ?>
                    </p>
                    <a href="/product?productID=<?php echo $item->getProductID(); ?>" class='btn btn-primary'>Product Page</a>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <footer>
    <?php include __DIR__ . '/footer.php'?>
  </footer>

  <!-- JavaScript to handle category change and redirection -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      // Get the category dropdown
      const categoryDropdown = document.getElementById('categoryDropdown');
      
      // Add event listener for change event
      categoryDropdown.addEventListener('change', function (event) {
        // Get the selected category value
        const selectedCategory = event.target.value;
        
        // Redirect to the selected category page
        window.location.href = `/products?category=${selectedCategory}`;
      });
      
      // Highlight the current category
      const currentCategory = '<?php echo isset($_GET["category"]) ? $_GET["category"] : "" ?>';
      const dropdownItems = categoryDropdown.querySelectorAll('.dropdown-item');
      dropdownItems.forEach(item => {
        if (item.textContent === currentCategory) {
          item.classList.add('active');
        } else {
          item.classList.remove('active');
        }
      });
    });
  </script>

</body>

</html>

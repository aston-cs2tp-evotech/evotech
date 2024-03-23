<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

global $userInfo;
if (isset($_SESSION["uid"])) {
    ReLogInUser(); 
}

// Check if $userInfo is set, and then set the username
if (isset($userInfo)) {
  $username = $userInfo->getUsername();
}

// Get an image of the first product in each category

// list of categories
$categories = array("Components", "CPUs", "Graphics Cards", "Cases", "Memory", "Storage");

// Create an associative array of the categories and their product
$categoryProduct = array();

// Loop through each category
foreach ($categories as $category) {
    // Get the first product in the category
    $product = GetAllByCategory($category)[0];
    // Add the product to the associative array
    $categoryImages[$category] = $product;
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
    <link rel="stylesheet" type="text/css" href="/view/css/home.css">
    <title>Home - EvoTech</title>
    <ion-icon name="desktop-outline"></ion-icon>
 

</head>

<nav>
    <?php 
    $currentPage = "home";
    include __DIR__ . '/nav.php';
    ?>
</nav>

<body>
     
    <!--Empty box to fix formatting error 
        Creates an empty above content to prevent main content  being covered by navbar
    -->
    <!-- <section class="bg-dark text-light p-5 text-center text-sm-start py-5">

    </section> -->
    

    <section class="bg text-light p-5 my text-center text-sm-start py-5" style="background-color:#534B62; margin-top: 20px;">
        <div class="container">
            <div class="d-sm-flex allign-items-center justify-content-between">
                <div>


                    <h1>evotech; Evolving your tech</h1>
                    <h1>
                        Shop the latest technology at evotech;
                    </h1>
                    
                </div>
               
            </div>
        </div>

    
        <!--List of different categories-->
    
    <section class="py-5">
      <div class="card " id="largeCard">
        <div class="card-body">
          <?php for ($i = 0; $i < count($categories); $i += 3): ?>
            <div class="row">
              <div class="row text-center p card-container">
                <?php for ($j = $i; $j <= $i + 2 && $j < count($categories); $j++): ?>
                  <div class="col-md-4 p-1 ">
                    <div class="card bg text-dark">
                      <?php $currentCategory = $categories[$j]; ?>
                      <?php if (isset($categoryImages[$currentCategory])): ?>
                        <a href="/products?category=<?php echo $currentCategory; ?>">
                          <img src="view/images/products/<?php echo $categoryImages[$currentCategory]->getProductID();?>/<?php echo $categoryImages[$currentCategory]->getMainImage();?>" class="<?= $currentCategory ?>-card-img-top" alt="Product Image">
                        </a>
                        <div class="card-body">
                          <h5 class="<?= $currentCategory ?>-text">
                            <a href="/products?category=<?php echo $currentCategory; ?>" class="text-decoration-none text-dark"><?php echo $currentCategory; ?></a>
                          </h5>
                        </div>
                      <?php else: ?>
                        <!-- Default image or alternative content if no image is available -->
                        <div class="bg-dark shadow-sm mx-auto" style="width: 100%; height: 100%; border-radius: 21px 21px 0 0;"></div>
                      <?php endif; ?>
                    </div>
                  </div>
                <?php endfor; ?>
              </div>
            </div>
          <?php endfor; ?>
        </div>
      </div>
    </section>



      <hr>
      <section id="statements">
      <h1>
      evotech : Making hardware accessible to all <br> users, prioritising inclusivity in technology solutions
        <div class="container p-3">
            <div class="row justify-content-between">
              <div class=" col-sm-4">
              <br>  
              <h2>
                evotech : Guiding hardware needs, with online shopping and support services.
              </h2> 
              </div>
              <div class="col-sm-6">
              <br>  
              <h3>For further details on evotech; and its wide range of products, simply click the link below </h3>
                <a id="LearnMore-Link" href="aboutus">Learn more about evotech; <i class="bi bi-arrow-right" style="color: white;"></i>
                <hr>
                </a>
              </div>
            </div>
          </div>

      

    </section>
</section>


  
<footer>
    <?php include __DIR__ . '/footer.php'?>

</footer>

    

                    


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
</body>



</html>
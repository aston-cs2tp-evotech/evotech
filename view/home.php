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
    <?php include __DIR__ . '/nav.php'?>
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
                    <h1>Evotech; Slogan</h1>
                    <h1 >
                        Shop the latest technology at evotech
                    </h1>
                    
                </div>
               
            </div>
        </div>

        <section class="p-5">
            <div class="card" id="largeCard">
                <div class="card-body">
                    <div class="row">
                        <div class="row text-center p card-container" >
                            <div class="col-md p-1" >
                                <div class="card bg text-dark" >
                                    <img src="view/images/Motherboard.png" style="max-width: 40%; max-height: 40%;" class="card-img-top" alt="Motherboard image">
                                    <div class="card-body">
                                      <h5 class="card-title">Components</h5>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="col-md p-1 ">
                                <div class="card text-dark" >
                                    <img src="view/images/cpu.jpg" style="max-width: 40%; max-height:40%;" class="card-img-top" alt="CPU image">
                                    <div class="card-body">
                                        <h5 class="card-title">CPUs</h5>
                                    </div>
                                </div>   
            
                            </div>
                            <div class="col-md p-1">
                               <div class="card text-dark" >
                                  <img src="view/images/Graphics card.webp" style="max-width: 40%; max-height: 40%;" class="card-img-top" alt="Graphics Card image">
                                  <div class="card-body">
                                    <h5 class="card-title">GPUs</h5>
                            
                                  </div>  
                                </div>
            
                            </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="row text-center p card-container" >
                            <div class="col-md p-1" >
                                <div class="card bg text-dark" >
                                    <img src="view/images/Case.png" style="max-width: 40%; max-height: 40%;" class="card-img-top" alt="Cases image">
                                    <div class="card-body">
                                      <h5 class="card-title">Cases</h5>
                                      
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="col-md p-1">
                                <div class="card  text-dark" >
                                    <img src="view/images/Ram.png" style="max-width: 40%; max-height: 40%;" class="card-img-top" alt="Ram image">
                                    <div class="card-body">
                                      <h5 class="card-title">Memory</h5>
                                      
                                    </div>
                                </div>   
            
                            </div>
                            <div class="col-md p-1">
                               <div class="card text-dark" >
                                  <img src="view/images/SSD.png" style="max-width: 40%; max-height: 40%;" class="card-img-top" alt="SSD image"> 
                                  <div class="card-body">
                                    <h5 class="card-title">Storage</h5>
                                    
                                  </div>  
                                </div>
            
                            </div>
                        </div>
                     </div>
                  
                </div>
              </div>
        </section>

      <hr>
      <section id="statements">
      <h1>
      Evotech: Making hardware accessible to all <br> users, prioritising inclusivity in technology solutions
        <div class="container p-3">
            <div class="row justify-content-between">
              <div class="col-4">
              <br>  
              <h2>
                Evotech: Guiding hardware needs, with online shopping and support services.
              </h2> 
              </div>
              <div class="col-6">
              <br>  
              <h3>For further details on Evotech and its wide range of products, simply click the link below </h3>
                <a id="LearnMore-Link" href="aboutus">Learn more about evotech <i class="bi bi-arrow-right" style="color: white;"></i>
                <hr>
                </a>
              </div>
            </div>
          </div>

       

      
      

    </section>
</section>


    <!--Boxes-
    <section class="p-5">
        <div class="container">
         <div class="row">
            <div class="row text-center">
                <div class="col-md">
                    <div class="card bg-light text-dark" >
                        <i class="bi bi-truck" style="font-size: 80px; color: black;"></i>
                        <div class="card-body">
                          <h5 class="card-title">Free Delivery</h5>
                          <p class="card-text">Across UK and Global Orders</p>
                        </div>
                        
                    </div>
                </div>
                <div class="col-md">
                    <div class="card bg-light text-dark" >
                        <i class="bi bi-lightning-charge-fill" style="font-size: 80px; color: black;"></i>
                        <div class="card-body">
                          <h5 class="card-title">Extended Warranty</h5>
                          <p class="card-text">12 Month Quality Guarantee</p>
                        </div>
                    </div>   

                </div>
                <div class="col-md">
                   <div class="card bg-light text-dark" >
                      <i class="bi bi-person-circle" style="font-size: 80px; color: black;"></i>
                      <div class="card-body">
                        <h5 class="card-title">User Choices</h5>
                        <p class="card-text">'Reviewed and Rated' by Real Users</p>
                      </div>  
                    </div>

                </div>
            </div>
         </div>
        </div>
    </section>
   -->

    <!--List of different categories-->

    <?php /*

    <?php for ($i = 0; $i < count($categories); $i += 2): ?>
    <section class="d-md-flex flex-md-equal my-md-3 ps-md-3">
        <?php for ($j = $i; $j <= $i + 1 && $j < count($categories); $j++): ?>
            <?php $currentCategory = $categories[$j]; ?>
            <div style="background-color: #534B62 ;" class="bg me-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden flex-grow-1">
                <div class="my-3 p-3">
                    <h2 class="<?= $currentCategory ?>">
                        <a href="/products?category=<?php echo $currentCategory; ?>" class="text-decoration-none text-dark"><?php echo $currentCategory; ?></a>
                    </h2>
                    <p class="Shop-now">Shop Now.</p>
                </div>
                <div class="bg-dark shadow-sm mx-auto" style="width: 80%; height: 300px; border-radius: 21px 21px 0 0;">
                    <?php if (isset($categoryImages[$currentCategory])): ?>
                        <a href="/products?category=<?php echo $currentCategory; ?>" class="text-decoration-none text-dark">
                            <img src="view/images/products/<?php echo $categoryImages[$currentCategory]->getProductID();?>/<?php echo $categoryImages[$currentCategory]->getMainImage();?>" class="card-img" alt="Product Image">
                        </a>
                    <?php else: ?>
                        <!-- Default image or alternative content if no image is available -->
                        <div class="bg-dark shadow-sm mx-auto" style="width: 100%; height: 100%; border-radius: 21px 21px 0 0;"></div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endfor; ?>
    </section>
<?php endfor; ?>
*/ ?>

                    

  <!-- FAQ
    <section id="Faq" class="p-4">
        <div class="container">
            <h2 class="text-center mb-4">Frequently Asked Questions</h2>
            <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                  <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <strong> 
                            What is Evotech?
                        </strong>
                    </button>
                  </h2>
                  <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                      <h5>Evotech is a collective of engineers, artists, and creators. We built Evotech, an e-commerce platform that recommends and sells optimum solutions for real-world problems.</h5> 
                    </div>
                  </div>
                </div>
                <div class="accordion-item">
                  <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"> <strong>
                      What solutions does Evotech sell?
                    </strong>
                    </button>
                  </h2>
                  <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                      <h5>If there's a problem, we have the technology for you. Check our Shop to view the CPU's, Accessories, Monitors and other Components that we sell.</h5> 
                    </div>
                  </div>
                </div>
                <div class="accordion-item">
                  <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                      <strong>
                        I have further questions, where can I get in touch?
                    </strong>  
                    </button>
                  </h2>
                  <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                      <h5>You can Contact Us, for further questions. </h5> 
                    </div>
                  </div>
                </div>
              </div>
        </div>

    </section>
        -->
                    

                    

    <!--Customer Reviews-
    <section id="instructors" class="p-5 bg-light">
        <div class="container">
            <h2 class="text-center text-dark">Customer Reviews</h2>
            <p class="lead text-center text-dark mb-5">
                See what our customers have to say about us 

            </p>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                  <div class="card bg-light"> 
                    <div class="card-body text-center">
                        <img src="view/images/Review1.png" width="200px"
                         class="rounded-circle mb-3"
                         alt="Review image"/>
                         <p class="card-text">"Evotech is my go-to! Unbeatable prices, top notch quality and extended warranty. Highly recommend!"  </p>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-lg-4">
                   <div class="card bg-light"> 
                    <div class="card-body text-center">
                        <img src="view/images/Review2.JPG" width="200px"
                         class="rounded-circle mb-3"
                         alt=""/>
                         <p class="card-text">"Soild prices, fast delivery and fantastic service, will be a repeat customer!"</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card bg-light">
                    <div class="card-body text-center">
                        <img src="view/images/Review3.jpg" width="200px"
                         class="rounded-circle mb-3"
                         alt=""/>
                         <p class="card-text">"Evotech exceeded my expectations. Delivery was lighting-fast, a go to for computer components"</p>
                     </div>
                  </div>
                </div>
            </div>
        </div>
    </section>
                    -->

                    

  
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

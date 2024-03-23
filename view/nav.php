<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" type="text/css" href="/view/css/nav.css">
    <title>EvoTech</title>
    <ion-icon name="desktop-outline"></ion-icon>
    <?php
    // Check if current page is set, if not set it to home
    if (!isset($currentPage)) {
        $currentPage = "home";
    }
    ?>

</head>

<nav class="navbar navbar-dark navbar-expand-lg fixed-top py-1">
        <div class="container-fluid">
            <a class="navbar-brand me-auto" href=home> <img src="view/images/evotechLogoCropped.png" style="width: 100px; height: 25px;" alt="Evotech Logo"> </a>
            <!-- <a class="navbar-brand me-auto" href="home">Evotech</a> -->
            <div class="offcanvas offcanvas-dark bg-dark offcanvas-end" tabindex="-1" id="offcanvasNavbar"
                aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel" style="color:white">Evotech</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-center flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link <?php if ($currentPage == "home") {echo "active";}?> aria-current="page" href="home">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if ($currentPage == "aboutus") {echo "active";}?>" href="aboutus">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if ($currentPage == "products") {echo "active";}?>" href="products">Products</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if ($currentPage == "contactpage") {echo "active";}?>" href="contactpage">Contact</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if ($currentPage == "basket") {echo "active";}?>" href="basket">
                                <i class="nav-link bi bi-basket">
                                    Basket
                                </i>
                            </a>
                        </li>

                </div>
            </div>
            
            <!--<a href="login" class="login-button">Login</a>-->
            <?php
            // Check if the user is logged in
            if (isset($_SESSION['uid'])) {
                
                echo "<a href='customer' class='login-button'>Logged in as $username</a>";
            } else {
                echo "<a href='login' class='login-button'>Login</a>";
            }
            ?>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

 <body>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
 </body>
</html>

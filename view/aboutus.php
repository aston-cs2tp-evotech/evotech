<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

global $userInfo;
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
    <title>About Us - EvoTech</title>
    <link rel="stylesheet" href="/view/css/aboutus.css">
    
</head>
<body>
  <?php include __DIR__ . '/nav.php'?>

  <section class="bg-success p-5  py-4">

    </section>
  
    <header>
        <h1>About Us</h1>
    </header>

    <div class="container">
        <!-- <img src="" alt=""> -->
      
        <h2>Our Story, the Evotech Effect</h2>
        <p>Evotech began after our team struggled to find the best technology components and accessories to help our daily problems. That's why we made Evotech, an e-commerce store with a wide range of products chosen by our team, and a community like no other.</p>

        <h2>Our Team/Contributors</h2>
        <p>We have the best engineers, designers and product specialists in our team. That's what helps us stay a cut above the rest in selecting and providing the best technology to help you work hard and play harder. Our team regularly reviews products we use on a daily basis, and updates these to Evotech. For any further questions about our opinions on products or Evotech as a collective, contact us below.</p>
        <div class="row">
            <div class="column">
              <div class="card">
                <!-- <img src="" alt="" style="width:100%">  -->
                <div class="container">
                  <h2>Hanzalah</h2>
                  <p class="title">Lead Product Specialist</p>
                  <p>I choose the best options, so you save time. Former product engineer, now specialising in motherboards, CPU's and other computer components.</p>
                  <p>hanzalah@evotech.com</p>
                  <p><button class="button">Contact</button></p>
                </div>
              </div>
            </div>
          
            <div class="column">
              <div class="card">
                <!-- <img src="" alt="" style="width:100%"> -->
                <div class="container">
                  <h2>Aaron</h2>
                  <p class="title">Illustrator</p>
                  <p>Better technology in illustration and design helps produce much better results, we source and supply technology that meets real-world needs, for creative-minded people. </p>
                  <p>aaron@evotech.com</p>
                  <p><button class="button">Contact</button></p>
                </div>
              </div>
            </div>
          
            <div class="column">
              <div class="card">
                <!-- <img src="" alt="" style="width:100%"> -->
                <div class="container">
                  <h2>Tom</h2>
                  <p class="title">Architect Engineer</p>
                  <p>Running CAD/CAM software is resource intensive, and a lot of my working time. That's why Evotech exists, to improve efficiency and ergonomics at work.</p>
                  <p>tom@evotech.com</p>
                  <p><button class="button">Contact</button></p>
                </div>
              </div>
            </div>
          </div>
        <h2>Get In Touch</h2>
        <p>If you have any questions or concerns, <a href="contactpage">contact us</a>. Evotech is for Everyone.</p>
    </div>

    <footer>
    <?php include __DIR__ . '/footer.php'?>

</footer>



</body>
</html>

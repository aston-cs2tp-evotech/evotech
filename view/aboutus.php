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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Evotech</title>
    <link rel="stylesheet" href="/view/css/aboutus.css">
    
</head>
<body>
  <?php include __DIR__ . '/nav.php'?>

  <section class="bg-success p-5  py-4">

    </section>
   <div class="background-box">
        <div class="container">
            <section class="about-section">
                <h2>What is Evotech?</h2>
                <p>Evotech's for all. The ones that create, work and play. We know that sometimes not having the right gear can hold you back, that's why we created Evotech.
                   Find selected hardware options and filter through categories, upgrade your gear based on the reviews of experts and our community. We're glad you're here. </p>
            </section>

            <section class="services-section">
                <h2>Our Services</h2>
                <div class="service">
                    <h4>Category Filtered Products</h4>
                    <p>Choose from six categories of hardware, and find purchasable products to upgrade your setup.</p>
                </div>
                <div class="service">
                    <h4>Useful Recommendations</h4>
                    <p>Based on similar orders and products, we will recommend great complementary products below each listing.</p>
                </div>
                <div class="service">
                    <h4>Community Reviews</h4>
                    <p>Let the Evotech community inform your decisions, with real reviews and opinions on the latest gear.</p>
                </div>
            </section>

            <section class="contact-section">
                <h2><a href="contactspage.php" class="contact-link">Support Team</a></h2>
                <div class="contact-info">
                    <div class="contact-person">
                        <h4>Aaron</h4>
                        <p>Email: aaron@evotech.com<br>Phone: +449765932789</p>
                    </div>
                    <div class="contact-person">
                        <h4>Tom</h4>
                        <p>Email: tom@evotech.com<br>Phone: +448267985635</p>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <footer>
    <?php include __DIR__ . '/footer.php'?>

</footer>



</body>
</html>

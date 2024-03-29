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
        <title>Contact Us - Evotech</title>
        <link rel="stylesheet" href="/view/css/contactpage.css">
    </head>

    <nav>
        <?php 
        $currentPage = "contactpage";
        include __DIR__ . '/nav.php';
        ?>
    </nav>

    <body>
        

        <section class="bg-success p-5  py-4">

        </section>
        <header>
            <h1>Contact Evotech</h1>
        </header>

        <section class="contact-container" >
            <div class="contact-form">
                <h2>Contact Form</h2>
                <form id="contactForm" action="mailto:info@evotech.com" method="get" enctype="text/plain">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="description">Description of issue:</label>
                    <textarea id="description" name="description" rows="4" required></textarea>

                    <label for="date">Date:</label>
                    <input type="date" id="date" name="date" required>

                    <button type="submit">Submit</button>
                    <script src="/view/js/contacspage.js"></script>
                </form>

            </div>

            <div class="contact-info">
                <h2>Contact Information</h2>
                <p>Email: info@evotech.com</p>
                <p>Phone: 1234567891011</p>
                <p>Address: Aston St, Birmingham B4 7ET.</p>
            </div>
        </section>
        <div></div>
        
        <!-- faq -->
        <section id="Faq" class="p-4">
            <div class="container">
                <h2 class="text-center mb-4">Frequently Asked Questions</h2>
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <h5>What is Evotech?</h5> 
                                
                            
                    
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
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"> 
                            <h5>What solutions does Evotech sell?</h5>
                        
                        
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
                        <h5>I have further questions, where can I get in touch?</h5>
                            
                        
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
    <section class="p-4"><div class="customer-reviews" >
        <div class="container" >
            <h2>Customer Reviews</h2>
            <p>See what our customers have to say about us</p>
            <div class="row">
                <div class="card">
                    <img src="view/images/Review1.webp" alt="Customer 1"/>
                    <p>Evotech goes beyond selling; their customer support is stellar...</p>
                    <p>Date: 01/01/2023</p>
                </div>
                <div class="card">
                    <img src="view/images/Review2.webp" alt="Customer 2"/>
                    <p>Responsive team and excellent communication...</p>
                    <p>Date: 15/02/2023</p>
                </div>
                <div class="card">
                    <img src="view/images/Review3.webp" alt="Customer 3"/>
                    <p>Evotech went above and beyond our expectations...</p>
                    <p>Date: 13/06/2023</p>
                </div>
            </div>
        </div>
    </div>

    </section>
    




        <!--
        <section class="customer-reviews-section">
            <h2>Customer Reviews</h2>
            <div class="row">
                <div class="col-sm">
                    <div class="customer-reviews">
                        <div class="review-box">
                            <img src="view/images/ginge.jpg" alt="Customer 1">
                            <p>"Evotech goes beyond selling; their customer support is stellar. Quick responses, knowledgeable staff – they make tech troubleshooting a breeze, setting a gold standard for customer care."</p>
                            <p>Date: 01/01/2023</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="customer-reviews">
                        <div class="review-box">
                            <img src="view/images/mrbean.jpg" alt="Customer 2">
                            <p>Responsive team and excellent communication. We are satisfied with their work.</p>
                            <p>Date: 15/02/2023</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="customer-reviews">
                        <div class="review-box">
                            <img src="view/images/danny.webp" alt="Customer 3">
                            <p>"EvoTech went above and beyond our expectations. Will definitely will buy there products again."</p>
                            <p>Date: 13/06/2023</p>
                        </div>
                    </div>
                </div>
            </div>
            </section>
            !-->

        <footer>
        <?php include __DIR__ . '/footer.php'?>

    </footer>
    </body>

    </html>
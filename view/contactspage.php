<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - EvoTech</title>
    <link rel="stylesheet" href="view/css/contactpage.css">
</head>
<body>
   <?php include __DIR__ . '/nav.php'?>

   <section class="bg-success p-5  py-4">

    </section>
    <header>
        <h1>Contact EvoTech</h1>
    </header>

    <section class="contact-container">
        <div class="contact-form">
            <h2>Contact Form</h2> 
            <form id="contactForm">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="description">Description of issue:</label>
    <textarea id="description" name="description" rows="4" required></textarea>

    <label for="date">Date:</label>
    <input type="date" id="date" name="date" required>

    <button type="submit">Submit</button>
    <script src="contacspage.js"></script>
</form>

        </div>

        <div class="contact-info">
            <h2>Contact Information</h2>
            <p>Email: info@evotech.com</p>
            <p>Phone: 1234567891011</p>
            <p>Address: Aston St, Birmingham B4 7ET.</p>
        </div>
    </section>

    <section class="customer-reviews-section">
        <h2>Customer Reviews</h2>
        <div class="customer-reviews">
            <div class="review-box">
                <img src="customer1.jpg" alt="Customer 1">
                <p>Great service! very good products and highly recommended.</p>
                <p>Date: 01/01/2023</p>
            </div>

            <div class="review-box">
                <img src="customer2.jpg" alt="Customer 2">
                <p>Responsive team and excellent communication. We are satisfied with their work.</p>
                <p>Date: 15/02/2023</p>
            </div>

            <div class="review-box">
                <img src="customer3.jpg" alt="Customer 3">
                <p>EvoTech went above and beyond our expectations. Will definitely work with them again.</p>
                <p>Date: 13/06/2023</p>
            </div>
        </div>
    </section>

   
</body>
</html>

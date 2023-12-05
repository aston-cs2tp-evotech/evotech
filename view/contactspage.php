<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - EvoTech</title>
    <link rel="stylesheet" href="view/css/contactpage.css">
</head>
<body>
    <header>
        <h1>Contact EvoTech</h1>
    </header>

    <section class="contact-container">
        <div class="contact-form">
            <form id="contactForm">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" required></textarea>

               
                <button type="button" onclick="submitForm()">Submit</button>
            </form>
        </div>

        <div class="contact-info">
            <h2>Contact Information</h2>
            <p>Email: info@evotech.com</p>
            <p>Phone: ??</p>
            <p>Address: ??</p>
        </div>
    </section>

    <script src="script.js"></script>
</body>
</html>


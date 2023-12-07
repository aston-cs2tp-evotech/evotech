<?php

// Start session
session_start();

// Include the database connection
include 'config/database.php';

// Include models
require __DIR__ . '/model/Customer.php';
$Customer = new CustomerModel($pdo);

// Include the controller
require __DIR__ . '/controller/Controller.php';

// Routing
$request = $_SERVER['REQUEST_URI'];
$requestPath = parse_url($request, PHP_URL_PATH);

switch ($requestPath) {

    case '/':
    case '/home':
        handleHomeRequest();
        break;

    case '/aboutus':
        handleAboutUsRequest();
        break;

    case '/login':
        handleLoginRequest();
        break;

    case '/register':
        handleRegisterRequest();
        break;
    
    case '/contactpage':
        handleContactPageRequest();
        break;
    
    default:
        handle404Request();
        break;
}


/**
 * Handles home page requests
 * 
 * @return void
 */
function handleHomeRequest() {
    require __DIR__ . '/view/home.php';
}

/**
 * Handle about us page requests
 * 
 * @return void
 */
function handleAboutUsRequest() {
    require __DIR__ . '/view/aboutus.php';
}

/**
 * Handle login page requests
 * 
 * @return void
 */
function handleLoginRequest() {
    global $pdo;


}

/**
 * Handle registration page requests
 * 
 * @return void
 */
function handleRegisterRequest() {
    global $pdo;

    switch ($_SERVER['REQUEST_METHOD']) {

        // Display the registration form for GET requests
        case 'GET':
            require __DIR__ . '/view/register.php';
            break;
        
        // Handle registration form submission for POST requests
        case 'POST':
            
            $registrationResult = RegisterUser($_POST);
            
            // Send the user back to the homepage if registration was successful
            if ($registrationResult === "") {
                header("Location: /");
                exit();
            } else {
                // Display the registration form with an error message if registration failed
                require __DIR__ . '/view/register.php';
            } 
    }
}

/**
 * Handle contact page requests
 * 
 * @return void
 */
function handleContactPageRequest() {
    require __DIR__ . '/view/contactspage.php';
}

/**
 * Handle 404 page requests
 * 
 * @return void
 */
function handle404Request() {
    http_response_code(404);
    require __DIR__ . '/view/404.php';
}

?>

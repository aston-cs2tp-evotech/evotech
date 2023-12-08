<?php

// Start session
session_start();

// Create connection to database
include __DIR__ . "/config/database.php";

// Create Models
include __DIR__ . "/model/Customer.php";
include __DIR__ . "/model/Products.php";
include __DIR__ . "/model/Orders.php";

// Initalise $userInfo
$userInfo = array();

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

    case '/logout':
        handleLogoutRequest();
        break;
    
    case '/contactpage':
        handleContactPageRequest();
        break;
    
    case '/basket':
        handleBasketPageRequest();
        break;
    
    case '/product':
        handleProductPageRequest();
        break;

    case '/checkout':
        handleCheckoutPageRequest();
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

    switch ($_SERVER['REQUEST_METHOD']) {

        // Display the login form for GET requests
        case 'GET':
            require __DIR__ . '/view/login.php';
            break;
        
        // Handle login form submission for POST requests
        case 'POST':
            
            $usernameOrEmail = $_POST['usernameOrEmail'];
            $password = $_POST['password'];

            $loginResult = AttemptLogin($usernameOrEmail, $password);

            // Send the user back to the homepage if login was successful
            if ($loginResult) {
                header("Location: /");
                exit();
            } else {
                // Display the login form with an error message if login failed
                require __DIR__ . '/view/login.php';
            }
    }

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
 * Handle logout requests
 * 
 * @return void
 */
function handleLogoutRequest() {
    LogOut();
    header("Location: /");
    exit();
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

/**
* Handle requests to the product page
*
* @return void  
*/
function handleProductPageRequest(){
    require __DIR__ . '/view/productpage.php';
}

/**
* Handle requests to the basket
* 
* @return void
*/
function handleBasketPageRequest(){
    if (!isset($_SESSION['uid'])){
        header("Location:/");
    } else{
        require __DIR__ . '/view/basket.php';
    }
}

/**
 * handle requests to the checkout page
 * 
 * @return void
 */
function handleCheckoutPageRequest(){
    require __DIR__ . '/view/checkout.php';
}

?>

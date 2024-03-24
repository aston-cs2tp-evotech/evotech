<?php

// Routing
$request = $_SERVER['REQUEST_URI'];
$requestPath = parse_url($request, PHP_URL_PATH);

// Check if OOBE has been run and if not, display OOBE page and exit
if (!file_exists(__DIR__ . "/config/oobeHasRun")) {
    switch ($requestPath) {
        case '/oobe':
            require __DIR__ . '/oobe/oobe.php';
            break;
        case '/oobe/checkDBConnection':
            require __DIR__ . '/oobe/checkDBConnection.php';
            break;
        case '/oobe/setupDatabase':
            require __DIR__ . '/oobe/setupDatabase.php';
            break;
        case '/oobe/setupAdmin':
            require __DIR__ . '/oobe/setupAdmin.php';
            break;
        case '/oobe/finishSetup':
            require __DIR__ . '/oobe/finishSetup.php';
            break;
        case '/oobe/style.css':
            header('Content-Type: text/css');
            require __DIR__ . '/oobe/style.css';
            break;
        default:
            require __DIR__ . '/oobe/oobe.php';
            break;
    }
    exit();
}


// Start session
session_start();

// Create connection to database
include __DIR__ . "/config/database.php";

// Create Models
include __DIR__ . "/model/Customer.php";
include __DIR__ . "/model/Products.php";
include __DIR__ . "/model/Orders.php";
include __DIR__ . "/model/Admin.php";

// Include the controller
require __DIR__ . '/controller/Controller.php';

/**
 * @var Customer|null $userinfo Customer object if initialized
 */
$userInfo = null;

if (isset($_SESSION["uid"])) {
    ReLogInUser(); 
}

// Check if Username is set in $userInfo and then set $username
if (isset($userInfo)) {
    $username = $userInfo->getUsername();
}


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

    case '/products':
        handleProductsPageRequest();
        break;

    case '/add-to-basket':
        handleAddToBasketRequest();
        break;

    case '/update-basket':
        handleUpdateBasketRequest();
        break;
    
    case '/checkout':
        handleCheckoutPageRequest();
        break;

    case '/checkoutProcess':
        handleCheckoutProcessRequest();
        break;

    case '/cancelOrder':
        handleCancelRequest();
        break;
    case '/orderSuccess':
        require __DIR__ . '/view/orderConfirmation.php';
        break;
    
    case '/orderFailed':
        require __DIR__ . '/view/orderFailed.php';
        break;

    case '/customer':
        handleCustomerPageRequest();
        break;
    
    case '/change-password':
        handleChangePasswordRequest();
        break;
    
    case '/leaveReview':
        handleLeaveReviewRequest();
        break;
    
    case '/removeReview':
        handleRemoveReviewRequest();
        break;

    case '/updateReview':
        handleUpdateReviewRequest();
        break;
    
    case '/admin':
        handleDashboardRequest();
        break;

    case '/adminLogin':
        handleAdminLogin();
        break;
    
    case '/adminLogout':
        handleAdminLogout();
        break;

    case '/api/updateOrderStatus':
        handleAPIRequest('updateOrderStatus');
        break;

    case '/api/getProduct':
        handleAPIRequest('getProduct');
        break;

    case '/api/addProduct':
        handleAPIRequest('addProduct');
        break;

    case '/api/editProduct':
        handleAPIRequest('editProduct');
        break;
    
    case '/api/deleteProduct':
        handleAPIRequest('deleteProduct');
        break;
    
    case '/api/getCustomer':
        handleAPIRequest('getCustomer');
        break;
    
    case '/api/editCustomer':
        handleAPIRequest('editCustomer');
        break;

    case '/api/deleteCustomer':
        handleAPIRequest('deleteCustomer');
        break;

    case '/api/getAdmin':
        handleAPIRequest('getAdmin');
        break;

    case '/api/editAdmin':
        handleAPIRequest('editAdmin');
        break;

    case '/api/addAdmin':
        handleAPIRequest('addAdmin');
        break;
    
    case '/api/deleteAdmin':
        handleAPIRequest('deleteAdmin');
        break;

    case '/api/refreshToken':
        handleAPIRequest('refreshToken');
        break;
    
    case '/api/revokeToken':
        handleAPIRequest('revokeToken');
        break;

    case '/api/addToken':
        handleAPIRequest('addToken');
        break;

    case '/api/deleteCustomerReviews':
        handleAPIRequest('deleteCustomerReviews');
        break;
    
    case '/api/deleteProductReviews':
        handleAPIRequest('deleteProductReviews');
        break;
    
    case '/api/deleteReview':
        handleAPIRequest('deleteReview');
        break;

    case '/api/editReview':
        handleAPIRequest('editReview');

    case '/api/getAllReviews':
        handleAPIRequest('getAllReviews');
        break;
    
    case '/api/getCustomerReviews':
        handleAPIRequest('getCustomerReviews');
        break;

    case '/api/getProductReviews':
        handleAPIRequest('getProductReviews');
        break;

    case '/api/returnOrder':
        handleAPIRequest('returnOrder');
        break;

    case '/api/cancelOrder':
        handleAPIRequest('cancelOrder');
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
function handleProductPageRequest() {
    global $Product;

    // Get the product ID from the URL parameter
    $productID = isset($_GET['productID']) ? intval($_GET['productID']) : 0;

    // Fetch product details based on the product ID
    $productDetails = GetProductByID($productID);

    // If the product is not found, handle it accordingly (e.g., redirect to 404)
    if (!$productDetails) {
        handle404Request();
        return;
    }

    // Include the productpage.php view and pass product details
    require __DIR__ . '/view/productpage.php';
}


/**
 * Handle requests to the products page
 * 
 * @return void
 */
function handleProductsPageRequest(){
   
    // Check if there is a Category in the URL
    $categoryName = isset($_GET['category']) ? $_GET['category'] : null;

    global $products;
    
    if ($categoryName) {
        $products = GetAllByCategory($categoryName);
    }

    if (!is_array($products)) {
        $products = GetAllProducts();
    }

    require __DIR__ . '/view/products.php';
}

/**
 * Handle requests to add a product to the basket
 *
 * @return void
 */
function handleAddToBasketRequest() {
    // Check if the user is logged in
    if (!isset($_SESSION['uid'])) {
        // Redirect to the login page if not logged in
        header("Location: /login");
        exit();
    }

    // Get the product ID and quantity from the POST request
    $productID = isset($_POST['productID']) ? intval($_POST['productID']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    // Add the product to the basket
    $addToBasketResult = AddProductToBasket($productID, $quantity);

    // Redirect to the basket page if the product was added successfully
    if ($addToBasketResult) {
        header("Location: /basket");
        exit();
    } else {
        // Redirect to the product page with an error message if the product could not be added
        header("Location: /basket");
        exit();
    }

}

/**
 * Handle requests to update the quantity of a product in the basket
 * 
 * @return void
 */
function handleUpdateBasketRequest() {
    // Check if the user is logged in
    if (!isset($_SESSION['uid'])) {
        // Redirect to the login page if not logged in
        header("Location: /login");
        exit();
    }

    // Get the product ID and quantity from the POST request
    $productID = isset($_POST['productID']) ? intval($_POST['productID']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    // Update the product in the basket
    $updateBasketResult = ModifyProductQuantityInBasket($productID, $quantity);

    // Redirect to the basket page if the product was updated successfully
    if ($updateBasketResult) {
        header("Location: /basket");
        exit();
    } else {
        // Redirect to the product page with an error message if the product could not be updated
        header("Location: /basket");
        exit();
    }
}

/**
* Handle requests to the basket
* 
* @return void
*/
function handleBasketPageRequest(){
    if (!isset($_SESSION['uid'])){
        $_SESSION['loginMessage'] = "You must be logged in to view your basket";
        header("Location:/login");
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
    // Check if the user is logged in and if there are any items in the basket
    if (!isset($_SESSION['uid'])){
        header("Location:/");
    }

    $basketItems = GetCustomerBasket();

    if (!$basketItems) {
        header("Location:/");
    } else {
        require __DIR__ . '/view/checkout.php';
    }
}

/** 
 * Handle requests to the checkout process
 * 
 * @return void
 */
function handleCheckoutProcessRequest(){

    if (!isset($_SESSION['uid'])){
        header("Location:/");
    }

    $hasCheckedOut = CheckoutBasket();

    if (!$hasCheckedOut) {
        header("Location:/orderFailed");
    } else {
        header("Location:/orderSuccess");
    }
}

/**
 * Handles cancelling or returning orders
 */
function handleCancelRequest() {
    if (!CheckLoggedIn()) header("Location:/");
    if (!CheckExists($_POST["OrderID"])) header("Location:/");

    $returned = CancelOrReturnOrder($_POST["OrderID"], "cancelled");
    
    if ($returned) header("Location:/customer");
    else header("Location:/");
}

/**
 * Handle requests to the customer page
 * 
 * @return void
 */
function handleCustomerPageRequest(){
    // Check if the user is logged in
    if (!isset($_SESSION['uid'])){
        header("Location:/");
    } else {
        require __DIR__ . '/view/customer.php';
    }
}

/**
 * Handle requests to change the password
 * 
 * @return void
 */
function handleChangePasswordRequest(){
    // Check if the user is logged in
    if (!isset($_SESSION['uid'])){
        header("Location:/login");
    } 

    $currentPassword = isset($_POST['current_password']) ? $_POST['current_password'] : null;
    $newPassword = isset($_POST['new_password']) ? $_POST['new_password'] : null;
    $confirmNewPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : null;
    $passwordChangeResult = false;


    $updateArray = array(
        "field" => "password",
        "value" => $currentPassword,
        "newPassword" => $newPassword,
        "confirmPassword" => $confirmNewPassword
    );

    $result = UpdateCustomerDetail($updateArray);
    // Result is a string, if it is empty then the update was successful
    if ($result === "") {
        $passwordChangeResult = true;
        require __DIR__ . '/view/customer.php';
        return;
    } else {
        $passwordChangeResult = false;
        echo $result;
        print_r($updateArray);
        require __DIR__ . '/view/customer.php';
        return;
    }


}

/**
 * Handles request to leave a review
 */
function handleLeaveReviewRequest() {
    if (!CheckLoggedIn()) header("Location:/login");
    //check passed info
    if (!CheckExists($_POST["ProductID"])) header("Location:/home");
    if (!CheckExists($_POST["Rating"])) header("Location:/product?productID=" . $_POST["ProductID"]);
    if (!CheckExists($_POST["Review"])) header("Location:/product?productID=" . $_POST["ProductID"]);

    //check if allowed to leave review
    if (!CheckCanLeaveReview($_SESSION["uid"], $_POST["ProductID"])) header("Location:/home");

    $success = CreateReview($_POST["ProductID"], $_SESSION["uid"], $_POST["Rating"], $_POST["Review"]);
    if (empty($success)) header("Location:/product?productID=" . $_POST["ProductID"]);
    else header("Location:/product?productID=" . $_POST["ProductID"]);
}

/**
 * Handles request to remove a review
 */
function handleRemoveReviewRequest() {
    if (!CheckLoggedIn()) header("Location:/login");

    if (!CheckExists($_POST["ProductID"])) header("Location:/home");

    $success = DeleteReview($_POST["ProductID"], $_SESSION["uid"]);
    if (empty($success)) header("Location:/product?productID=" . $_POST["ProductID"]);
    else header("Location:/product?productID=" . $_POST["ProductID"]);
}

/**
 * Handles request to update a review
 */
function handleUpdateReviewRequest() {
    if (!CheckLoggedIn()) header("Location:/login");
    //check passed info
    if (!CheckExists($_POST["ProductID"])) header("Location:/home");
    if (!CheckExists($_POST["Rating"])) header("Location:/product?productID=" . $_POST["ProductID"]);
    if (!CheckExists($_POST["Review"])) header("Location:/product?productID=" . $_POST["ProductID"]);

    $success = UpdateReview($_POST["ProductID"], $_SESSION["uid"], $_POST["Rating"], $_POST["Review"]);
    if (empty($success)) header("Location:/product?productID=" . $_POST["ProductID"]);
    else header("Location:/product?productID=" . $_POST["ProductID"]);
}

/**
 * Handles requests to view the dashboard
 */
function handleDashboardRequest() {
    PruneTokens();
    if (CheckAdminLoggedIn()) {
        if (VerfiyToken($_SESSION["adminToken"])) {
            require __DIR__ . '/view/admin/dashboard.php';
        } 
        else {
            // Logouut admin
            unset($_SESSION);
            header("Location:/adminLogin");
        }
    } 
    else { 
        header("Location:/adminLogin"); //redirect to login page
    }
}

/**
 * Handles admin logins
 */
function handleAdminLogin() {
    if (!isset($_POST["username"])) {
        require __DIR__ . '/view/AdminLogin.php';
    }
    else {
        if (!isset($_POST["password"])) header("Location:/adminLogin");
        else {
            $result = AttemptAdminLogin($_POST["username"], $_POST["password"]);
            if (empty($result)) header("Location:/admin");
            $_SESSION["loginMessage"] = "Your username or password is incorrect";
            require __DIR__ . '/view/AdminLogin.php';
        }
    }
}

/**
 * Handles admin logouts
 */
function handleAdminLogout() {
    unset($_SESSION);
    session_destroy();
    $_SESSION['loginMessage'] = "You have been logged out";
    header("Location:/adminLogin");
}

/**
 * Handles API requests
 * 
 * @param string $path Path to request
 */
function handleAPIRequest($path) {
    PruneTokens();
    if (!isset($_POST["Token"])) {
        $result = VerfiyToken($_SESSION["adminToken"]);
        if (!$result) { 
            http_response_code(403);
            echo "Access denied";
            return;
        }
    }
    else {
        $result = VerfiyToken($_POST["Token"]);
        if (!$result) { 
            http_response_code(403);
            return;
        }
    }

    switch ($path) {
        case 'updateOrderStatus':
            require __DIR__ . '/api/updateOrderStatus.php';
            break;
        case 'getProduct':
            require __DIR__ . '/api/getProduct.php';
            break;
        
        case 'addProduct':
            require __DIR__ . '/api/addProduct.php';
            break;
        
        case 'editProduct':
            require __DIR__ . '/api/editProduct.php';
            break;
            
        case 'deleteProduct':
            require __DIR__ . '/api/deleteProduct.php';
            break;
            
        case 'getCustomer':
            require __DIR__ . '/api/getCustomer.php';
            break;
            
        case 'editCustomer':
            require __DIR__ . '/api/editCustomer.php';
            break;
        
        case 'deleteCustomer':
            require __DIR__ . '/api/deleteCustomer.php';
            break;
        
        case 'getAdmin':
            require __DIR__ . '/api/getAdmin.php';
            break;
        
        case 'editAdmin':
            require __DIR__ . '/api/editAdmin.php';
            break;
        
        case 'addAdmin':
            require __DIR__ . '/api/addAdmin.php';
            break;
        
        case 'deleteAdmin':
            require __DIR__ . '/api/deleteAdmin.php';
            break;

        case 'refreshToken':
            require __DIR__ . '/api/refreshToken.php';
            break;

        case 'revokeToken':
            require __DIR__ . '/api/revokeToken.php';
            break;
        
        case 'addToken':
            require __DIR__ . '/api/addToken.php';
            break;

        case 'deleteCustomerReviews':
            require __DIR__ . '/api/deleteCustomerReviews.php';
            break;

        case 'deleteProductReviews':
            require __DIR__ . '/api/deleteProductReviews.php';
            break;

        case 'deleteReview':
            require __DIR__ . '/api/deleteReview.php';
            break;

        case 'editReview':
            require __DIR__ . '/api/editReview.php';
            break;

        case 'getAllReviews':
            require __DIR__ . '/api/getAllReviews.php';
            break;

        case 'getCustomerReviews':
            require __DIR__ . '/api/getCustomerReviews.php';
            break;

        case 'getProductReviews':
            require __DIR__ . '/api/getProductReviews.php';
            break;

        case 'returnOrder':
            require __DIR__ . '/api/returnOrder.php';
            break;

        case 'cancelOrder':
            require __DIR__ . '/api/cancelOrder.php';
            break;
            
        default:
            handle404Request();
            break;
    }
}


?>

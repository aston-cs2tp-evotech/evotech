<?php

require("/model/Customer.php");
require("/model/Products.php");
require("/model/Orders.php");

// ---------------------------------------------
//
// FUNCTIONS RELATING TO CUSTOMER AND MAIN PAGE
//
// ---------------------------------------------

/**
 * @var array Global variable to store customer info (username, address etc)
 */
global $userInfo;

/**
 * @var PDO The database connection
 */
global $pdo;

/**
 * @var CustomerModel The customer model for interacting with the database
 */
$Customer = new CustomerModel($pdo);

/**
 * @var ProductModel The product model for interacting with the database
 */
$Product = new ProductModel($pdo);

/**
 * @var OrdersModel The orders model for interacting with the database
 */
$Order = new OrdersModel($pdo);

/**
 * Check if a variable is safe to evaluate
 * @param mixed $var Variable to check
 * @return boolean True if $var is safe and exists, otherwise false
 */
function checkExists($var) {
    return (isset($var) && !empty($var));
}

/** 
 * Puts all relevent user info into the global userInfo array
*/
function reLogInUser() {
    global $userInfo, $Customer;
    //checks if it is set and not false
    if (checkExists($_SESSION["uid"])) {
        $uid = $_SESSION["uid"];
        //querys the database to get user info
        $userInfo = $Customer->getCustomerByUID($uid);
    } 
}

/**
 * Checks if the user is logged in
 * @return boolean True if logged in, false if not
 */
function checkLoggedIn() {
    global $userInfo;
    return (checkExists($_SESSION["uid"]) && checkExists($userInfo));
}

/**
 * Attempts to log the user in using supplied credentials
 * @param string $user Customer's username or email
 * @param string $pass Customer's password
 * @return boolean True if login succeeded, otherwise false
 */
function attemptLogin($user, $pass) {
    global $Customer;
    if (!checkExists($user) || !(gettype($user) == "string")) return false;
    if (!checkExists($pass) || !(gettype($pass) == "string")) return false;
    //attempts to fetch details via username
    $details = $Customer->getCustomerByUsername($user);
    if (!checkExists($details)) {
        //falls back to fetching via email
        if (!filter_var($user, FILTER_VALIDATE_EMAIL)) return false;
        $details = $Customer->getCustomerByEmail($user);
        if (!checkExists($details)) return false;
    }
    //checks passwords match
    if (password_verify($pass, $details["PasswordHash"])) {
        $_SESSION["uid"] = $details["CustomerID"];
        unset($details);
        reLogInUser();
        return true;
    }
    else return false;
}

/**
 * Registers users to the database if supplied information passes all checks
 * @param array $details Associative array with relevent info (most likely just $_POST)
 * @return string Empty if succeeds (ie. evaluates to false), or a string to indicate where it failed
 */
function registerUser($details) {
    global $Customer;
    if (!checkExists($details["email"]) || !filter_var($details["email"], FILTER_VALIDATE_EMAIL)) return "Invalid Email";
    if (!checkExists($details["username"]) || !preg_match("/[a-zA-Z0-9]+/", $details["username"])) return "Invalid Username";
    if (!checkExists($details["address"]) || !preg_match("/[a-zA-Z0-9.,]+/", $details["address"])) return "Invalid address";
    if (!checkExists($details["password"]) || !(gettype($details["password"] == "string")) || !(strlen($details["password"]) > 7)) return "Invalid password";
    if (!checkExists($details["password_confirmation"]) || !($details["password"] === $details["confirmpass"])) return "Confirmation password does not match";
    if ($Customer->getCustomerByUsername($details["username"])) return "Username is already taken";
    if ($Customer->getCustomerByEmail($details["email"])) return "Email is already in use";
    //hashes password
    $details["password_hash"] = password_hash($details["password"], PASSWORD_DEFAULT);
    if (!$Customer->registerCustomer($details)) return "Database Error";
    //if here, then success
    $_SESSION["uid"] = $Customer->getCustomerByUsername($details["username"])["CustomerID"];
    reLogInUser();
    return "";
}

/**
 * Updates a specified field in the database for a customer 
 * @param array $details Associative array containing field to change, new value and other relevant info
 * @return string Empty if succeeded, or a string to indicate where it failed
 */
function updateCustomerDetail($details) {
    global $Customer;
    $details["field"] = strtolower($details["field"]);
    //preliminary checks
    if (!checkLoggedIn()) return "Not logged in";
    if (!checkExists($details["field"]) || !checkExists($details["value"]) || !(gettype($details["value"]) == "string")) return "Invalid request";

    switch ($details["field"]) {
        case "username":
            //username check
            if (!preg_match("/[a-zA-Z0-9]+/", $details["value"])) return "Invalid username";

            //if username is not in use
            $user = $Customer->getCustomerByUsername($details["value"]);
            if (!$user) {
                if ($Customer->updateCustomerDetail($_SESSION["uid"], "Username", $details["value"])) return "";
                else return "Database Error";
            }
            else return "Username already taken";

        case "email":
            //email check
            if (!filter_var($details["value"], FILTER_VALIDATE_EMAIL)) return "Invalid Email";

            //if email is not in use
            $user = $Customer->getCustomerByEmail($details["value"]);
            if (!$user) {
                if ($Customer->updateCustomerDetail($_SESSION["uid"], "Email", $details["value"])) return "";
                else return "Database Error";
            }
            else return "Email already in use";

        case "address":
            //address check
            if (!preg_match("/[a-zA-Z0-9.,]+/", $details["value"])) return "Invalid address";

            if ($Customer->updateCustomerDetail($_SESSION["uid"], "CustomerAddress", $details["value"])) return "";
            else return "Database Error";

        case "password":
            //password uses additional fields so those need checking too
            if (!checkExists($details["newPassword"]) || !checkExists($details["confirmPassword"] ||  !(gettype($details["newPassword"] == "string")))) return "Invalid request";
            //checks that can be done before queries
            if (strlen($details["newPassword"]) < 7) return "New password should be longer than 7 characters";
            if ($details["newPassword"] !== $details["confirmPassword"]) return "New and confirmation passwords should match";
            
            $user = $Customer->getCustomerByUID($_SESSION["uid"]);
            if (!password_verify($details["value"], $user["PasswordHash"])) return "Current password is incorrect";

            if($Customer->updateCustomerDetail($_SESSION["uid"], "PasswordHash", password_hash($details["newPassword"], PASSWORD_DEFAULT))) return "";
            else return "Database Error";

        default:
            return "Invalid field";
    }
}

/**
 * Unsets both global arrays and destroys the session
 */
function logOut() {
    global $userInfo;
    unset($userInfo);
    unset($_SESSION);
    session_destroy();
    //route back to main page or page that responds that user logged out
}

// ---------------------------------------------
//
// FUNCTIONS RELATING TO ORDERS 
//
// ---------------------------------------------

//TODO: add function to checkout user's cart
//      add function to view previous orders (marked as Delivered or Completed idk yet)

/**
 * --INTERNAL USE ONLY-- checks for product to make sure it's all legit
 * @param int $productID PID of product
 * @param int $quantity Quantity of product
 * @param array $product Array to hold the product being checked
 * @return boolean True if succeeded, otherwise false
 */
function productAndQuantityCheck($productID, $quantity, $product){
    global $Product;
    //check legitimate quantity
    if (!checkExists($quantity) || !(gettype($quantity) == "integer") || !($quantity >= 0)) return false;
    //check PID is int (no SQL injection allowed here sorry)
    if (!checkExists($productID) || !(gettype($productID) == "integer")) return false;
    //check product is legit
    $product = $Product->getProductByID($productID);
    if (!$product) return false;
    //check product has enough stock
    if ($product["Stock"] < $quantity) return false;
    //passed all checks
    return true;
}

/**
 * Adds specified product to user's basket
 * @param int $productID PID of the product
 * @param int $quantity Quantity of specified product to add to basket
 * @return boolean True if succeeded, otherwise false
 */
function addProductToBasket($productID, $quantity) {
    global $Order;
    //check login
    if (!checkLoggedIn()) return false;
    //init array for product
    $product = Array();
    if (!productAndQuantityCheck($productID, $quantity, $product)) return false;
    //quantity needs additional check to make sure it isnt 0
    if ($quantity === 0) return false;

    //create basket if none found, otherwise add item to it
    //(also checks for ownership of basket)
    $basket = $Order->getAllOrdersByStatusNameAndCustomerID("Basket", $_SESSION["uid"]);
    $prodPrice = $product["Price"] * $quantity;
    if (!$basket) {
        //basket doesn't exist so it makes a new one
        $orderStatID = $Order->createOrderStatus("Basket");
        if (!$orderStatID) return false;
        $orderID = $Order->createOrder(Array("customerID"=>$_SESSION["uid"], "totalAmount"=>$prodPrice,"orderStatusID"=>$orderStatID));
        if (!$orderID) return false;
        return $Order->createOrderLine(Array("orderID"=>$orderID, "productID"=>$productID, "quantity"=>$quantity));
    }
    //check if product is already in basket
    $orderLines = $Order->getAllOrderLinesByOrderID($basket["OrderID"]);
    if (!$orderLines) return false;
    foreach ($orderLines as $orderLine) if ($orderLine["ProductID"] == $productID) return modifyProductQuantityInBasket($productID, $orderLine["Quantity"]+$quantity);

    //product not in basket, appending
    if ($Order->createOrderLine(Array("orderID"=>$basket["OrderID"], "productID"=>$productID, "quantity"=>$quantity))) {
        return $Order->updateOrderDetails($basket["OrderID"], "TotalAmount", $basket["TotalAmount"]+$prodPrice);
    }
    else return false;
}

/**
 * Changes quantity of specified product in user's basket, a quantity of 0 will delete product from basket
 * @param int $productID PID of the product
 * @param int $quantity New quantity
 * @return boolean True if succeeded, otherwise false
 */
function modifyProductQuantityInBasket($productID, $quantity) {
    global $Order;
    //check login
    if (!checkLoggedIn()) return false;
    //init array for product
    $product = Array();
    if (!productAndQuantityCheck($productID, $quantity, $product)) return false;

    //gets basket to update (also checks ownership)
    $basket = $Order->getAllOrdersByStatusNameAndCustomerID("Basket", $_SESSION["uid"]);
    if (!$basket) return false;
    $allOrderLines = $Order->getAllOrderLinesByOrderID($basket["OrderID"]);
    if (!$allOrderLines) return false;

    //fetch orderLine with product in it
    $orderLine = Array();
    foreach ($allOrderLines as $curOrderLine) if ($curOrderLine["ProductID"] == $productID) $orderLine = $curOrderLine;
    if (!$orderLine) return false;

    //check for orderLine deletion
    if ($quantity == 0) {
        if (!$Order->deleteOrderLine($basket["OrderID"], $product["ProductID"])) return false;
        return $Order->updateOrderDetails($basket["OrderID"], "TotalAmount", $basket["TotalAmount"]-($orderLine["Quantity"]*$product["Price"]));
    }

    //change quantity and update price to match
    $oldPrice = $orderLine["Quantity"] * $product["Price"];
    $newPrice = $quantity * $product["Price"];
    $newTotal = $basket["TotalAmount"] - $oldPrice + $newPrice;
    if (!$Order->updateOrderLineDetails($basket["OrderID"], $product["ProductID"], "Quantity", $quantity)) return false;
    return $Order->updateOrderDetails($basket["OrderID"], "TotalAmount", $newTotal);
}

?>
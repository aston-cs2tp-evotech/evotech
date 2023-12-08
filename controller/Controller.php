<?php

//TODO: add function to check contact form data
//      add function to retrieve a single product's data
//      add function to retrieve products by category

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
function CheckExists($var) {
    return (isset($var) && !empty($var));
}

/** 
 * Puts all relevent user info into the global userInfo array
*/
function ReLogInUser() {
    global $userInfo, $Customer;
    //checks if it is set and not false
    if (CheckExists($_SESSION["uid"])) {
        $uid = $_SESSION["uid"];
        //querys the database to get user info
        $userInfo = $Customer->getCustomerByUID($uid);
    } 
}

/**
 * Checks if the user is logged in
 * @return boolean True if logged in, false if not
 */
function CheckLoggedIn() {
    global $userInfo;
    return (CheckExists($_SESSION["uid"]) && CheckExists($userInfo));
}

/**
 * Attempts to log the user in using supplied credentials
 * @param string $user Customer's username or email
 * @param string $pass Customer's password
 * @return boolean True if login succeeded, otherwise false
 */
function AttemptLogin($user, $pass) {
    global $Customer;
    if (!CheckExists($user) || !(gettype($user) == "string")) return false;
    if (!CheckExists($pass) || !(gettype($pass) == "string")) return false;
    //attempts to fetch details via username
    $details = $Customer->getCustomerByUsername($user);
    if (!CheckExists($details)) {
        //falls back to fetching via email
        if (!filter_var($user, FILTER_VALIDATE_EMAIL)) return false;
        $details = $Customer->getCustomerByEmail($user);
        if (!CheckExists($details)) return false;
    }
    //checks passwords match
    if (password_verify($pass, $details["PasswordHash"])) {
        $_SESSION["uid"] = $details["CustomerID"];
        unset($details);
        ReLogInUser();
        return true;
    }
    else return false;
}

/**
 * Registers users to the database if supplied information passes all checks
 * @param array $details Associative array with relevent info (most likely just $_POST)
 * @return string Empty if succeeds (ie. evaluates to false), or a string to indicate where it failed
 */
function RegisterUser($details) {
    global $Customer;
    if (!CheckExists($details["email"]) || !filter_var($details["email"], FILTER_VALIDATE_EMAIL)) return "Invalid Email";
    if (!CheckExists($details["username"]) || !preg_match("/[a-zA-Z0-9]+/", $details["username"])) return "Invalid Username";
    if (!CheckExists($details["customer_address"]) || !preg_match("/[a-zA-Z0-9.,]+/", $details["customer_address"])) return "Invalid address";
    if (!CheckExists($details["password"]) || !(gettype($details["password"] == "string")) || !(strlen($details["password"]) > 7)) return "Invalid password";
    if (!CheckExists($details["password_confirmation"]) || !($details["password"] === $details["confirmpass"])) return "Confirmation password does not match";
    if ($Customer->getCustomerByUsername($details["username"])) return "Username is already taken";
    if ($Customer->getCustomerByEmail($details["email"])) return "Email is already in use";
    //hashes password
    $details["password_hash"] = password_hash($details["password"], PASSWORD_DEFAULT);
    if (!$Customer->registerCustomer($details)) return "Database Error";
    //if here, then success
    $_SESSION["uid"] = $Customer->getCustomerByUsername($details["username"])["CustomerID"];
    ReLogInUser();
    return "";
}

/**
 * Updates a specified field in the database for a customer 
 * @param array $details Associative array containing field to change, new value and other relevant info
 * @return string Empty if succeeded, or a string to indicate where it failed
 */
function UpdateCustomerDetail($details) {
    global $Customer;
    $details["field"] = strtolower($details["field"]);
    //preliminary checks
    if (!CheckLoggedIn()) return "Not logged in";
    if (!CheckExists($details["field"]) || !CheckExists($details["value"]) || !(gettype($details["value"]) == "string")) return "Invalid request";

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
            if (!CheckExists($details["newPassword"]) || !CheckExists($details["confirmPassword"] ||  !(gettype($details["newPassword"] == "string")))) return "Invalid request";
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
function LogOut() {
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


/**
 * --INTERNAL USE ONLY-- checks for product to make sure it's all legit
 * @param int $productID PID of product
 * @param int $quantity Quantity of product
 * @param array $product Array to hold the product being checked
 * @return boolean True if succeeded, otherwise false
 */
function ProductAndQuantityCheck($productID, $quantity, $product){
    global $Product;
    //check legitimate quantity
    if (!CheckExists($quantity) || !(gettype($quantity) == "integer") || !($quantity >= 0)) return false;
    //check PID is int (no SQL injection allowed here sorry)
    if (!CheckExists($productID) || !(gettype($productID) == "integer")) return false;
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
function AddProductToBasket($productID, $quantity) {
    global $Order;
    //check login
    if (!CheckLoggedIn()) return false;
    //init array for product
    $product = Array();
    if (!ProductAndQuantityCheck($productID, $quantity, $product)) return false;
    //quantity needs additional check to make sure it isnt 0
    if ($quantity === 0) return false;

    //create basket if none found, otherwise add item to it
    //(also checks for ownership of basket)
    $basket = $Order->getAllOrdersByOrderStatusNameAndCustomerID("Basket", $_SESSION["uid"]);
    $prodPrice = $product["Price"] * $quantity;
    if (!$basket) {
        //basket doesn't exist so it makes a new one
        $orderStatID = $Order->getOrderStatusIDByName("Basket");
        if (!$orderStatID) return false;
        $orderID = $Order->createOrder(Array("customerID"=>$_SESSION["uid"], "totalAmount"=>$prodPrice,"orderStatusID"=>$orderStatID));
        if (!$orderID) return false;
        return $Order->createOrderLine(Array("orderID"=>$orderID, "productID"=>$productID, "quantity"=>$quantity));
    }
    //check if product is already in basket
    $orderLines = $Order->getAllOrderLinesByOrderID($basket["OrderID"]);
    if (!$orderLines) return false;
    foreach ($orderLines as $orderLine) if ($orderLine["ProductID"] == $productID) return ModifyProductQuantityInBasket($productID, $orderLine["Quantity"]+$quantity);

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
function ModifyProductQuantityInBasket($productID, $quantity) {
    global $Order;
    //check login
    if (!CheckLoggedIn()) return false;
    //init array for product
    $product = Array();
    if (!ProductAndQuantityCheck($productID, $quantity, $product)) return false;

    //gets basket to update (also checks ownership)
    $basket = $Order->getAllOrdersByOrderStatusNameAndCustomerID("Basket", $_SESSION["uid"]);
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

/**
 * Checks out the basket (if it exists), of the logged in customer
 * @return boolean True if succeeded, otherwise false
 */
function CheckoutBasket() {
    global $Order;
    if (!CheckLoggedIn()) return false;
    //fetch basket
    $basket = $Order->getAllOrdersByOrderStatusNameAndCustomerID("Basket", $_SESSION["uid"]);
    if (!$basket) return false;

    //passed all checks, updating status
    return $Order->updateOrderDetails($basket["OrderID"], "OrderStatusID", $Order->getOrderStatusIDByName("Processing"));
}

/**
 * --INTERNAL USE ONLY-- Formats the passed orderlines to create an array with the order
 * @param array $orderLines Array of OrderLines
 * @param array $basket Associative array of the order (array[int][string])
 * @return boolean True if succeeded, otherwise false
 */
function FormatOrderLines($orderLines, $basket) {
    global $Product;

    for ($i=0; $i<count($orderLines); $i++) {
        $product = $Product->getProductByID($orderLines[$i]["ProductID"]);
        if (!$product) return false;

        $basket[$i]["ProductID"] = $product["ProductID"];
        $basket[$i]["ProductName"] = $product["Name"];
        $basket[$i]["Quantity"] = $orderLines[$i]["Quantity"];
        $basket[$i]["TotalStock"] = $product["Stock"];
        $basket[$i]["UnitPrice"] = $product["Price"];
        $basket[$i]["TotalPrice"] = $basket[$i]["UnitPrice"] * $basket[$i]["Quantity"];
    }
    return true;
}

/**
 * Retrieves the customer's basket
 * @param string $totalAmount empty var when passed, holds total price for order if succeeds
 * @return array|boolean 2d array (array[int][string]) if success, otherwise null
 */
function GetCustomerBasket($totalAmount) {
    global $Order;
    if (!CheckLoggedIn()) return false;

    //retrieve order
    $intOrder = $Order->getAllOrdersByOrderStatusNameAndCustomerID("Basket", $_SESSION["uid"]);
    if (!$intOrder) return false;
    $intOrderLines = $Order->getAllOrderLinesByOrderID($intOrder["OrderID"]);
    if (!$intOrderLines) return false;

    //format return value
    $basket = Array(Array());
    if (!FormatOrderLines($intOrderLines, $basket)) return false;

    //assign price and return
    $totalAmount = $intOrder["TotalAmount"];
    return $basket;

}

/**
 * Retrieves all previous orders for a customer (not incl. basket)
 * @param array $totalAmounts empty array when passed, each index holds the total amount for the respective order
 * @return array|boolean 3d array (array[int][int][string]) if success, otherwise false
 */
function GetPreviousOrders($totalAmounts) {
    global $Order;
    if (!CheckLoggedIn()) return false;

    //retrieve orders
    $orders = $Order->getAllOrdersByOrderStatusNameAndCustomerID("Delivered", $_SESSION["uid"]);
    if (!$orders) return false;
    
    //retrieve all orderlines associated with each order
    $orderLines = Array(Array());
    for ($i=0; $i<count($orders); $i++) { 
        $orderLines[$i] = $Order->getAllOrderLinesByOrderID($orders[$i]["OrderID"]);
        if (!$orderLines[$i]) return false;
    }

    //format return value
    $megaBasket = Array(Array(Array()));
    for ($i=0; $i<count($orderLines); $i++) if (!FormatOrderLines($orderLines[$i], $megaBasket[$i])) return false;

    //get total amounts for each order
    for ($i=0; $i<count($orders); $i++) $totalAmounts[$i] = $orders[$i]["TotalAmount"];

    //return array
    return $megaBasket;
}

?>
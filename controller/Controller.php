<?php

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
function ReLogInUser() {
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
function CheckLoggedIn() {
    global $userInfo;
    return (checkExists($_SESSION["uid"]) && checkExists($userInfo));
}

/**
 * Attempts to log the user in using supplied credentials
 * @param string $user Customer's username or email
 * @param string $pass Customer's password
 * @return boolean True if login succeeded, otherwise false
 */
function AttemptLogin($user, $pass) {
    global $Customer;
    //attempts to fetch details via username
    $details = $Customer->getCustomerByUsername($user);
    if (!checkExists($details)) {
        //falls back to fetching via email
        $details = $Customer->getCustomerByEmail($user);
        if (!checkExists($details)) return false;
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
    if (!checkExists($details["email"]) || !filter_var($details["email"], FILTER_VALIDATE_EMAIL)) return "Invalid Email";
    if (!checkExists($details["username"]) || !preg_match("/[a-zA-Z0-9]+/", $details["username"])) return "Invalid Username";
    if (!checkExists($details["customer_address"]) || !preg_match("/[a-zA-Z0-9.,]+/", $details["customer_address"])) return "Invalid address";
    if (!checkExists($details["password"]) || !(strlen($details["password"]) > 7)) return "Invalid password";
    if (!checkExists($details["password_confirmation"]) || !($details["password"] === $details["confirmpass"])) return "Confirmation password does not match";
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
    if (!checkExists($details["field"]) || !checkExists($details["value"])) return "Invalid request";

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
            if (!checkExists($details["newPassword"]) || !checkExists($details["confirmPassword"])) return "Invalid request";
            //checks that can be done before queries
            if (strlen($details["newPassword"]) < 7) return "New password should be longer than 7 characters";
            if ($details["newPassword"] != $details["confirmPassword"]) return "New and confirmation passwords should match";
            
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
 * @param Array $product Array to hold the product being checked
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
 * Adds specified product to user's cart
 * @param int $productID PID of the product
 * @param int $quantity Quantity of specified product to add to cart
 * @return boolean True if succeeded, otherwise false
 */
function addProductToCart($productID, $quantity) {
    global $Order;
    //check login
    if (!CheckLoggedIn()) return false;
    //init array for product
    $product = Array();
    if (!productAndQuantityCheck($productID, $quantity, $product)) return false;
    //quantity needs additional check to make sure it isnt 0
    if ($quantity === 0) return false;

    //create basket if none found, otherwise add item to it
    //(also checks for ownership of basket)
    $basket = $Order->getOrdersByStatus($_SESSION["uid"], "Basket");
    if (!$basket) return $Order->createOrder($_SESSION["uid"], ($quantity * $product["Price"]), "Basket", $product["ProductID"], $quantity);
    //                                                    ||                   Calculate new price                ||             
    else return $Order->appendToOrder($basket["OrderID"], ($basket["TotalAmount"] + ($quantity * $product["Price"])), $product["ProductID"], $quantity);
}

/**
 * Changes quantity of specified product in user's cart
 * @param int $productID PID of the product
 * @param int $quantity New quantity
 * @return boolean True if succeeded, otherwise false
 */
function modifyProductQuantityInCart($productID, $quantity) {
    global $Order;
    //check login
    if (!CheckLoggedIn()) return false;
    //init array for product
    $product = Array();
    if (!productAndQuantityCheck($productID, $quantity, $product)) return false;

    //gets basket to update (also checks ownership)
    $basket = $Order->getOrderByStatus($_SESSION["uid"], "Basket");
    if (!$basket) return false;
    //gets order line of product to check it is in cart
    $oldProd = $Order->getOrderLineByProductID($basket["OrderID"], $product["ProductID"]);
    if (!$oldProd) return false;
    if ($quantity === 0) {
        //calculate price without specified product
        $newPrice = $basket["TotalAmount"] - ($oldProd["Quantity"] * $product["Price"]);
        return $Order->removeProductFromOrder($basket["OrderID"], $product["ProductID"], $newPrice);
    }
    else {
        $newPrice = $basket["TotalAmount"] - ($oldProd["Quantity"] * $product["Price"]) + ($product["Price"] * $quantity);
        return $Order->updateProductInOrder($basket["OrderID"], $product["ProductID"], $quantity, $newPrice);
    }
}

?>
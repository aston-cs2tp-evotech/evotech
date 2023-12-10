<?php

//TODO: add function to check contact form data

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
    if (!CheckExists($details["confirmpass"]) || !($details["password"] === $details["confirmpass"])) return "Confirmation password does not match";
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
// FUNCTIONS RELATING TO PRODCUTS
//
// ---------------------------------------------

/**
 * Sorts through the images of a product and finds the main one
 * @param array $images The productImages as an array
 * @param string $mainImage The variable that will store the main image
 * @param array $otherImages The array to store all other images
 */
function SortProductImages($images, &$mainImage, &$otherImages) {
    $main = "";
    $other = array();
    foreach ($images as $image) {
        if ($image["MainImage"]) $main = $image["FileName"]; 
        else array_push($other, $image["FileName"]);
    }
    $mainImage = $main;
    $otherImages = $other;
}

/**
 * Adds the images to the product array
 * @param array $product The product array
 */
function AddProductImagesToProduct(&$product) {
    global $Product;
    $images = $Product->getProductImages($product["ProductID"]);
    if (!$images) return false;
    SortProductImages($images, $product["MainImage"], $product["OtherProductImages"]);
}
/**
 * Gets product from the database, regardless of stock
 * @param int $productID ID of the product
 * @return array|boolean Array with product details if success, otherwise false
 */
function GetProductByID($productID) {
    global $Product;
    if (!checkExists($productID) || !(gettype($productID) == "integer")) return false;
    $prod = $Product->getProductByID($productID);
    if (!$prod) return false;
    AddProductImagesToProduct($prod);
    return $prod;
}

/**
 * --INTERNAL USE ONLY-- Filters array to only have stocked products
 * @param array $products Array of products to filter (will overwrite)
 * @return string|boolean True if success, otherwise a string for failure
 */
function FilterStockedProducts(&$products) {
    if (!$products) {
        return "No products to check";
    }
    $stockedProducts = array();
    foreach ($products as $product) {
        if ($product["Stock"] > 0) {
            array_push($stockedProducts, $product);
        }
    }
    if (!$stockedProducts) {
        return "No products have stock";
    }
    $products = $stockedProducts;
    return true;
}

/**
 * Gets every product in the database, regardless of stock
 * @return array|boolean 2d array if succeeded, otherwise false
 */
function GetAllProducts() {
    global $Product;
    $products = $Product->getAllProducts();
    if (!$products) return false;
    foreach ($products as &$product) {
        AddProductImagesToProduct($product);
    }
    return $products;
}

/**
 * Gets every product in the database where stock > 0
 * @return array|boolean 2d array if succeeded, otherwise false
 */
function GetAllStockedProducts() {
    $products = GetAllProducts();
    if (!FilterStockedProducts($products)) return false;
    else return $products;
}

/**
 * Adds a category to the product via it's categoryID
 * @param array $product The product
 * @return string Empty if success, otherwise indicates failure 
 */
function AddCategoryToProduct(&$product) {
    global $Product;

    //get categoryID
    $categories = $Product->getCategories();
    if (!$categories) {
        return "Database Error";
    }

    //iterate through categories to find correct one
    foreach ($categories as $category) {
        if ($product["CategoryID"] == $category["CategoryID"]) {
            $product["Category"] = $category["CategoryName"];
        }
    }

    //check it has category
    if (empty($product["Category"])) {
        return "Product has no category";
    }

    return "";
}

/**
 * Adds categories to the products
 * @param array $products The products array
 * @return string Empty if success, otherwise indicates failure
 */
function AddCategoriesToProducts(&$products) {
    global $Product;
    
    //get categoryID
    $categories = $Product->getCategories();
    if (!$categories) {
        return "Database Error";
    }

    //iterate through products to add category
    foreach ($products as &$product) {
        foreach ($categories as $category) {
            if ($product["CategoryID"] == $category["CategoryID"]) {
                $product["Category"] = $category["CategoryName"];
            }
        }
    }

    //check all products have a category
    foreach ($products as $product) {
        if (empty($product["Category"])) {
            return $product["Name"] . " has no category associated with it";
        }
    }

    return "";

}

/**
 * Gets all products by category, regardless of stock
 * @param string $category Category of the product (component, accessory etc.)
 * @return array|string 2d array if succeeded, otherwise a string where it failed
 */
function GetAllByCategory($category){
    global $Product;
    //Input validation
    $categories = array("Components", "CPUs", "Graphics Cards", "Cases", "Storage", "Memory");
    if (!CheckExists($category) || !(gettype($category) == "string") || !(in_array($category, $categories))) {
        return "Invalid Category";
    }
    
    //get products
    $products = GetAllProducts();
    if (!$products) {
        return "Error getting products";
    }

    //add categories
    $err = AddCategoriesToProducts($products);
    if (!empty($err)) {
        return $err;
    }

    //filter products
    $filterProducts = array();
    foreach ($products as $product) {
        if ($product["Category"] == $category) {
            array_push($filterProducts, $product);
        }
    }
    if (empty($filterProducts)) {
        return "No products in category";
    }

    //add images
    foreach ($filterProducts as &$filteredProduct) {
        AddProductImagesToProduct($filteredProduct);
    }
    
    return $filterProducts;
}

/**
 * Gets all products by category where stock > 0
 * @param string $category Category of the product (component, accessory etc)
 * @return array|string 2d array if succeeded, otherwise string for failure
 */
function GetAllStockedByCategory($category) {
    $products = GetAllByCategory($category);
    $err = FilterStockedProducts($products);
    if (!$err) { 
        return $err;
    }
    else {
        return $products;
    }
}

/**
 * --INTERNAL USE ONLY-- Removes the product from the array by PID
 * @param array $products The products array
 * @param int $productID The ID of the product to remove
 * @return string Empty if success, otherwise indicates failure
 */
function RemoveProductFromArrayByID(&$products, $productID) {
    $oldProducts = $products;
    $size = count($oldProducts);
    //remove product from similar products
    for ($i=0; $i<count($oldProducts); $i++) {
        if ($oldProducts[$i]["ProductID"] == $productID) {
            unset($oldProducts[$i]);
        }
    }
    
    //fixing array key values so it cant generate a null
    $simProducts = array();
    foreach ($oldProducts as $product) {
        array_push($simProducts, $product);
    }

    //check array
    if ($size > 1 && empty($simProducts)) {
        return "No products left in array";
    }

    $products = $simProducts;
    return "";
}
/**
* Gets 3 random products, including from other categories if needed
* @param int $productID The ID of the product
* @return array|string Array with 3 products if success, otherwise indicates failure
*/
function GetRecommendedProducts($productID) {
   global $Product;
   
   // ID check
   if (!CheckExists($productID)) {
       return "Invalid productID";
   }

   // Fetch product
   $product = $Product->getProductByID($productID);
   if (!CheckExists($product)) {
       return "Database Error";
   }

   // Get product category
   $err = AddCategoryToProduct($product);
   if (!empty($err)) {
       return $err;
   }

   // Gets similar products from the same category
   $products = GetAllStockedByCategory($product["Category"]);
   if (gettype($products) == "string") {
       // If not enough products in the same category, fetch from other categories
       $allCategories = $Product->getCategories();
       if (!$allCategories) {
           return "Database Error";
       }

       // Remove the current product's category
       $allCategories = array_filter($allCategories, function ($category) use ($product) {
           return $category["CategoryID"] !== $product["CategoryID"];
       });

       // Choose a random category
       $randomCategory = $allCategories[array_rand($allCategories)];

       // Get products from the random category
       $products = GetAllStockedByCategory($randomCategory["CategoryName"]);

       if (gettype($products) == "string") {
           return $products;
       }
   }

   // Choose 3 randomly
   $returnProds = array();
   for ($i = 0; $i < 3; $i++) {
       // Check if $products array is empty
       if (empty($products)) {
           return "Not enough products available for recommendations";
       }

       // Select a random index within the valid range
       $randomIndex = random_int(0, count($products) - 1);

       // Push the selected product to $returnProds
       array_push($returnProds, $products[$randomIndex]);

       // Remove the selected product from the array
       $err2 = RemoveProductFromArrayByID($products, $returnProds[$i]["ProductID"]);
       if (!empty($err2)) {
           return $err2 . " when selecting recommended products";
       }
   }

   if (empty($returnProds)) {
       return "Failed selecting recommended products";
   }

   return $returnProds;
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
 * @return array|boolean Product if succeeded, otherwise false
 */
function ProductAndQuantityCheck($productID, $quantity){
    global $Product;
    //check legitimate quantity
    if (!isset($quantity) || !(gettype($quantity) == "integer") || !($quantity >= 0)) return false;
    //check PID is int (no SQL injection allowed here sorry)
    if (!CheckExists($productID) || !(gettype($productID) == "integer")) return false;
    //check product is legit
    $product = $Product->getProductByID($productID);
    if (!$product) return false;
    //check product has enough stock
    if ($product["Stock"] < $quantity) return false;
    //passed all checks
    return $product;
}

/**
 * Adds specified product to user's basket
 * @param int $productID PID of the product
 * @param int $quantity Quantity of specified product to add to basket
 * @return boolean True if succeeded, otherwise false
 */
function AddProductToBasket($productID, $quantity) {
    global $Order;

    // Check login
    if (!CheckLoggedIn()) {
        return false;
    }

    // Init array for product
    $product = ProductAndQuantityCheck($productID, $quantity);
    if (!$product) {
        return false;
    }

    // Quantity needs additional check to make sure it isn't 0
    if ($quantity === 0) {
        return false;
    }

    // Create basket if none found, otherwise add item to it (also checks for ownership of basket)
    $basket = $Order->getAllOrdersByOrderStatusNameAndCustomerID("basket", $_SESSION["uid"])[0];
    $prodPrice = $product["Price"] * $quantity;
    if (!$basket) {
        // Basket doesn't exist, so it makes a new one
        $orderStatID = $Order->getOrderStatusIDByName("basket");
        if (!$orderStatID) {
            return false;
        }

        $orderID = $Order->createOrder(array("customerID" => $_SESSION["uid"], "totalAmount" => $prodPrice, "orderStatusID" => $orderStatID));

        if (!$orderID) {
            return false;
        }

        return $Order->createOrderLine(array("orderID" => $orderID, "productID" => $productID, "quantity" => $quantity));
    }

    // Check if product is already in the basket
    $orderLines = $Order->getAllOrderLinesByOrderID($basket["OrderID"]);

    if (!$orderLines) {
        return false;
    }

    foreach ($orderLines as $orderLine) {
        if ($orderLine["ProductID"] == $productID) {
            return ModifyProductQuantityInBasket($productID, $orderLine["Quantity"] + $quantity);
        }
    }

    // Product not in basket, appending
    if ($Order->createOrderLine(array("orderID" => $basket["OrderID"], "productID" => $productID, "quantity" => $quantity))) {
        return $Order->updateOrderDetails($basket["OrderID"], "TotalAmount", $basket["TotalAmount"] + $prodPrice);
    } else {
        return false;
    }
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
    $product = ProductAndQuantityCheck($productID, $quantity);
    if (!$product) return false;

    //gets basket to update (also checks ownership)
    $basket = $Order->getAllOrdersByOrderStatusNameAndCustomerID("basket", $_SESSION["uid"])[0];
    if (!$basket) return false;
    $allOrderLines = $Order->getAllOrderLinesByOrderID($basket["OrderID"]);
    if (!$allOrderLines) return false;

    //fetch orderLine with product in it
    $orderLine = array();
    foreach ($allOrderLines as $curOrderLine) if ($curOrderLine["ProductID"] == $productID) $orderLine = $curOrderLine;
    if (!$orderLine) return false;

    //check for orderLine deletion
    if ($quantity == 0) {
        if (!$Order->deleteOrderLine($basket["OrderID"], $product["ProductID"])) return false;
        if (!$Order->getAllOrderLinesByOrderID($basket["OrderID"])) return $Order->deleteOrder($basket["OrderID"]);
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
    $basket = $Order->getAllOrdersByOrderStatusNameAndCustomerID("basket", $_SESSION["uid"])[0];
    if (!$basket) return false;

    return $Order->updateOrderDetails($basket["OrderID"], "OrderStatusID", $Order->getOrderStatusIDByName("ready"));
}

/**
 * --INTERNAL USE ONLY-- Formats the passed orderlines to create an array with the order
 * @param array $orderLines Array of OrderLines
 * @param array $basket Associative array of the order (array[int][string])
 * @return boolean True if succeeded, otherwise false
 */
function FormatOrderLines($orderLines, &$basket) {
    global $Product;

    foreach ($orderLines as $index => $orderLine) {
        $product = $Product->getProductByID($orderLine["ProductID"]);
        if (!$product) {
            return false;
        }
        AddProductImagesToProduct($product);

        $basket[$index]["ProductID"] = $product["ProductID"];
        $basket[$index]["ProductName"] = $product["Name"];
        $basket[$index]["Quantity"] = $orderLine["Quantity"];
        $basket[$index]["TotalStock"] = $product["Stock"];
        $basket[$index]["UnitPrice"] = $product["Price"];
        $basket[$index]["TotalPrice"] = $basket[$index]["UnitPrice"] * $basket[$index]["Quantity"];
        $basket[$index]["MainImage"] = $product["MainImage"];
        $basket[$index]["OtherProductImages"] = $product["OtherProductImages"];
    }

    return true;
}


/**
 * Retrieves the customer's basket
 * @param string $totalAmount empty var when passed, holds total price for order if succeeds
 * @return array|boolean 2d array (array[int][string]) if success, otherwise null
 */
function GetCustomerBasket(&$totalAmount) {
    global $Order;

    if (!CheckLoggedIn()) {
        return false;
    }

    // Retrieve orders with status "basket" for the customer
    $basketOrders = $Order->getAllOrdersByOrderStatusNameAndCustomerID("basket", $_SESSION["uid"]);

    // Check if any basket orders are found
    if (!$basketOrders || empty($basketOrders)) {
        return false;
    }

    // Assuming you want the latest basket order, you can choose the first one in the list
    $latestBasketOrder = $basketOrders[0];

    // Retrieve order lines for the basket order
    $orderLines = $Order->getAllOrderLinesByOrderID($latestBasketOrder["OrderID"]);


    // Check if any order lines are found
    if (!$orderLines || empty($orderLines)) {
        return false;
    }

    // Format return value
    $basket = array();

    // Assign price to the totalAmount variable
    $totalAmount = $latestBasketOrder["TotalAmount"];

    // Format order lines
    if (!FormatOrderLines($orderLines, $basket)) {
        return false;
    }

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
    $allOrders = $Order->getAllOrdersByCustomerID($_SESSION["uid"]);
    if (!$allOrders) {
        return false;
    }

    //removes basket from orders
    $orders = array();
    foreach ($allOrders as $order) {
        if (!($order["OrderStatusID"] == $Order->getOrderStatusIDByName("basket"))) {
            array_push($orders, $order);
        }
    }
    if (!$orders) {
        return false;
    }
    
    //retrieve all orderlines associated with each order
    $orderLines = array(array());
    for ($i=0; $i<count($orders); $i++) { 
        $orderLines[$i] = $Order->getAllOrderLinesByOrderID($orders[$i]["OrderID"]);
        if (!$orderLines[$i]) return false;
    }

    //format return value
    $megaBasket = array(array(array()));
    for ($i=0; $i<count($orderLines); $i++) if (!FormatOrderLines($orderLines[$i], $megaBasket[$i])) return false;


    //prepare orderStatuses
    $orderStats = $Order->getAllOrderStatuses();

    //add order status to order
    foreach ($megaBasket as $index =>&$basket) {
        //gets the index of the order by checking the first orderline to fetch order to get orderstatusID
        foreach ($orderStats as $order) {
            if ($order["OrderStatusID"] == $orders[$index]["OrderStatusID"]) {
                $basket["Status"] = $order["Name"];
            }
        }
        
        if (empty($basket["Status"])) {
            return false;
        }
    }

    //get total amounts for each order
    for ($i=0; $i<count($orders); $i++) $totalAmounts[$i] = $orders[$i]["TotalAmount"];

    //return array
    return $megaBasket;
}

?>
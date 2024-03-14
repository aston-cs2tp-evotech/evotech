<?php

//TODO: add function to check contact form data

// ---------------------------------------------
//
// FUNCTIONS RELATING TO CUSTOMER AND MAIN PAGE
//
// ---------------------------------------------

/**
 * @var Customer Global variable to store customer info (username, address etc)
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
 * Iterates through details from db to ensure every key exists for the Customer object
 * @param array $details Details array from the database
 * @return Customer|null A customer object with all details, or null if any didn't exist 
 */
function CreateSafeCustomer($details) {
    $badCustomer = false;
    $keys = array('CustomerID', 'Email', 'Username', 'CustomerAddress', 'PasswordHash');

    //check every key
    foreach ($details as $key => $detail) {
        if (!in_array($key, $keys)) $badCustomer = true;
        else unset($keys[array_search($key, $keys)]); //removes key, to check all necessary fields exist
    }

    echo count($keys);
    //create customer
    if (!$badCustomer && empty($keys)) {
        return new Customer($details);
    }
    else return null;
}

/** 
 * Puts all relevent user info into the global userInfo array
*/
function ReLogInUser() {
    global $userInfo, $Customer;
    //checks if it is set and not false
    if (CheckExists($_SESSION["uid"])) {
        $uid = $_SESSION["uid"];
        //querys the database to get user info and creates a customer
        $userInfo = CreateSafeCustomer($Customer->getCustomerByUID($uid));
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
 * @return string Empty if succeeded (i.e. evaluates to false) or an err message if failed
 */
function AttemptLogin($user, $pass) {
    global $Customer;
    if (!CheckExists($user) || !(gettype($user) == "string")) return "Username or email not entered";
    if (!CheckExists($pass) || !(gettype($pass) == "string")) return "Password not entered";
    //attempts to fetch details via username
    $details = CreateSafeCustomer($Customer->getCustomerByUsername($user));
    if (!CheckExists($details)) {
        //falls back to fetching via email
        if (!filter_var($user, FILTER_VALIDATE_EMAIL)) return "Invalid email entered";
        $details = CreateSafeCustomer($Customer->getCustomerByEmail($user));
        if (!CheckExists($details)) return "User does not exist";
    }
    //checks passwords match
    if (password_verify($pass, $details->getPasswordHash())) {
        $_SESSION["uid"] = $details->getUID();
        unset($details);
        ReLogInUser();
        return "";
    }
    else return "Incorrect password";
}

/**
 * Registers users to the database if supplied information passes all checks
 * @param array $details Associative array with relevent info (most likely just $_POST)
 * @return string Empty if succeeds (ie. evaluates to false), or a string to indicate where it failed
 */
function RegisterUser($details) {
    global $Customer;
    if (!CheckExists($details["email"]) || !filter_var($details["email"], FILTER_VALIDATE_EMAIL)) return "Invalid Email";
    if (!CheckExists($details["username"]) || !preg_match("/[a-zA-Z0-9]+/", $details["username"])) return "Invalid Username, please make sure it is alphanumeric";
    if (!CheckExists($details["customer_address"]) || !preg_match("/[a-zA-Z0-9.,]+/", $details["customer_address"])) return "Invalid address";
    if (!CheckExists($details["password"]) || !(gettype($details["password"] == "string")) || !(strlen($details["password"]) > 7)) return "Invalid password, make sure it is 8 characters or more";
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
 * @return string Empty if succeeded (i.e. evaluates to false), or a string to indicate where it failed
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
            if (!preg_match("/[a-zA-Z0-9]+/", $details["value"])) return "Invalid username, make sure it is alphanumeric";

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
 * Creates a product object with all details (minus cateogries and images) if exists
 * @param array $details Details array from db
 * @return Product|null Product with required details, or null
 */
function CreateSafeProduct($details) {
    $badProd = false;
    $keys = array('ProductID', 'Name', 'Price', 'Stock', 'Description', 'CategoryID');

    //check each required key
    foreach ($details as $key => $detail) {
        if (!in_array($key, $keys)) $badProd = true;
        else unset($keys[array_search($key, $keys)]);
    }

    if (!$badProd && empty($keys)) {
        //prepare missing categories for Product
        $details['CategoryName'] = null;
        $details['MainImage'] = null;
        $details['OtherImages'] = null;

        $temp = new Product($details);
        AddProductImagesToProduct($temp);
        $err = AddCategoryToProduct($temp);
        if (!$err) return $temp;
        else return null;
    }
    else return null;
}

/**
 * Sorts through the images of a product and finds the main one
 * @param array $images The productImages as an array
 * @param Product $product The Product object to add to
 */
function SortProductImages($images, $product) {
    $main = "";
    $other = array();
    foreach ($images as $image) {
        if ($image["MainImage"]) $main = $image["FileName"]; 
        else array_push($other, $image["FileName"]);
    }
    $product->setMainImage($main);
    $product->setOtherImages($other);
}

/**
 * Adds the images to the product
 * @param Product $product The product
 */
function AddProductImagesToProduct(&$product) {
    global $Product;
    $images = $Product->getProductImages($product->getProductID());
    if (!$images) return false;
    SortProductImages($images, $product);
}
/**
 * Gets product from the database, regardless of stock
 * @param int $productID ID of the product
 * @return Product|boolean Product if success, otherwise false
 */
function GetProductByID($productID) {
    global $Product;
    if (!checkExists($productID) || !(gettype($productID) == "integer")) return false;
    $prod = CreateSafeProduct($Product->getProductByID($productID));
    if ($prod == null) return false;
    return $prod;
}

/**
 * --INTERNAL USE ONLY-- Filters array to only have stocked products
 * @param array $products Array of products to filter (will overwrite)
 * @return string Empty if succeeded (i.e. evaluates to false) or an err message if failed
 */
function FilterStockedProducts(&$products) {
    if (!$products) {
        return "No products to check";
    }
    $stockedProducts = array();
    foreach ($products as $product) {
        if ($product->getStock() > 0) {
            array_push($stockedProducts, $product);
        }
    }
    if (!$stockedProducts) {
        return "No products have stock";
    }
    $products = $stockedProducts;
    return "";
}

/**
 * Gets every product in the database, regardless of stock
 * @return array|string Array of products if succeeded, otherwise err message
 */
function GetAllProducts() {
    global $Product;
    $products = $Product->getAllProducts();
    if (!$products) return "Error retrieving products";
    foreach ($products as &$product) {
        $prod = CreateSafeProduct($product);
        if ($prod == null) return "Error retrieving a product";
        $product = $prod;
    }
    return $products;
}

/**
 * Gets every product in the database where stock > 0
 * @return array|string 2d array if succeeded, otherwise err message if failed
 */
function GetAllStockedProducts() {
    $products = GetAllProducts();
    if (gettype($products) == "string") return $products;
    $err = FilterStockedProducts($products);
    if ($err) return $err;
    else return $products;
}

/**
 * Adds a category to the product via it's categoryID
 * @param Product $product The product
 * @return string Empty if success, otherwise indicates failure 
 */
function AddCategoryToProduct(&$product) {
    global $Product;

    //get categoryID
    $categories = $Product->getCategories();
    if (!$categories) {
        return "Error retrieving categories";
    }

    //iterate through categories to find correct one
    foreach ($categories as $category) {
        if ($product->getCategoryID() == $category["CategoryID"]) {
            $product->setCategoryName($category["CategoryName"]);
        }
    }

    //check it has category
    if (empty($product->getCategoryName())) {
        return $product->getName() . " has no category associated with it";
    }

    return "";
}

/**
 * Adds categories to the products
 * @param array $products The products array
 * @return string Empty if success, otherwise indicates failure
 */
function AddCategoriesToProducts(&$products) {
    foreach ($products as &$product) {
        $err = AddCategoryToProduct($product);
        if ($err) return $err;
    }

    return "";

}

/**
 * Gets all products by category, regardless of stock
 * @param string $category Category of the product (component, accessory etc.)
 * @return array|string Array of products if succeeded, otherwise a string where it failed
 */
function GetAllByCategory($category){
    global $Product;
    //Input validation
    $categories = array("Components", "CPUs", "Graphics Cards", "Cases", "Storage", "Memory");
    if (!CheckExists($category) || !(gettype($category) == "string") || !(in_array($category, $categories))) {
        return "Invalid category";
    }
    
    //get products
    $products = GetAllProducts();
    if (!$products) {
        return "Error retrieving products";
    }

    //add categories
    $err = AddCategoriesToProducts($products);
    if (!empty($err)) {
        return $err;
    }

    //filter products
    $filterProducts = array();
    foreach ($products as $product) {
        if ($product->getCategoryName() == $category) {
            array_push($filterProducts, $product);
        }
    }
    if (empty($filterProducts)) {
        return "No products in category";
    }

    
    return $filterProducts;
}

/**
 * Gets all products by category where stock > 0
 * @param string $category Category of the product (component, accessory etc)
 * @return array|string Array of products succeeded, otherwise string for failure
 */
function GetAllStockedByCategory($category) {
    $products = GetAllByCategory($category);
    $err = FilterStockedProducts($products);
    if ($err) { 
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
        if ($oldProducts[$i]->getProductID() == $productID) {
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
   $product = CreateSafeProduct($Product->getProductByID($productID));
   if (!CheckExists($product)) {
       return "Error retrieving product";
   }

   // Gets similar products from the same category
   $products = GetAllStockedByCategory($product->getCategoryName());
   if (gettype($products) == "string") {
       // If not enough products in the same category, fetch from other categories
       $allCategories = $Product->getCategories();
       if (!$allCategories) {
           return "Error retrieving categories";
       }

       // Remove the current product's category
       $allCategories = array_filter($allCategories, function ($category) use ($product) {
           return $category["CategoryID"] !== $product->getCategoryID();
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
       $err2 = RemoveProductFromArrayByID($products, $returnProds[$i]->getProductID());
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
 * Iterates through every detail from db to ensure every needed key exists
 * @param array $details the array from the db query
 * @return OrderLine|null OrderLine with all required info, or null if failed
 */
function CreateSafeOrderLine($details) {
    global $Product;
    $badOrderLine = false;
    //only keys passed from db when fetching orderLines
    $keys = array('ProductID', 'Quantity', 'OrderID');

    foreach ($details as $key => $detail) {
        if (!in_array($key, $keys)) $badOrderLine = true;
        else unset($keys[array_search($key, $keys)]);
    }
    if (!$badOrderLine && empty($keys)) {
        //fetch product to look up other info
        $product = CreateSafeProduct($Product->getProductByID($details['ProductID']));
        if (is_null($product)) return null;

        //add missing info to orderLine
        $details['ProductName'] = $product->getName();
        $details['TotalStock'] = $product->getStock();
        $details['UnitPrice'] = $product->getPrice();
        $details['MainImage'] = $product->getMainImage();
        $details['OtherImages'] = $product->getOtherImages();

        return new OrderLine($details);
    }
    else return null;
}

/**
 * Iterates through array to make safe versions of each orderLine
 * @param array $details 2d array of orderLines from db
 * @return array|null array of OrderLine objects, or null if no orderLines are "safe"
 */
function CreateMultipleSafeOrderLines($details) {
    $orderLines = array();

    if (is_null($details)) return null;
    
    foreach ($details as $detail) {
        $orderLine = CreateSafeOrderLine($detail);
        if (!is_null($orderLine)) array_push($orderLines, $orderLine);
    }

    if (!empty($orderLines)) {
        return $orderLines;
    }
    else return null;

}

/**
 * Iterates through every detail from db to ensure every needed key exists
 *  @param array $details the array from the db query
 *  @return Order|null Order with all required info, or null if failed
 */ 
function CreateSafeOrder($details) {
    global $Order;
    $badOrder = false;
    $keys = array('OrderID', 'CustomerID', 'TotalAmount', 'OrderStatusID');

    foreach ($details as $key => $detail) {
        if (!in_array($key, $keys)) $badOrder = true;
        else unset($keys[array_search($key, $keys)]);
    }

    if (!$badOrder && empty($keys)) {
        //add orderStatusName
        $statuses = $Order->getAllOrderStatuses();
        foreach ($statuses as $status) {
            if ($status['OrderStatusID'] == $details['OrderStatusID']) {
                $details['OrderStatusName'] = $status['Name'];
            }
        }
        if (!isset($details['OrderStatusName'])) return null;

        return new Order($details);
    }
    else return null;
}

/**
 * Iterates through array to make safe versions of each order
 * @param array $details 2d array of orders from db
 * @return array|null array of Order objects, or null if none can be made "safe"
 */
function CreateMultipleSafeOrders($details) {
    $orders = array();

    if (is_null($details)) return null;

    foreach ($details as $detail) {
        $order = CreateSafeOrder($detail);
        if (!is_null($order)) array_push($orders, $order);
    }

    if (!empty($orders)) {
        return $orders;
    }
    else return null;
}

/**
 * --INTERNAL USE ONLY-- checks for product to make sure it's all legit
 * @param int $productID PID of product
 * @param int $quantity Quantity of product
 * @return Product|string Product if succeeded, otherwise err message
 */
function ProductAndQuantityCheck($productID, $quantity){
    global $Product;
    //check legitimate quantity
    if (!isset($quantity) || !(gettype($quantity) == "integer") || !($quantity >= 0)) return "Invalid product quantity";
    //check PID is int (no SQL injection allowed here sorry)
    if (!CheckExists($productID) || !(gettype($productID) == "integer")) return "Invalid product ID";
    //check product is legit
    $product = CreateSafeProduct($Product->getProductByID($productID));
    if ($product == null) return "Product does not exist";
    //check product has enough stock
    if ($product->getStock() < $quantity) return "Not enough product stock for request";
    //passed all checks
    return $product;
}

/**
 * Adds specified product to user's basket
 * @param int $productID PID of the product
 * @param int $quantity Quantity of specified product to add to basket
 * @return string Empty if succeeded (i.e. evaluates to false) or an err message if failed
 */
function AddProductToBasket($productID, $quantity) {
    global $Order;

    // Check login
    if (!CheckLoggedIn()) {
        return "Not logged in, please log in to add items to basket";
    }

    // Init array for product
    $product = ProductAndQuantityCheck($productID, $quantity);
    if (gettype ($product) == "string") {
        return $product;
    }

    // Quantity needs additional check to make sure it isn't 0
    if ($quantity === 0) {
        return "Product quantity should be bigger than 0";
    }

    // Create basket if none found, otherwise add item to it (also checks for ownership of basket)
    $basket = CreateSafeOrder($Order->getAllOrdersByOrderStatusNameAndCustomerID("basket", $_SESSION["uid"])[0]);
    $prodPrice = $product->getPrice() * $quantity;
    if (!$basket) {
        // Basket doesn't exist, so it makes a new one
        $orderStatID = $Order->getOrderStatusIDByName("basket");
        if (!$orderStatID) {
            return "Error creating basket";
        }

        $orderID = $Order->createOrder(array("customerID" => $_SESSION["uid"], "totalAmount" => $prodPrice, "orderStatusID" => $orderStatID));

        if (!$orderID) {
            return "Error initializing basket";
        }

        $ol = $Order->createOrderLine(array("orderID" => $orderID, "productID" => $productID, "quantity" => $quantity));
        if ($ol == null) {
            return "Error adding item to basket";
        } else {
            return "";
        }
    }

    // Check if product is already in the basket
    $orderLines = CreateMultipleSafeOrderLines($Order->getAllOrderLinesByOrderID($basket->getOrderID()));

    if (is_null($orderLines)) {
        return false;
    }

    foreach ($orderLines as $orderLine) {
        if ($orderLine->getProductID() == $productID) {
            return ModifyProductQuantityInBasket($productID, $orderLine->getQuantity() + $quantity);
        }
    }

    // Product not in basket, appending
    if ($Order->createOrderLine(array("orderID" => $basket->getOrderID(), "productID" => $productID, "quantity" => $quantity))) {
        $err = $Order->updateOrderDetails($basket->getOrderID(), "TotalAmount", $basket->getTotalAmount() + $prodPrice);
        if (!$err) {
            return "Error updating database";
        } else {
            return "";
        }
    } else {
        return "Error adding to basket";
    }
}

/**
 * Changes quantity of specified product in user's basket, a quantity of 0 will delete product from basket
 * @param int $productID PID of the product
 * @param int $quantity New quantity
 * @return string Empty if succeeded (i.e. evaluates to false) or an err message if failed
 */
function ModifyProductQuantityInBasket($productID, $quantity) {
    global $Order;
    //check login
    if (!CheckLoggedIn()) return false;
    //init array for product
    $product = ProductAndQuantityCheck($productID, $quantity);
    if (gettype($product) == "string") return $product;

    //gets basket to update (also checks ownership)
    $basket = CreateSafeOrder($Order->getAllOrdersByOrderStatusNameAndCustomerID("basket", $_SESSION["uid"])[0]);
    if (!$basket) return "Error retrieving basket";
    $allOrderLines = CreateMultipleSafeOrderLines($Order->getAllOrderLinesByOrderID($basket->getOrderID()));
    if (is_null($allOrderLines)) return "Basket has no items in it";

    //fetch orderLine with product in it
    $orderLine = null;
    foreach ($allOrderLines as $curOrderLine) if ($curOrderLine->getProductID() == $productID) $orderLine = $curOrderLine;
    if (is_null($orderLine)) return "Item not in basket";

    //check for orderLine deletion
    if ($quantity == 0) {
        if (!$Order->deleteOrderLine($basket->getOrderID(), $product->getProductID())) return "Error removing item from basket";
        if (!$Order->getAllOrderLinesByOrderID($basket->getOrderID())) { 
            $err = $Order->deleteOrder($basket->getOrderID());
            if (!$err) return "Error deleting basket";
            else return "";
        }
        $err = $Order->updateOrderDetails($basket->getOrderID(), "TotalAmount", $basket->getTotalAmount()-($orderLine->getQuantity()*$product->getPrice()));
        if (!$err) return "Error updating basket";
        else return "";
    }

    //change quantity and update price to match
    $oldPrice = $orderLine->getQuantity() * $product->getPrice();
    $newPrice = $quantity * $product->getPrice();
    $newTotal = $basket->getTotalAmount() - $oldPrice + $newPrice;
    $err =  $Order->updateOrderLineDetails($basket->getOrderID(), $product->getProductID(), "Quantity", $quantity);
    if (!$err) return "Error updating item in basket";
    $err1 = $Order->updateOrderDetails($basket->getOrderID(), "TotalAmount", $newTotal);
    if (!$err1) return "Error updating basket";
    else return "";
}

/**
 * Checks out the basket (if it exists), of the logged in customer
 * @return string Empty if succeeded (i.e. evaluates to false) or an err message if failed
 */
function CheckoutBasket() {
    global $Order;
    if (!CheckLoggedIn()) return "User is not logged in, please log in to check out";
    //fetch basket
    $basket = CreateSafeOrder($Order->getAllOrdersByOrderStatusNameAndCustomerID("basket", $_SESSION["uid"])[0]);
    if (is_null($basket)) return "Cannot check out empty basket";

    $err = $Order->updateOrderDetails($basket->getOrderID(), "OrderStatusID", $Order->getOrderStatusIDByName("ready"));
    if (!$err)  return "Error checking out basket";
    else return "";
}

/**
 * --INTERNAL USE ONLY-- Adds the orderLines to the Order
 * @param array $orderLines Array of OrderLines
 * @param Order $basket The Order to attach the OrderLines to
 * @return boolean True if succeeded, otherwise false
 */
function AddOrderLinesToOrder($orderLines, &$basket) {
    foreach ($orderLines as $orderLine) {
        if (is_null($orderLine)) return false;
        $basket->addOrderLine($orderLine);
    }
    return true;
}


/**
 * Retrieves the customer's basket
 * @return Order|string Order with every orderLine attached, or err message
 */
function GetCustomerBasket() {
    global $Order;

    if (!CheckLoggedIn()) {
        return "User not logged in, please log in to view your basket";
    }

    // Retrieve first (should be only) order with status "basket"
    $basket = CreateSafeOrder($Order->getAllOrdersByOrderStatusNameAndCustomerID("basket", $_SESSION["uid"])[0]);
    // Check if any orders are found
    if (is_null($basket)) {
        return "Basket does not exist";
    }

    // Retrieve order lines for the order
    $orderLines = CreateMultipleSafeOrderLines($Order->getAllOrderLinesByOrderID($basket->getOrderID()));
    // Check if any order lines are found
    if (is_null($orderLines)) {
        return "Empty basket";
    }

    // Format order lines
    if (!AddOrderLinesToOrder($orderLines, $basket)) {
        return "Error retrieving basket";
    }

    return $basket;
}

/**
 * Retrieves all previous orders for a customer (not incl. basket)
 * @return array|string array of Order objects if success, otherwise err message
 */
function GetPreviousOrders() {
    global $Order;
    if (!CheckLoggedIn()) return "User not logged in";

    //retrieve orders
    $allOrders = CreateMultipleSafeOrders($Order->getAllOrdersByCustomerID($_SESSION["uid"]));
    if (is_null($allOrders)) {
        return "Customer has no orders";
    }

    //removes basket from orders
    $orders = array();
    foreach ($allOrders as $order) {
        if ($order->getOrderStatusName() != "basket") {
            array_push($orders, $order);
        }
    }
    if (empty($orders)) {
        return "Customer has no previous orders";
    }

    foreach ($orders as &$order) {
        //add orderLines to each order
        $orderLines = CreateMultipleSafeOrderLines($Order->getAllOrderLinesByOrderID($order->getOrderID()));
        if (is_null($orderLines)) return "Error: order with reference number " . $order->getOrderID() . " is empty";
        $err = AddOrderLinesToOrder($orderLines, $order);
        if (!$err) return "Error retrieving order with reference number " . $order->getOrderID();
    }

    //return array
    return $orders;
}

/**
 * Retrieves all orders, with orderLines attached
 * @return array|boolean array of Order objects if success, otherwise false
 */
function GetAllOrders() {
    global $Order;

    //retrieve all orders
    $allOrders = CreateMultipleSafeOrders($Order->getAllOrders());
    if (is_null($allOrders)) return false;

    //add orderLines to each order
    foreach ($allOrders as &$order) {
        $orderLines = CreateMultipleSafeOrderLines($Order->getAllOrderLinesByOrderID($order->getOrderByID()));
        if (is_null($orderLines)) return false;
        $err = AddOrderLinesToOrder($orderLines, $order);
        if (!$err) return false;
    }

    return $allOrders;
}

?>
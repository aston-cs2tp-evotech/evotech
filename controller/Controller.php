<?php

//  --------------------------------------------------
//
//
//              CUSTOMER FUNCTIONS
//
//
//  --------------------------------------------------

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
 * @var AdminModel The admin model for interacting with the database
 */
$Admin = new AdminModel($pdo);

/**
 * Check if a variable is safe to evaluate
 * @param mixed $var Variable to check
 * @return boolean True if $var is safe and exists, otherwise false
 */
function CheckExists($var) {
    return (isset($var) && !empty($var));
}

/**
 * Converts html chars to prevent html injection (supports up to 1d arrays)
 * @param $params Any param to escape html injection 
 */
function escapeHTML(&...$params) {
    foreach ($params as &$param) {
        if (!is_array($param) && (gettype($param) == "string")) $param = htmlspecialchars($param);
        else if (!is_array($param)) continue;
        else {
            foreach ($param as &$p) { 
                if (gettype($p) == "string") $p = htmlspecialchars($p);
            }
        }
    }
}

/**
 * Iterates through details from db to ensure every key exists for the Customer object
 * @param array $details Details array from the database
 * @return Customer|null A customer object with all details, or null if any didn't exist 
 */
function CreateSafeCustomer($details) {
    $badCustomer = false;
    $keys = array('CustomerID', 'Email', 'Username', 'CustomerAddress', 'PasswordHash', 'CreatedAt', 'UpdatedAt');

    //check every key
    foreach ($details as $key => $detail) {
        if (!in_array($key, $keys)) $badCustomer = true;
        else unset($keys[array_search($key, $keys)]); //removes key, to check all necessary fields exist
    }

    //create customer
    if (!$badCustomer && empty($keys)) {
        return new Customer($details);
    }
    else return null;
}

/**
 * Iterates through details from db to ensure every key exists for the Admin object
 * @param array $details Details array from the database
 * @return Admin|null An admin object with all details, or null if any didn't exist 
 */
function CreateSafeAdmin($details) {
    $badAdmin = false;
    $keys = array('AdminID', 'Username', 'PasswordHash', 'CreatedAt', 'UpdatedAt');

    //check every key
    foreach ($details as $key => $detail) {
        if (!in_array($key, $keys)) $badAdmin = true;
        else unset($keys[array_search($key, $keys)]); //removes key, to check all necessary fields exist
    }

    //create admin
    if (!$badAdmin && empty($keys)) {
        return new Admin($details);
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
 * @return boolean True if login succeeded, otherwise false
 */
function AttemptLogin($user, $pass) {
    global $Customer;
    escapeHTML($user, $pass);
    if (!CheckExists($user) || !(gettype($user) == "string")) return false;
    if (!CheckExists($pass) || !(gettype($pass) == "string")) return false;
    //attempts to fetch details via username
    $customerArray = $Customer->getCustomerByUsername($user);
    if (!CheckExists($customerArray)) {
        //falls back to fetching via email
        if (!filter_var($user, FILTER_VALIDATE_EMAIL)) return false;
        $customerArray = $Customer->getCustomerByEmail($user);
        if (!CheckExists($customerArray)) return false;
    }
    $details = CreateSafeCustomer($customerArray);
    //checks passwords match
    if (password_verify($pass, $details->getPasswordHash())) {
        $_SESSION["uid"] = $details->getUID();
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
    escapeHTML($details);
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
 * Get count of all customers in the database
 * 
 * @return int|boolean The count of customers if success, otherwise false
 */
function GetCustomerCount() {
    global $Customer;
    $count = $Customer->getCustomerCount();
    if (!is_null($count)) return $count;
    else return false;
}

/**
 * Get all customers in the database
 * 
 * @return array|boolean Array of customers if success, otherwise false
 */
function GetAllCustomers() {
    global $Customer;
    $customers = $Customer->getAllCustomers();
    if (!$customers) return false;
    foreach ($customers as &$customer) {
        $cust = CreateSafeCustomer($customer);
        if ($cust == null) return false;
        $customer = $cust;
    }
    return $customers;
}

/**
 * Updates a specified field in the database for a customer 
 * @param array $details Associative array containing field to change, new value and other relevant info
 * @return string Empty if succeeded, or a string to indicate where it failed
 */
function UpdateCustomerDetail($details) {
    global $Customer;
    escapeHTML($details);
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
 * Gets customer by their ID
 * 
 * @param int $customerID The ID of the customer
 * @return Customer|boolean The customer if success, otherwise false
 */
function GetCustomerByID($customerID) {
    global $Customer;
    escapeHTML($customerID);
    $customer = CreateSafeCustomer($Customer->getCustomerByUID($customerID));
    if ($customer) return $customer;
    else return false;
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
    global $Product;
    $badProd = false;
    $keys = array('ProductID', 'Name', 'Price', 'Stock', 'Description', 'CategoryID', 'CreatedAt', 'UpdatedAt');

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
        //add all reviews to product
        $revs = array();
        $reviews = $Product->getProductReviewsByProduct($details['ProductID']);
        if (is_null($reviews)) return null;
        
        foreach ($reviews as $review) {
            $rev = CreateSafeProductReview($review);
            if (is_null($rev)) return null;
            array_push($revs, $rev);
        }

        $details['Reviews'] = $revs;

        $temp = new Product($details);
        AddProductImagesToProduct($temp);
        $err = AddCategoryToProduct($temp);
        if (!$err) return $temp;
        else return null;
    }
    else return null;
}

/**
 * Creates a productReview object with all details if exists
 * @param array $details Details array from db
 * @return ProductReview|null Product with required details, or null
 */
function CreateSafeProductReview($details) {
    global $Customer;
    $badRev = false;
    $keys = array('ProductID', 'CustomerID', 'Rating', 'Review', 'CreatedAt', 'UpdatedAt');

    //check each required key
    foreach ($details as $key => $detail) {
        if (!in_array($key, $keys)) $badRev = true;
        else unset($keys[array_search($key, $keys)]);
    }

    if (!$badRev && (empty($keys))) {
        $cust = CreateSafeCustomer($Customer->getCustomerByUID($details['CustomerID']));
        if (is_null($cust)) return null;
        $details["CustomerName"] = $cust->getUsername();
        return new ProductReview($details);
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
    escapeHTML($productID);
    try {
        // convert to int
        $productID = (int)$productID;
    } catch (Exception $e) {
        return false;
    }
    if (!checkExists($productID) || !(gettype($productID) == "integer")) return false;
    $prod = CreateSafeProduct($Product->getProductByID($productID));
    if ($prod == null) return false;
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
        if ($product->getStock() > 0) {
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
 * @return array|boolean Array of products if succeeded, otherwise false
 */
function GetAllProducts() {
    global $Product;
    $products = $Product->getAllProducts();
    if (!$products) return false;
    foreach ($products as &$product) {
        $prod = CreateSafeProduct($product);
        if ($prod == null) return false;
        $product = $prod;
    }
    return $products;
}

/**
 * Get count of all products in the database
 * 
 * @return int|boolean The count of products if success, otherwise false
 */
function GetProductCount() {
    global $Product;
    $count = $Product->getProductCount();
    if (!is_null($count)) return $count;
    else return false;
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
 * Gets all categories from the database
 * @return array|boolean Array of categories if succeeded, otherwise false
 */
function GetAllCategories() {
    global $Product;
    $categories = $Product->getCategories();
    if (!$categories) return false;
    return $categories;
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
        return "Database Error";
    }

    //iterate through categories to find correct one
    foreach ($categories as $category) {
        if ($product->getCategoryID() == $category["CategoryID"]) {
            $product->setCategoryName($category["CategoryName"]);
        }
    }

    //check it has category
    if (empty($product->getCategoryName())) {
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
            if ($product->getCategoryID() == $category["CategoryID"]) {
                $product->setCategoryName($category["CategoryName"]);
            }
        }
    }

    //check all products have a category
    foreach ($products as $product) {
        if (empty($product->getCategoryName())) {
            return $product->getName() . " has no category associated with it";
        }
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
    escapeHTML($category);
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
    escapeHTML($category);
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
   escapeHTML($productID);
   // ID check
   if (!CheckExists($productID)) {
       return "Invalid productID";
   }

   // Fetch product
   $product = CreateSafeProduct($Product->getProductByID($productID));
   if (!CheckExists($product)) {
       return "Database Error";
   }

   // Gets similar products from the same category
   $products = GetAllStockedByCategory($product->getCategoryName());
   if (gettype($products) == "string") {
       // If not enough products in the same category, fetch from other categories
       $allCategories = $Product->getCategories();
       if (!$allCategories) {
           return "Database Error";
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

/**
 * Checks if a customer has bought the product, and has not already left a review
 * @param int $customerID The unqiue identifier of the customer
 * @param int $productID The unique identifier of the product
 * @return boolean True if they are allowed to, otherwise false
 */
function CheckCanLeaveReview($customerID, $productID) {
    global $Product;
    $bought = false;
    //check ids
    if (!CheckExists($customerID)) return false;
    if (!CheckExists($productID)) return false;
    try {
        $customerID = (int)$customerID;
    } 
    catch (Exception $e) {
        return false;
    }
    try {
        $customerID = (int)$customerID;
    }
    catch (Exception $e) {
        return false;
    }
    //check if bought product
    $prev = GetPreviousOrders();
    if (!$prev) return false;
    foreach ($prev as $order) {
        foreach ($order->getOrderLines() as $ol) {
            if ($ol->getProductID() == $productID) {
                $bought = true;
                break;
            }
        }
    }

    if (!$bought) return false;

    //check if has left review
    if (!($Product->getProductReview($productID, $customerID))) return true;
    else return false;
}

/**
 * --INTERNAL USE ONLY-- Checks all vars for leaving a review
 * @param int $productID The product's ID
 * @param int $customerID The customer's ID
 * @param int $rating The rating value
 * @param string $review The review
 * @return string Empty if ok, otherwise an error message
 */
function ReviewVarChecks($productID, $customerID, $rating, $review) {
    if (!CheckExists($productID)) return "Missing productID";
    if (!CheckExists($customerID)) return "Missing customerID";
    if (!CheckExists($rating)) return "Missing rating";
    if (!(CheckExists($review)) || !(gettype($review) == "string")) return "Invalid review";
    try {
        $productID = (int)$productID;
    }
    catch (Exception $e) {
        return "Invalid productID";
    }
    try {
        $customerID = (int)$customerID;
    }
    catch (Exception $e) {
        return "Invalid customerID";
    }
    try {
        $rating = (int)$rating;
    }
    catch (Exception $e) {
        return "Invalid rating";
    }
    if (!($rating > 0 && $rating < 6)) return "Rating must be between 1 and 5";
    if (strlen($review) > 200) return "Review must be 200 characters or less";
    return "";
}

/**
 * Creates a rating for a product
 * @param int $productID The product's ID
 * @param int $customerID The customer's ID
 * @param int $rating The rating value
 * @param string $review The review
 * @return string Empty if success, otherwise an error message
 */
function CreateReview($productID, $customerID, $rating, $review) {
    global $Product;
    escapeHTML($review);

    //param checks
    $err = ReviewVarChecks($productID, $customerID, $rating, $review);
    if (!empty($err)) return $err;

    $success = $Product->addProductReview(array("ProductID" => $productID, "CustomerID" => $customerID, "Rating" => $rating, "Review" => $review));
    if (is_null($success)) return "Failed adding review to database";
    else return "";

}

/**
 * Updates a rating for a product
 * @param int $productID The product's ID
 * @param int $customerID The customer's ID
 * @param int $rating The rating value
 * @param string $review The review
 * @return string Empty if success, otherwise an error message
 */
function UpdateReview($productID, $customerID, $rating, $review) {
    global $Product;
    escapeHTML($review);

    //param checks
    $err = ReviewVarChecks($productID, $customerID, $rating, $review);
    if (!$err) return $err;

    if (!$Product->updateProductReview($productID, $customerID, "Rating", $rating)) return "Error updating review";
    if (!$Product->updateProductReview($productID, $customerID, "Review", $review)) return "Error updating review";
    
    return "";
}

/**
 * Deletes a product review
 * @param int $productID The product's ID
 * @param int $customerID The customer's ID
 * @return boolean True if success, otherwise false
 */
function DeleteReview($productID, $customerID) {
    global $Product;
    //id checks
    if (!CheckExists($customerID)) return false;
    if (!CheckExists($productID)) return false;
    try {
        $customerID = (int)$customerID;
    } 
    catch (Exception $e) {
        return false;
    }
    try {
        $customerID = (int)$customerID;
    }
    catch (Exception $e) {
        return false;
    }

    return $Product->deleteProductReview($productID, $customerID);
}

/**
 * Gets all productReviews by a customer
 * @param int $customerID The customer's ID
 * @return array|boolean Array of ProductReview objects, or false
 */
function GetAllReviewsByCustomer($customerID) {
    global $Product;

    if (!CheckExists($customerID)) return false;
    try {
        $customerID = (int)$customerID;
    }
    catch (Exception $e) {
        return false;
    }

    $reviews = array();
    $revs = $Product->getProductReviewsByCustomer($customerID);
    if (is_null($revs)) return false;

    foreach ($revs as $rev) {
        $review = CreateSafeProductReview($rev);
        if (is_null($review)) return false;
        array_push($reviews, $review);
    }

    return $reviews;
}

/**
 * Gets all reviews on a product with a certain rating
 * @param int $productID The product's ID
 * @param int $rating The rating value
 * @return array|boolean Array of ProductReview objects, or false
 */
function GetAllReviewsByRating($productID, $rating) {
    global $Product;

    //type checks
    if (!CheckExists($productID)) return false;
    if (!CheckExists($rating)) return false;
    try {
        $productID = (int)$productID;
    }
    catch (Exception $e) {
        return false;
    }
    try {
        $rating = (int)$rating;
    }
    catch (Exception $e) {
        return false;
    }

    $reviews = array();
    $revs = $Product->getProductReviewsByProductRating($productID, $rating);
    if (is_null($revs)) return false;

    foreach ($revs as $rev) {
        $review = CreateSafeProductReview($rev);
        if (is_null($review)) return false;
        array_push($reviews, $review);
    }

    return $reviews;
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
    $keys = array('ProductID', 'Quantity', 'OrderID', 'CreatedAt', 'UpdatedAt');

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
 * Get all order statuses
 * @return array|boolean Array of order statuses if success, otherwise false
 */
function GetAllOrderStatuses() {
    global $Order;
    $statuses = $Order->getAllOrderStatuses();
    if (is_null($statuses)) return false;
    return $statuses;
}

/**
 * Iterates through every detail from db to ensure every needed key exists
 *  @param array $details the array from the db query
 *  @return Order|null Order with all required info, or null if failed
 */ 
function CreateSafeOrder($details) {
    global $Order;
    $badOrder = false;
    $keys = array('OrderID', 'CustomerID', 'TotalAmount', 'OrderStatusID', 'CheckedOutAt','CreatedAt', 'UpdatedAt');

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
 * @return Product|boolean Product if succeeded, otherwise false
 */
function ProductAndQuantityCheck($productID, $quantity){
    global $Product;
    escapeHTML($productID, $quantity);
    //check legitimate quantity
    if (!isset($quantity) || !(gettype($quantity) == "integer") || !($quantity >= 0)) return false;
    //check PID is int (no SQL injection allowed here sorry)
    if (!CheckExists($productID) || !(gettype($productID) == "integer")) return false;
    //check product is legit
    $product = CreateSafeProduct($Product->getProductByID($productID));
    if ($product == null) return false;
    //check product has enough stock
    if ($product->getStock() < $quantity) return false;
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
    escapeHTML($productID, $quantity);

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
    $basket = CreateSafeOrder($Order->getAllOrdersByOrderStatusNameAndCustomerID("basket", $_SESSION["uid"])[0]);
    $prodPrice = $product->getPrice() * $quantity;
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
        return $Order->updateOrderDetails($basket->getOrderID(), "TotalAmount", $basket->getTotalAmount() + $prodPrice);
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
    escapeHTML($productID, $quantity);
    //check login
    if (!CheckLoggedIn()) return false;
    //init array for product
    $product = ProductAndQuantityCheck($productID, $quantity);
    if (!$product) return false;

    //gets basket to update (also checks ownership)
    $basket = CreateSafeOrder($Order->getAllOrdersByOrderStatusNameAndCustomerID("basket", $_SESSION["uid"])[0]);
    if (!$basket) return false;
    $allOrderLines = CreateMultipleSafeOrderLines($Order->getAllOrderLinesByOrderID($basket->getOrderID()));
    if (is_null($allOrderLines)) return false;

    //fetch orderLine with product in it
    $orderLine = null;
    foreach ($allOrderLines as $curOrderLine) if ($curOrderLine->getProductID() == $productID) $orderLine = $curOrderLine;
    if (is_null($orderLine)) return false;

    //check for orderLine deletion
    if ($quantity == 0) {
        if (!$Order->deleteOrderLine($basket->getOrderID(), $product->getProductID())) return false;
        if (!$Order->getAllOrderLinesByOrderID($basket->getOrderID())) return $Order->deleteOrder($basket->getOrderID());
        return $Order->updateOrderDetails($basket->getOrderID(), "TotalAmount", $basket->getTotalAmount()-($orderLine->getQuantity()*$product->getPrice()));
    }

    //change quantity and update price to match
    $oldPrice = $orderLine->getQuantity() * $product->getPrice();
    $newPrice = $quantity * $product->getPrice();
    $newTotal = $basket->getTotalAmount() - $oldPrice + $newPrice;
    if (!$Order->updateOrderLineDetails($basket->getOrderID(), $product->getProductID(), "Quantity", $quantity)) return false;
    return $Order->updateOrderDetails($basket->getOrderID(), "TotalAmount", $newTotal);
}

/**
 * Checks out the basket (if it exists), of the logged in customer
 * @return boolean True if succeeded, otherwise false
 */
function CheckoutBasket() {
    global $Order;
    if (!CheckLoggedIn()) return false;
    //fetch basket
    $basket = CreateSafeOrder($Order->getAllOrdersByOrderStatusNameAndCustomerID("basket", $_SESSION["uid"])[0]);
    if (is_null($basket)) return false;

    $orderID = $basket->getOrderID();
    $basket = GetOrderByID($orderID);
    // For each orderLine, reduce the stock of the product by the quantity in the orderLine
    $orderLines = $basket -> getOrderLines();
    foreach ($orderLines as $orderLine) {
        $productID = $orderLine -> getProductID();
        $quantity = $orderLine -> getQuantity();
        $product = GetProductByID($productID);
        $updatedStock = $product -> getStock() - $quantity;
        if ($product -> getStock() < $quantity) {
            return false;
        }
        $result = UpdateProductDetail($productID, "Stock", $updatedStock);
        if ($result == 0) {
            return false;
        }
    }
    $result1 = $Order->updateOrderDetails($basket->getOrderID(), "OrderStatusID", $Order->getOrderStatusIDByName("ready"));
    $result2 = $Order->updateOrderDetails($basket->getOrderID(), "CheckedOutAt", date("Y-m-d H:i:s"));
    if ($result1 && $result2) {
        return true;
    }
    return false;

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
 * @return Order|boolean Order with every orderLine attached, or false if failed
 */
function GetCustomerBasket() {
    global $Order;

    if (!CheckLoggedIn()) {
        return false;
    }
    
    // Retrieve first (should be only) order with status "basket"
    $basketOrders = $Order->getAllOrdersByOrderStatusNameAndCustomerID("basket", $_SESSION["uid"]);
    if ($basketOrders == null) {
        return false;
    }
    $basketOrder = $basketOrders[0];
    $basket = CreateSafeOrder($basketOrder);
    // Check if any orders are found
    if (is_null($basket)) {
        return false;
    }

    // Retrieve order lines for the order
    $orderLines = CreateMultipleSafeOrderLines($Order->getAllOrderLinesByOrderID($basket->getOrderID()));
    // Check if any order lines are found
    if (is_null($orderLines)) {
        return false;
    }

    // Format order lines
    if (!AddOrderLinesToOrder($orderLines, $basket)) {
        return false;
    }

    return $basket;
}

/**
 * Retrieves all previous orders for a customer (not incl. basket)
 * @return array|boolean array of Order objects if success, otherwise false
 */
function GetPreviousOrders() {
    global $Order;
    if (!CheckLoggedIn()) return false;

    //retrieve orders
    $allOrders = CreateMultipleSafeOrders($Order->getAllOrdersByCustomerID($_SESSION["uid"]));
    if (is_null($allOrders)) {
        return false;
    }

    //removes basket from orders
    $orders = array();
    foreach ($allOrders as $order) {
        if ($order->getOrderStatusName() != "basket") {
            array_push($orders, $order);
        }
    }
    if (empty($orders)) {
        return false;
    }

    foreach ($orders as &$order) {
        //add orderLines to each order
        $orderLines = CreateMultipleSafeOrderLines($Order->getAllOrderLinesByOrderID($order->getOrderID()));
        if (is_null($orderLines)) return false;
        $err = AddOrderLinesToOrder($orderLines, $order);
        if (!$err) return false;
    }

    //return array
    return $orders;
}

/**
 * Returns stock to product and sets order status to Cancelled or Returned
 * @param int $orderID The unique identifier of the order
 * @param string $status canclled or returned
 * @return boolean True if success, otherwise false
 */
function CancelOrReturnOrder($orderID, $status) {
    global $Order, $Product;
    //check fields
    $fields = array("cancelled", "returned");
    if (!CheckExists($status) || !(gettype($status) == "string") || !in_array($status, $fields)) return false;
    if (!CheckExists($orderID)) return false;
    try {
        $orderID = (int)$orderID;
    }
    catch (Exception $e) {
        return false;
    }

    //fetch order and orderlines
    $order = CreateSafeOrder($Order->getOrderByID($orderID));
    if (is_null($order)) return false;

    $orderLines = CreateMultipleSafeOrderLines($Order->getAllOrderLinesByOrderID($orderID));
    if (is_null($orderLines)) return false;

    foreach ($orderLines as $orderLine) {
        $quantity = $orderLine->getQuantity();
        $prod = CreateSafeProduct($Product->getProductByID($orderLine->getProductID()));
        if (is_null($prod)) return false;

        //update stock
        $newQuantity = $prod->getStock() + $quantity;
        $success = $Product->updateProductDetail($orderLine->getProductID(), "Stock", $newQuantity);
        if (!$success) return false;
    }

    $statusID = $Order->getOrderStatusIDByName($status);
    if (is_null($statusID)) return false;

    return $Order->updateOrderDetails($order->getOrderID(), "OrderStatusID", $statusID);
}

//  --------------------------------------------------
//
//
//              ADMIN FUNCTIONS
//
//
//  --------------------------------------------------

/**
 * Check API Token validity & attempt to generate token if expired recently
 * @param string $token API token
 * @return string|boolean Token if valid, otherwise false
 */
function VerfiyToken($token) {
    global $Admin;
    escapeHTML($token);
    //preliminary token checks
    if (!(CheckExists($token)) || (!(gettype($token) == "string"))) return false;
    $tk = $Admin->getTokenByID($token);
    if (is_null($tk)) return false;

    $tkDate = new DateTime($tk["ExpiresAt"]);

    //check if token has expired
    if ($tkDate < new DateTime()) {
        //check token expired in the last 5 mins
        if ($tkDate->add(new DateInterval("PT5M")) >= new DateTime()) {
            //generate new token and delete old one
            $newTk = GenerateToken($tk["AdminID"]);
            $success = $Admin->deleteToken($tk["Token"]);

            if ($success && (!empty($newTk)))  {
                $_SESSION["adminToken"] = $newTk;
                return $newTk;
            }
            else return false;
        }
        else return false;
    } 
    else return $token;
}

/**
 * Retrieves the Admin object related to a token
 * 
 * @param string $token The token to check
 * @return Admin|boolean Admin object if success, otherwise false
 */
function GetAdminByToken($token) {
    global $Admin;
    escapeHTML($token);
    $tk = VerfiyToken($token);
    if (!$tk) return false;

    $admin = CreateSafeAdmin($Admin->getAdminByToken($tk));
    if ($admin) return $admin;
    else return false;
}

/**
 * Create a token
 * @param int $adminID The admin to associate to (defaults to $_SESSION["adminID"])
 * @param DateTime $expiry The expiry time for the token (defaults to now+20mins)
 * @param string $name The name for token access type
 * @return string The token, or an empty string if failed
 */
function GenerateToken($adminID = null, $expiry=null, $name="ADMIN_DASHBOARD_ACCESS") {
    global $Admin;
    //assign current admin as adminID if not supplied
    if (is_null($adminID)) {
        if (!CheckAdminLoggedIn()) return "";
        else $adminID = $_SESSION["adminUID"];
    }
    else {
        //check adminID
        try {
            $adminID = (int) $adminID;
        }
        catch (Exception $e) {
            return "";
        }

        $ad = $Admin->getAdminByUID($adminID);
        if (is_null($ad)) return "";
    }

    //create DateTime and add 20 mins to it
    if (is_null($expiry)) {
        $expiry = new DateTime();
        $expiry->add(new DateInterval("PT20M"));
    }
    else {
        //check expiry is actually DateTime
        try {
            $expiry->add(new DateInterval("PT0M"));
        }
        catch (Exception $e) {
            return "";
        }
    }
    if (!(CheckExists($name)) || !(gettype($name) == "string")) return "";

    $tk = $Admin->createToken($adminID, $expiry, $name);
    if (is_null($tk)) return "";
    else return $tk["Token"];
}

/**
 * Checks all tokens, and deletes ones that have expired more than 5 mins ago
 */
function PruneTokens() {
    global $Admin;
    $tokens = $Admin->getAllTokens();
    $currTime = new DateTime();
    foreach ($tokens as $token) {
        $tkTime = new DateTime($token["ExpiresAt"]);
        $tkTime->add(new DateInterval("PT5M"));

        if ($tkTime < $currTime) $Admin->deleteToken($token["Token"]);
    }
}

/**
 * Deletes token from database
 * @param string $token The token to remove
 * @return boolean True if succeeded, otherwise false
 */
function RevokeToken($token) {
    global $Admin;
    escapeHTML($token);

    //check token is valid and exists
    if (!(CheckExists($token)) or !(gettype($token) == "string")) return false;

    $tk = $Admin->getTokenByID($token);
    if (is_null($tk)) return false;

    //delete token
    $result = $Admin->deleteToken($tk["Token"]);

    return $result;
}

/**
 * Add an admin to the database
 * 
 * @param array $details Associative array containing key as field to update and value as new value
 * @return string Empty if success, otherwise a string to indicate where it failed
 */
function AddAdmin($details) {
    global $Admin;
    escapeHTML($details);
    if (!CheckExists($details["Username"]) || !CheckExists($details["Password"])) return "Invalid request";
    if (!preg_match("/[a-zA-Z0-9]+/", $details["Username"])) return "Invalid username";
    if (strlen($details["Password"]) < 7) return "Password should be longer than 7 characters";
    $details["Password_hash"] = password_hash($details["Password"], PASSWORD_DEFAULT);
    $err = $Admin->addAdmin($details);
    if (!$err) return "Error creating admin";
    return "";
}

/**
 * Get Admin by their ID
 * 
 * @param int $adminID The ID of the admin
 */
function GetAdminByID($adminID) {
    global $Admin;
    escapeHTML($adminID);
    $admin = CreateSafeAdmin($Admin->getAdminByUID($adminID));
    if ($admin) return $admin;
    else return false;
}

/**
 * Update the details of an admin by an admin
 * 
 * @param array $details Associative array containing key as field to update and value as new value
 * @return string Empty if success, otherwise a string to indicate where it failed
 */
function UpdateAdminByAdmin($details) {
    global $Admin;
    escapeHTML($details);
    foreach ($details as $key => $value) {
        if ($key == "AdminID") {
            continue;
        }
        $err = $Admin->updateAdmin($details["AdminID"], $key, $value);
        if (!$err) {
            return "Error updating " . $key . " for admin " . $details["AdminID"];
        }
    }
    return "";
}

/**
 * Get all admins in the database
 * 
 * @return array|boolean Array of admins if success, otherwise false
 */
function GetAllAdmins() {
    global $Admin;
    $admins = $Admin->getAllAdmins();
    
    if (!$admins) return false;
    foreach ($admins as &$admin) {
        $adm = CreateSafeAdmin($admin);
        if ($adm == null) return false;
        $admin = $adm;
    }
    return $admins;
}

/**
 * Get all api tokens in the database
 * 
 * @return array|boolean Array of tokens if success, otherwise false
 */
function GetAllTokens() {
    global $Admin;
    $tokens = $Admin->getAllTokens();
    if (!$tokens) return false;
    return $tokens;
}


/**
 * Update Customer details by admin
 * 
 * @param array $details Associative array containing key as field to update and value as new value
 * @return string Empty if success, otherwise a string to indicate where it failed
 */
function UpdateCustomerByAdmin($details) {
    global $Customer;
    escapeHTML($details);
    foreach ($details as $key => $value) {
        if ($key == "CustomerID") {
            continue;
        }
        $err = $Customer->updateCustomerDetail($details["CustomerID"], $key, $value);
        if (!$err) {
            return "Error updating " . $key . " for customer " . $details["CustomerID"];
        }
    }
    return "";
}

/**
 * Deletes a customer from the database by their ID if there are no orders associated with them
 * 
 * @param int $customerID The ID of the customer
 * @return string Empty if success, otherwise a string to indicate where it failed
 */
function DeleteCustomerByAdmin($customerID) {
    global $Customer;
    global $Order;
    escapeHTML($customerID);
    $orders = $Order->getAllOrdersByCustomerID($customerID);
    if ($orders) {
        return "Customer has orders associated with them";
    }
    $err = $Customer->deleteCustomer($customerID);
    if (!$err) {
        return "Error deleting customer";
    }
    return "";
}

/**
 * Delete an admin from the database by their ID
 * 
 * @param int $adminID The ID of the admin
 * @return string Empty if success, otherwise a string to indicate where it failed
 */
function DeleteAdminByAdmin($adminID) {
    global $Admin;
    escapeHTML($adminID);
    $err = $Admin->deleteAdmin($adminID);
    if (!$err) {
        return "Error deleting admin";
    }
    return "";
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
        $orderLines = CreateMultipleSafeOrderLines($Order->getAllOrderLinesByOrderID($order->getOrderID()));
        if (is_null($orderLines)) return false;
        $err = AddOrderLinesToOrder($orderLines, $order);
        if (!$err) return false;
    }

    return $allOrders;
}

/**
 * Retrieves an order by orderID
 * @param int $orderID The ID of the order
 * @return Order|boolean Order object if success, otherwise false
 */
function GetOrderByID($orderID) {
    global $Order;
    escapeHTML($orderID);
    // Retrieve the order by orderID
    $order = CreateSafeOrder($Order->getOrderByID($orderID));
    if (is_null($order)) {
        return false;
    }

    // Retrieve order lines for the order
    $orderLines = CreateMultipleSafeOrderLines($Order->getAllOrderLinesByOrderID($order->getOrderID()));
    if (is_null($orderLines)) {
        return false;
    }

    // Format order lines
    if (!AddOrderLinesToOrder($orderLines, $order)) {
        return false;
    }

    return $order;
}


/**
 * Updates the status of an order
 * @param int $orderID The ID of the order
 * @param int $newStatusID The new status ID of the order
 * @return boolean True if succeeded, otherwise false
 */
function UpdateOrderStatus($orderID, $newStatusID) {
    global $Order;
    escapeHTML($orderID, $newStatusID);

    // Update the status of the order
    return $Order->updateOrderDetails($orderID, "OrderStatusID", $newStatusID);
}


/**
 * Attempts to log in an admin with the supplied credentials, storing it in $_SESSION
 * @param string $user Admin's username
 * @param string $pass Admin's password
 * @return string Empty if success, otherwise false
 */
function AttemptAdminLogin($user, $pass) {
    global $Admin;
    escapeHTML($user, $pass);
    if (!CheckExists($user) || !(gettype($user) == "string")) return "Invalid Username";
    if (!CheckExists($pass) || !(gettype($pass) == "string")) return "Invalid password";
    //attempts to fetch details via username
    $details = $Admin->getAdminByUsername($user);
    if (is_null($details)) return "Incorrect password or username";
    //checks passwords match
    if (password_verify($pass, $details["PasswordHash"])) {
        $_SESSION["adminUID"] = $details["AdminID"];
        $_SESSION["adminName"] = $details["Username"];
        //assign token
        $token = $Admin->getTokensByAdmin($_SESSION["AdminUID"]);
        if (is_null($token)) $_SESSION["adminToken"] = GenerateToken($_SESSION["AdminUID"]);
        else $_SESSION["adminToken"] = $token[0]["Token"];
        return "";
    }
    else return "Incorrect password or username";
}
/**
 * Checks if an admin is logged in
 * @return boolean True if logged in, otherwise false
 */
function CheckAdminLoggedIn() {
    if (isset($_SESSION["adminUID"]) && !(is_null($_SESSION["adminUID"]))) return true;
    else return false;
}

/**
 * Updates the specified field of a product in db
 * @param int $productID the ID of the product to update
 * @param string $field the field to update
 * @param mixed $value the value to change to
 * @return string Empty if success, otherwise an err message
 */
function UpdateProductDetail($productID, $field, $value) {
    global $Product;
    escapeHTML($productID, $field, $value);
    $fields = array("Name", "Price", "Stock", "Description", "CategoryID");

    try {
        $productID = (int)$productID;
    } catch (Exception $e) {
        return "Invalid productID";
    }
    if (!CheckExists($productID)) return "Invalid productID";
    if (!CheckExists($field) || !(gettype($field) == "string") || !(in_array($field, $fields)))  return "Invalid field";
    if (!CheckExists($value)) return "Empty value";

    $prod = $Product->getProductByID($productID);
    if (is_null($prod)) return "Product does not exist";

    switch ($field){
        case ("Name"):
            if (!(gettype($value) == "string")) return "Invalid name";
            $err = $Product->updateProductDetail($productID, 'Name', $value);
            if (!$err) return "Error changing name";
            else return "";

        case ("Price"):
            try {
                $value = (double)$value;
            } catch (Exception $e) {
                return "Invalid price";
            }
            if (!(gettype($value) == "double")) return "Invalid price";
            $err = $Product->updateProductDetail($productID, 'Price', $value);
            if (!$err) return "Error updating price";
            else return "";

        case ("Stock"):
            try {
                $value = (int)$value;
            } catch (Exception $e) {
                return "Invalid stock";
            }
            if (!(gettype($value) == "integer")) return "Invalid stock";
            $err = $Product->updateProductDetail($productID, 'Stock', $value);
            if (!$err) return "Error updating stock";
            return "";

        case ("Description"):
            if (!(gettype($value) == "string")) return "Invalid description";
            $err = $Product->updateProductDetail($productID, 'Description', $value);
            if (!$err) return "Error updating description";
            else return "";

        case ("CategoryID"):
            try {
                $value = (int)$value;
            } catch (Exception $e) {
                return "Invalid CategoryID";
            }
            if (!(gettype($value) == "integer")) return "Invalid CategoryID";
            $cats = $Product->getCategories();
            if (is_null($cats)) return "Database error";

            $catFound = false;
            foreach ($cats as $cat) {
                if ($cat["CategoryID"] == $value) {
                    $catFound = true;
                    break;
                }
            }
            if (!$catFound) return "Category does not exist";

            $err = $Product->updateProductDetail($productID, 'CategoryID', $value);
            if (!$err) return "Error updating category";
            else return "";

        default:
            return "Invalid category";
        }
}

/**
 * Add a product to the database
 * @param array $details array with all necessary info
 * @return string empty if success, otherwise false
 */
function AddProduct($details) {
    global $Product;
    escapeHTML($details);
    if (!(gettype($details) == "array")) return "Invalid details";
    $fields = array('name', 'price', 'stock', 'description', 'categoryID');

    //check each field and value
    foreach ($details as $key => $detail) {
        if (in_array($key, $fields)) unset($fields[array_search($key, $fields)]);
        else return "Invalid data";

        switch ($key) {
            case "name":
                if (!(gettype($detail) == "string")) return "Invalid name";
                break;

            case "price":
                try {
                    $detail = (int)$detail;
                } catch (Exception $e) {
                    return "Invalid price";
                }
                if (!(gettype($detail) == "integer")) return "Invalid price";
                break;

            case "stock":
                try {
                    $detail = (int)$detail;
                } catch (Exception $e) {
                    return "Invalid price";
                }
                if (!(gettype($detail) == "integer")) return "Invalid stock";
                break;

            case "description":
                if (!(gettype($detail) == "string")) return "Invalid description";
                break;

            case "categoryID":
                try {
                    $detail = (int)$detail;
                } catch (Exception $e) {
                    return "Invalid price";
                }
                if (!(gettype($detail) == "integer")) return "Invalid categoryID";
                $cats = $Product->getCategories();
                if (is_null($cats)) return "Database error";
                // Check if category exists
                foreach ($cats as $cat) {
                    if ($cat["CategoryID"] == $detail) {
                        unset($cats[array_search($cat, $cats)]);
                    }
                }
                break;

            default:
                return "Invalid field specified";
        }
    }

    if (!empty($fields)) return "Not all required fields have been filled";

    $err = $Product->addProduct($details);
    if (is_null($err)) return "Database error";
    else return $err['ProductID'];
}

/**
 * Deletes specified customer from db only if no orders are associated with it
 * 
 * @param int $customerID the customer to delete
 * @return string empty if success, otherwise false
 */


/**
 * Deletes the specified product from db only if no orders are associated with it
 * @param int $productID the product to delete
 * @return string empty if success, otherwise false
 */
function DeleteProduct($productID) {
    global $Product;
    escapeHTML($productID);
    try {
        $productID = (int)$productID;
    } catch (Exception $e) {
        return "Invalid productID";
    }
    $debug_msg = "";
    
    if (!(gettype($productID) == "integer")) return "Invalid productID";
    $prod = $Product->getProductByID($productID);
    if (is_null($prod)) return "Product does not exist";

    $isInOrder = false;
    $orders = GetAllOrders();
    // if no orders are associated with the product, delete it
    foreach ($orders as $order) {
        foreach ($order->getOrderLines() as $orderLine) {
            if ($orderLine->getProductID() == $productID) {
                $isInOrder = true;
                break;
            }
        }
    }
    if ($isInOrder) return "Product is associated with an existing order. Remove it from all orders before deleting it.";

    $err = $Product->deleteProduct($productID);

    if (!$err) return $err;
    else return "";
}

/** */

/**
 * Update the specified field in a customer's details
 * @param int $customerID the ID of the customer
 * @param string $field the field to update
 * @param mixed $value the new value for the field
 * @return string Empty if success, otherwise false
 */
function UpdateCustomerInfo($customerID, $field, $value) {
    global $Customer;
    escapeHTML($customerID, $field, $value);
    $fields = array('Username', 'Email', 'CustomerAddress', 'PasswordHash');

    if (!(CheckExists($customerID) || !(gettype($customerID) == "int"))) return "Invalid customerID";
    if (!(CheckExists($field)) || !(gettype($field) == "string") || !(in_array($field, $fields))) return "Invalid field";
    if (!(CheckExists($value))) return "Invalid value";
    
    $cust = $Customer->getCustomerByUID($customerID);
    if (is_null($cust)) return "Customer does not exist";

    switch ($field){
        case "Username":
            if (!(gettype($value) == "string")) return "Invalid username";
            $err = $Customer->updateCustomerDetail($customerID, "Username", $value);
            if (!$err) return "Database error";
            else return "";
        case "Email":
            if (!(gettype($value) == "string") || !(filter_var($value, FILTER_VALIDATE_EMAIL))) return "Invalid email";
            $err = $Customer->updateCustomerDetail($customerID, "Email", $value);
            if (!$err) return "Database error";
            else return "";
        case "CustomerAddress":
            if (!(gettype($value) == "string")) return "Invalid address";
            $err = $Customer->updateCustomerDetail($customerID, "CustomerAddress", $value);
            if (!$err) return "Database error";
            else return "";
        case "PasswordHash":
            if (!(gettype($value) == "string")) return "Invalid password";
            $hash = password_hash($value, PASSWORD_DEFAULT);
            $err = $Customer->updateCustomerDetail($customerID, "PasswordHash", $hash);
            if (!$err) return "Database error";
            else return "";
        default:
            return "Invalid category";
    }
}

/**
 * Add an image to the product
 * @param int $productID The ID of the product
 * @param string $fileName The name of the file
 * @param boolean $mainImage Whether the image is the main image
 * @return string Empty if success, otherwise false
 */
function AddProductImage($productID, $fileName, $mainImage) {
    global $Product;
    escapeHTML($productID, $fileName, $mainImage);
    try {
        $productID = (int)$productID;
    } catch (Exception $e) {
        return "Invalid productID";
    }
    if (!(gettype($fileName) == "string")) return "Invalid fileName";
    if (!(gettype($mainImage) == "boolean")) return "Invalid mainImage";

    $prod = $Product->getProductByID($productID);
    if (is_null($prod)) return "Product does not exist";

    $err = $Product->addProductImage($productID, $fileName);
    if (!$err) return "Database error";
    $err = $Product->updateProductImage($productID, $fileName, "MainImage", $mainImage);
    if (!$err) return "Database error";
    else return "";
}

/**
 * Update Image for a product
 * 
 * @param int $productID The ID of the product
 * @param string $fileName The name of the file
 * @param boolean $mainImage Whether the image is the main image
 * @return string Empty if success, otherwise false
 */
function UpdateProductImage($productID, $fileName, $mainImage) {
    global $Product;
    escapeHTML($productID, $fileName, $mainImage);
    try {
        $productID = (int)$productID;
    } catch (Exception $e) {
        return "Invalid productID";
    }
    if (!(gettype($fileName) == "string")) return "Invalid fileName";
    if (!(gettype($mainImage) == "boolean")) return "Invalid mainImage";

    $prod = $Product->getProductByID($productID);
    if (is_null($prod)) return "Product does not exist";
    $imageName = GetProductByID($productID)->getMainImage();


    $err = $Product->updateProductImage($productID, $imageName, "FileName", $fileName);
    if (!$err) return "Database error";
    $err = $Product->updateProductImage($productID, $imageName, "MainImage", $mainImage);
    if (!$err) return "Database error";
    else return "";
}

/**
 * Gets all productReviews
 * @return array|boolean Array of ProductReview objects, or false
 */
function GetAllReviews() {
    global $Product;

    $reviews = array();
    $revs = $Product->getAllProductReviews();
    if (is_null($revs)) return false;

    foreach ($revs as $rev) {
        $review = CreateSafeProductReview($rev);
        if (is_null($review)) return false;
        array_push($reviews, $review);
    }

    return $reviews;
}

/**
 * Gets all reviews on a product
 * --API USE ONLY, PRODUCTS HAVE ALL REVIEWS ATTACHED--
 * @param int $productID The product's ID
 * @return array|boolean Array of ProductReview objects, or false
 */
function GetAllReviewsByProduct($productID) {
    global $Product;

    $reviews = array();
    $revs = $Product->getProductReviewsByProduct($productID);
    if (is_null($revs)) return false;

    foreach ($revs as $rev) {
        $review = CreateSafeProductReview($rev);
        if (is_null($review)) return false;
        array_push($reviews, $review);
    }

    return $reviews;
}

/**
 * Deletes all reviews associated with a product
 * @param int $productID The product's ID
 * @return boolean True if success, otherwise false
 */
function DeleteReviewsByProduct($productID) {
    global $Product;

    if (!CheckExists($productID)) return false;
    try {
        $productID = (int)$productID;
    }
    catch (Exception $e) {
        return false;
    }

    return $Product->deleteProductReviewsByProduct($productID);
}

/**
 * Deletes all reviews associated with a customer
 * @param int $customerID The customer's ID
 * @return boolean True if success, otherwise false
 */
function DeleteReviewsByCustomer($customerID) {
    global $Product;

    if (!CheckExists($customerID)) return false;
    try {
        $customerID = (int)$customerID;
    }
    catch (Exception $e) {
        return false;
    }

    return $Product->deleteProductReviewsByCustomer($customerID);
}
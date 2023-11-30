<?php

// This file currently stores functions made for authentication
// Alone it does not work, and will only be merged once the skeleton for the site exists
// Ordering of the functions will come later

/**
 * @var array Global variable to store customer info (username, address etc)
 */
$userInfo = Array();

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
    global $userInfo;
    //checks if it is set and not false
    if (checkExists($_SESSION["uid"])) {
        $uid = $_SESSION["uid"];
        //querys the database to get user info
        $userInfo = Customer->getCustomerByUID($uid);
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
    //attempts to fetch details via username
    $details = Customer->getCustomerByUsername($user);
    if (!checkExists($details)) {
        //falls back to fetching via email
        $details = Customer->getCustomerByEmail($user);
        if (!checkExists($details)) return false;
    }
    //checks passwords match
    if (password_verify(password_hash($pass, PASSWORD_DEFAULT), $details["pass"])) {
        $_SESSION["uid"] = $details["uid"];
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
    //TODO: NEEDS FIXING!!!! regex for email
    //needs support for symbols and 3rd level domains
    if (checkExists($details["email"]) && preg_match("/[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[a-zA-Z0-9]+/", $details["email"])){
        //regex for username (also checks if length > 0)
        if (checkExists($details["username"]) && preg_match("/[a-zA-Z0-9]+/", $details["username"])) {
            //TODO: add constraints to address
            if (checkExists($details["customer_address"])) {
                //check first pass is good and longer than 7
                if (checkExists($details["password"]) && strlen($details["password"]) > 7) {
                    //checks confirmation is good
                    if (checkExists($details["confirmpass"]) && $details["password"] === $details["confirmpass"]) {
                        //these checks are left to the end to minimise the number of potential pointless queries

                        //checks if username exists (returns false if it doesn't)
                        if (!Customer->getCustomerByUsername($details["username"])) {
                            //checks if email is taken
                            if (!Customer->getCustomerByEmail($details["email"])) {
                                //if here, then all checks have passed

                                //hashes password
                                $details["password_hash"] = password_hash($details["password"], PASSWORD_DEFAULT);
                                
                                //any response will evaluate to true
                                if (Customer->registerCustomer($details)) return "";
                                else return "Database error occured, please try registering again.";
                            } 
                            else return "Email is already in use, please use another one";
                        }
                        else return "Username is already taken. Please choose another one";
                    }
                    else return "Invalid Confirmation password, ensure it both passwords match";
                } 
                else return "Invalid Password, ensure it is longer than 7 characters";
            }
            else return "Invalid Address";
        }
        else return "Invalid Username";
    }
    else return "Invalid Email";
}

/**
 * Unsets both global arrays and destroys the session
 */
function LogOut() {
    unset($userInfo);
    unset($_SESSION);
    session_destroy();
    //route back to main page or page that responds that user logged out
}


?>
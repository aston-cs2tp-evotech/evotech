<?php

class CustomerModel {
    
    private $database;

    
    /**
     * CustomerModel constructor.
     *
     * @param PDO $database The database connection.
     */
    public function __construct($database) {
        $this->database = $database;
    }


    /**
     * Retrieve customer details by UID.
     *
     * @param int $customerID The unique identifier of the customer.
     * @return array|null The customer details or null if not found.
     */
    public function getCustomerByUID($customerID) {
        $query = "SELECT * FROM `Customers` WHERE `CustomerID` = :customerID";
        $statement = $this->database->prepare($query);
        $statement->bindParam(':customerID', $customerID, PDO::PARAM_INT);

        if ($statement->execute()) {
            $customer = $statement->fetch(PDO::FETCH_ASSOC);
            return $customer ? $customer : null;
        } else {
            return null; // Failed to execute query
        }
    }


    /**
     * Retrieve customer details by username.
     *
     * @param string $username The username of the customer.
     * @return array|null The customer details or null if not found.
     */
    public function getCustomerByUsername($username) {
        $query = "SELECT * FROM `Customers` WHERE `Username` = :username";
        $statement = $this->database->prepare($query);
        $statement->bindParam(':username', $username, PDO::PARAM_STR);

        if ($statement->execute()) {
            $customer = $statement->fetch(PDO::FETCH_ASSOC);
            return $customer ? $customer : null;
        } else {
            return null; // Failed to execute query
        }
    }



    /**
     * Retrieve customer details by email.
     *
     * @param string $email The email of the customer.
     * @return array|null The customer details or null if not found.
     */
    public function getCustomerByEmail($email) {
        $query = "SELECT * FROM `Customers` WHERE `Email` = :email";
        $statement = $this->database->prepare($query);
        $statement->bindParam(':email', $email, PDO::PARAM_STR);

        if ($statement->execute()) {
            $customer = $statement->fetch(PDO::FETCH_ASSOC);
            return $customer ? $customer : null;
        } else {
            return null; // Failed to execute query
        }
    }


    /**
     * Register a new user.
     *
     * @param array $userData The user data including email, username, address, and password hash.
     * @return array|null The registered customer details or null if registration fails.
     */
    public function registerUser($userData) {
        $insertQuery = "INSERT INTO `Customers` (
                            `Email`, 
                            `Username`, 
                            `CustomerAddress`, 
                            `PasswordHash`
                        ) VALUES (
                            :email, 
                            :username, 
                            :customerAddress, 
                            :passwordHash
                        )";
        $insertStatement = $this->database->prepare($insertQuery);
        $insertStatement->bindParam(':email', $userData['email'], PDO::PARAM_STR);
        $insertStatement->bindParam(':username', $userData['username'], PDO::PARAM_STR);
        $insertStatement->bindParam(':customerAddress', $userData['customer_address'], PDO::PARAM_STR);
        $insertStatement->bindParam(':passwordHash', $userData['password_hash'], PDO::PARAM_STR);

        return $insertStatement->execute() ? $this->getCustomerByEmail($userData['email']) : null;
    }

    /**
     * Update a customer's details.
     *
     * @param int $UID The unique identifier of the customer.
     * @param string $field The field to update.
     * @param string $value The new value.
     * @return bool True if the update is successful, false otherwise.
     */
    public function updateCustomerDetail($UID, $field, $value) {
        // Validate $field to prevent SQL injection
        $allowedFields = ['Email', 'Username', 'CustomerAddress', 'PasswordHash'];
        if (!in_array($field, $allowedFields)) {
            return false; 
        }

        $query = "UPDATE `Customers` SET `$field` = :value WHERE `CustomerID` = :customerID";
        $statement = $this->database->prepare($query);
        $statement->bindParam(':value', $value, PDO::PARAM_STR);
        $statement->bindParam(':customerID', $UID, PDO::PARAM_INT);

        try {
            return $statement->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}

?>

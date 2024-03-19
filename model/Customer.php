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
    public function registerCustomer($userData) {
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

    /**
     * Count the number of customers in the database.
     * 
     * @return int The number of customers in the database.
     */
    
    public function getCustomerCount() {
        $query = "SELECT COUNT(*) FROM `Customers`";
        $statement = $this->database->prepare($query);
        $statement->execute();
        return $statement->fetchColumn();
    }

    /**
     * Retrieve all customers in the database.
     * 
     * @return array|null An array of all customers or null if no customers are found.
     */
    public function getAllCustomers() {
        $query = "SELECT * FROM `Customers`";
        $statement = $this->database->prepare($query);
        if ($statement->execute()) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return null;
        }
    }

    /**
     * Delete a customer from the database
     * 
     * @param int $customerID The unique identifier of the customer.
     */
    public function deleteCustomer($customerID) {
        $query = "DELETE FROM `Customers` WHERE `CustomerID` = :customerID";
        $statement = $this->database->prepare($query);
        $statement->bindParam(':customerID', $customerID, PDO::PARAM_INT);
        return $statement->execute();
    }
}


class Customer {

    private $customerID;
    private $email;
    private $username;
    private $address;
    private $passwordHash;
    private $createdAt;
    private $updatedAt;

    /**
     * Construct a new Customer object.
     * 
     * @param array $customerData The customer details.
     */
    public function __construct($customerData) {
        $this->customerID = $customerData['CustomerID'];
        $this->email = $customerData['Email'];
        $this->username = $customerData['Username'];
        $this->address = $customerData['CustomerAddress'];
        $this->passwordHash = $customerData['PasswordHash'];
        $this->createdAt = $customerData['CreatedAt'];
        $this->updatedAt = $customerData['UpdatedAt'];
    }


    /**
     * Retrieve the customer's unique identifier.
     *
     * @return int The customer's unique identifier.
     */
    public function getUID() {
        return $this->customerID;
    }


    /**
     * Set the customer's unique identifier.
     * 
     * @param int $UID The customer's unique identifier.
     */
    public function setUID($UID) {
        $this->customerID = $UID;
    }


    /**
     * Retrieve the customer's email.
     *
     * @return string The customer's email.
     */
    public function getEmail() {
        return $this->email;
    }


    /**
     * Set the customer's email.
     * 
     * @param string $email The customer's email.
     */
    public function setEmail($email) {
        $this->email = $email;
    }


    /**
     * Retrieve the customer's username.
     *
     * @return string The customer's username.
     */
    public function getUsername() {
        return $this->username;
    }


    /**
     * Set the customer's username.
     * 
     * @param string $username The customer's username.
     */
    public function setUsername($username) {
        $this->username = $username;
    }


    /**
     * Retrieve the customer's address.
     *
     * @return string The customer's address.
     */
    public function getAddress() {
        return $this->address;
    }


    /**
     * Set the customer's address.
     * 
     * @param string $address The customer's address.
     */
    public function setAddress($address) {
        $this->address = $address;
    }


    /**
     * Retrieve the customer's password hash.
     *
     * @return string The customer's password hash.
     */
    public function getPasswordHash() {
        return $this->passwordHash;
    }


    /**
     * Set the customer's password hash.
     * 
     * @param string $passwordHash The customer's password hash.
     */
    public function setPasswordHash($passwordHash) {
        $this->passwordHash = $passwordHash;
    }

    /**
     * Retrieve the customer's creation date.
     *
     * @return string The customer's creation date.
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * Retrieve the customer's last update date.
     *
     * @return string The customer's last update date.
     */
    public function getUpdatedAt() {
        return $this->updatedAt;
    }
}
?>

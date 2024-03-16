<?php

class AdminModel {
    
    private $database;

    
    /**
     * AdminModel constructor.
     *
     * @param PDO $database The database connection.
     */
    public function __construct($database) {
        $this->database = $database;
    }


    /**
     * Retrieve admin details by UID.
     *
     * @param int $adminID The unique identifier of the admin.
     * @return array|null The admin details or null if not found.
     */
    public function getAdminByUID($adminID) {
        $query = "SELECT * FROM `AdminCredentials` WHERE `AdminID` = :adminID";
        $statement = $this->database->prepare($query);
        $statement->bindParam(':adminID', $adminID, PDO::PARAM_INT);

        if ($statement->execute()) {
            $admin = $statement->fetch(PDO::FETCH_ASSOC);
            return $admin ? $admin : null;
        } else {
            return null; // Failed to execute query
        }
    }

    /**
     * Retrieve admin details by username.
     *
     * @param string $username The username of the admin.
     * @return array|null The admin details or null if not found.
     */
    public function getAdminByUsername($username) {
        $query = "SELECT * FROM `AdminCredentials` WHERE `Username` = :username";
        $statement = $this->database->prepare($query);
        $statement->bindParam(':username', $username, PDO::PARAM_STR);

        if ($statement->execute()) {
            $admin = $statement->fetch(PDO::FETCH_ASSOC);
            return $admin ? $admin : null;
        } else {
            return null; // Failed to execute query
        }
    }

    /**
     * Add a new admin to the database.
     * 
     * @param array $adminData The admin details to add inluding username and password.
     * @return array|null The registered admin details or null if registration fails.
     */
    public function addAdmin($adminData) {
        $insertQuery = "INSERT INTO 'AdminCredentials' (
                        'Username',
                        'PasswordHash'
                    ) VALUES (
                        :username,
                        :passwordHash
                    )";
        $insertStatement = $this->database->prepare($insertQuery);
        $insertStatement->bindParam(':username', $adminData['username'], PDO::PARAM_STR);
        $insertStatement->bindParam(':passwordHash', $adminData['password_hash'], PDO::PARAM_STR);

        return $insertStatement->execute() ? $this->getAdminByUsername($adminData['username']) : null;
    }

    /**
     * Update an admin's details.
     * 
     * @param int $adminID The unique identifier of the admin.
     * @param string $field The field to update.
     * @param string $value The new value.
     * @return bool True if the update was successful, false otherwise.
     */
    public function updateAdmin($adminID, $field, $value) {
        // Validate $field to prevent SQL injection
        $allowedFields = ['Username', 'PasswordHash'];
        if (!in_array($field, $allowedFields)) {
            return false;
        }

        $query = "UPDATE `AdminCredentials` SET `$field` = :value WHERE `AdminID` = :adminID";
        $statement = $this->database->prepare($query);
        $statement->bindParam(':value', $value, PDO::PARAM_STR);
        $statement->bindParam(':adminID', $adminID, PDO::PARAM_INT);

        try {
            return $statement->execute();
        } catch (PDOException $e) {
            return false;
        }

    }

    /**
     * Get all admins
     * 
     * @return array|null The list of all admins or null if no admins found.
     */
    public function getAllAdmins() {
        $query = "SELECT * FROM `AdminCredentials`";
        $statement = $this->database->prepare($query);

        if ($statement->execute()) {
            $admins = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $admins ? $admins : null;
        } else {
            return null; // Failed to execute query
        }
    }
}

class Admin {
    private $adminID;
    private $username;
    private $passwordHash;


    /**
     * Admin constructor.
     *
     * @param array $adminData The admin details.
     */
    public function __construct($adminData) {
        $this->adminID = $adminData['AdminID'];
        $this->username = $adminData['Username'];
        $this->passwordHash = $adminData['PasswordHash'];
    }

    /**
     * Get the admin's unique identifier.
     *
     * @return int The admin's unique identifier.
     */
    public function getUID() {
        return $this->adminID;
    }

    /**
     * Set the admin's unique identifier.
     *
     * @param int $adminID The admin's unique identifier.
     */
    public function setUID($adminID) {
        $this->adminID = $adminID;
    }

    /**
     * Get the admin's username.
     *
     * @return string The admin's username.
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Set the admin's username.
     *
     * @param string $username The admin's username.
     */
    public function setUsername($username) {
        $this->username = $username;
    }

    /**
     * Get the admin's password hash.
     *
     * @return string The admin's password hash.
     */
    public function getPasswordHash() {
        return $this->passwordHash;
    }

    /**
     * Set the admin's password hash.
     *
     * @param string $passwordHash The admin's password hash.
     */
    public function setPasswordHash($passwordHash) {
        $this->passwordHash = $passwordHash;
    }
}
?>

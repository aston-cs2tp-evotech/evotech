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
        $insertQuery = "INSERT INTO `AdminCredentials` (
                        `Username`,
                        `PasswordHash`
                    ) VALUES (
                        :username,
                        :passwordHash
                    )";
        $insertStatement = $this->database->prepare($insertQuery);
        $insertStatement->bindParam(':username', $adminData['Username'], PDO::PARAM_STR);
        $insertStatement->bindParam(':passwordHash', $adminData['Password_hash'], PDO::PARAM_STR);
    
        return $insertStatement->execute() ? $this->getAdminByUsername($adminData['Username']) : null;
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

    /**
     * Creates an API token for a specified admin
     * 
     * @param int $adminID the adminID
     * @param DateTime $expiry the expiry time of the token
     * @return array|null The created token, or null if failed.
     */
    public function createToken($adminID, $expiry, $name) {
        //format DateTime to string
        $exp = $expiry->format("Y-m-d H:i:s");
        //generate a token
        $token = "";
        $hex = "0123456789abcdef";
        $h = str_split($hex);
        do {
            $token = "";
            for ($i = 0; $i < 16; $i++) {
                $token = $token . $h[random_int(0, count($h)-1)];
            }
        // this is meant to stop collisions but also
        // scary while loop :(
        } while (!is_null($this->getTokenByID($token)));


        $insertQuery = "INSERT INTO `APITokens` (
                        `AdminID`,
                        `Token`,
                        `ExpiresAt`,
                        `TokenName`
                    ) VALUES (
                        :adminID,
                        :token,
                        :expiry,
                        :TokenName
                    )";
        $insertStatement = $this->database->prepare($insertQuery);
        $insertStatement->bindParam(':adminID', $adminID, PDO::PARAM_INT);
        $insertStatement->bindParam(':token', $token, PDO::PARAM_STR);
        $insertStatement->bindParam(':expiry', $exp, PDO::PARAM_STR);
        $insertStatement->bindParam(':TokenName', $name, PDO::PARAM_STR);

        return $insertStatement->execute() ? $this->getTokenByID($token) : null;
    }

    /**
     * Gets API token by supplied ID
     * 
     * @param string $token The token
     * @return array|null The token w/ info or null if not found.
     */
    public function getTokenByID($token) {
        $query = "SELECT * FROM `APITokens` WHERE `Token` = :token";
        $statement = $this->database->prepare($query);
        $statement->bindParam(':token', $token, PDO::PARAM_STR);

        if ($statement->execute()) {
            $tk = $statement->fetch(PDO::FETCH_ASSOC);
            return $tk ? $tk : null;
        } else {
            return null;
        }
    }

    /**
     * Gets API token associated with admin
     * 
     * @param int $adminID the AdminID for the token
     * @return array|null The tokens associated with the admin, or null if not found.
     */
    public function getTokensByAdmin($adminID)  {
        $query = "SELECT * FROM `APITokens` WHERE `AdminID` = :adminID";
        $statement = $this->database->prepare($query);
        $statement->bindParam(':adminID', $adminID, PDO::PARAM_INT);

        if ($statement->execute()) {
            $tk = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $tk ? $tk : null;
        } else {
            return null;
        }
    }

    /**
     * Gets all API tokens
     * 
     * @return array|null Array of tokens, or null if failed
     */
    public function getAllTokens() {
        $query = "SELECT * FROM `APITokens`";
        $statement = $this->database->prepare($query);

        if ($statement->execute()) {
            $tk = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $tk ? $tk : null;
        } else {
            return null;
        }
    }


    /**
     * Deletes an API token
     * 
     * @param string $token the Token to delete
     * @return boolean True if success, otherwise false
     */
    public function deleteToken($token) {
        $query = "DELETE FROM `APITokens` WHERE `Token` = :token";
        $statement = $this->database->prepare($query);
        $statement->bindParam(':token', $token, PDO::PARAM_STR);

        return $statement->execute();
    }

    /**
     * Get an admin by a token associated with them
     * 
     * @param string $token the token
     * @return array|null The admin associated with the token, or null if not found.
     */
    public function getAdminByToken($token) {
        $query = "SELECT * FROM `APITokens` WHERE `Token` = :token";
        $statement = $this->database->prepare($query);
        $statement->bindParam(':token', $token, PDO::PARAM_STR);

        if ($statement->execute()) {
            $tk = $statement->fetch(PDO::FETCH_ASSOC);
            return $tk ? $this->getAdminByUID($tk['AdminID']) : null;
        } else {
            return null;
        }
    }
}

class Admin {
    private $adminID;
    private $username;
    private $passwordHash;
    private $createdAt;
    private $updatedAt;


    /**
     * Admin constructor.
     *
     * @param array $adminData The admin details.
     */
    public function __construct($adminData) {
        $this->adminID = $adminData['AdminID'];
        $this->username = $adminData['Username'];
        $this->passwordHash = $adminData['PasswordHash'];
        $this->createdAt = $adminData['CreatedAt'];
        $this->updatedAt = $adminData['UpdatedAt'];
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

    /**
     * Get the admin's creation date.
     *
     * @return string The admin's creation date.
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * Set the admin's creation date.
     *
     * @param string $createdAt The admin's creation date.
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }

    /**
     * Get the admin's last update date.
     *
     * @return string The admin's last update date.
     */
    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    /**
     * Set the admin's last update date.
     *
     * @param string $updatedAt The admin's last update date.
     */
    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;
    }
}

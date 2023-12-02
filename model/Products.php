<?php

class ProductModel {

    private $database;

    /**
     * ProductModel constructor.
     *
     * @param PDO $database The database connection.
     */
    public function __construct($database) {
        $this->database = $database;
    }

    /**
     * Retrieve product details by ID.
     *
     * @param int $productID The unique identifier of the product.
     * @return array|null The product details or null if not found.
     */
    public function getProductByID($productID) {
        $query = "SELECT * FROM `Products` WHERE `ProductID` = :productID";
        $statement = $this->database->prepare($query);
        $statement->bindParam(':productID', $productID, PDO::PARAM_INT);

        if ($statement->execute()) {
            $product = $statement->fetch(PDO::FETCH_ASSOC);
            return $product ? $product : null;
        } else {
            return null; // Failed to execute query
        }
    }

    /**
     * Retrieve product details by name.
     *
     * @param string $productName The name of the product.
     * @return array|null The product details or null if not found.
     */
    public function getProductByName($productName) {
        $query = "SELECT * FROM `Products` WHERE `Name` = :productName";
        $statement = $this->database->prepare($query);
        $statement->bindParam(':productName', $productName, PDO::PARAM_STR);

        if ($statement->execute()) {
            $product = $statement->fetch(PDO::FETCH_ASSOC);
            return $product ? $product : null;
        } else {
            return null; // Failed to execute query
        }
    }

    /**
     * Retrieve all products.
     *
     * @return array|null An array of product details or null if no products found.
     */
    public function getAllProducts() {
        $query = "SELECT * FROM `Products`";
        $statement = $this->database->prepare($query);

        if ($statement->execute()) {
            $products = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $products ? $products : null;
        } else {
            return null; // Failed to execute query
        }
    }

    /**
     * Add a new product.
     *
     * @param array $productData The product data including name, price, and stock.
     * @return array|null The added product details or null if adding fails.
     */
    public function addProduct($productData) {
        $insertQuery = "INSERT INTO `Products` (
                            `Name`, 
                            `Price`, 
                            `Stock`
                        ) VALUES (
                            :name, 
                            :price, 
                            :stock
                        )";
        $insertStatement = $this->database->prepare($insertQuery);
        $insertStatement->bindParam(':name', $productData['name'], PDO::PARAM_STR);
        $insertStatement->bindParam(':price', $productData['price'], PDO::PARAM_STR);
        $insertStatement->bindParam(':stock', $productData['stock'], PDO::PARAM_INT);

        return $insertStatement->execute() ? $this->getProductByName($productData['name']) : null;
    }

    /**
     * Update a product's details.
     *
     * @param int $productID The unique identifier of the product.
     * @param string $field The field to update.
     * @param string|int $value The new value.
     * @return bool True if the update is successful, false otherwise.
     */
    public function updateProductDetail($productID, $field, $value) {
        // Validate $field to prevent SQL injection
        $allowedFields = ['Name', 'Price', 'Stock'];
        if (!in_array($field, $allowedFields)) {
            return false; 
        }

        $query = "UPDATE `Products` SET `$field` = :value WHERE `ProductID` = :productID";
        $statement = $this->database->prepare($query);
        $statement->bindParam(':value', $value, PDO::PARAM_STR);
        $statement->bindParam(':productID', $productID, PDO::PARAM_INT);

        try {
            return $statement->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Delete a product.
     *
     * @param int $productID The unique identifier of the product.
     * @return bool True if the deletion is successful, false otherwise.
     */
    public function deleteProduct($productID) {
        $query = "DELETE FROM `Products` WHERE `ProductID` = :productID";
        $statement = $this->database->prepare($query);
        $statement->bindParam(':productID', $productID, PDO::PARAM_INT);

        try {
            return $statement->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    
}

?>

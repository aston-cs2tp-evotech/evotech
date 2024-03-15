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
        $allowedFields = ['Name', 'Price', 'Stock', 'Description', 'CategoryID'];
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

    
    /**
     * Retrieve compatibility types for a product.
     *
     * @param int $productID The unique identifier of the product.
     * @return array|null An array of compatibility types or null if not found.
     */
    public function getProductCompatibility($productID) {
        $query = "SELECT `Compatibility`.`CompatibilityName`, `ProductCompatibility`.`SlotType`
                  FROM `Compatibility`
                  JOIN `ProductCompatibility` ON `Compatibility`.`CompatibilityID` = `ProductCompatibility`.`CompatibilityID`
                  WHERE `ProductCompatibility`.`ProductID` = :productID";
        $statement = $this->database->prepare($query);
        $statement->bindParam(':productID', $productID, PDO::PARAM_INT);

        if ($statement->execute()) {
            $compatibility = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $compatibility ? $compatibility : null;
        } else {
            return null; // Failed to execute query
        }
    }

    
    /**
     * Retrieve all products that are compatible with a product.
     * 
     * @param int $productID The unique identifier of the product.
     * @return array|null An array of compatible products or null if not found.
     */
    public function getAllCompatibleProducts($productID) {
        $query = "SELECT `Products`.* FROM `Products`
                  JOIN `ProductCompatibility` ON `Products`.`ProductID` = `ProductCompatibility`.`ProductID`
                  WHERE `ProductCompatibility`.`CompatibilityID` IN (
                      SELECT `CompatibilityID` FROM `ProductCompatibility` WHERE `ProductID` = :productID
                  ) AND `Products`.`ProductID` != :productID";
        $statement = $this->database->prepare($query);
        $statement->bindParam(':productID', $productID, PDO::PARAM_INT);

        if ($statement->execute()) {
            $products = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $products ? $products : null;
        } else {
            return null; // Failed to execute query
        }
    }

    /**
     * Retrieve slot types for a product.
     *
     * @param int $productID The unique identifier of the product.
     * @return array|null An array of slot types or null if not found.
     */
    public function getProductSlots($productID) {
        $query = "SELECT `SlotType` FROM `ProductSlots` WHERE `ProductID` = :productID";
        $statement = $this->database->prepare($query);
        $statement->bindParam(':productID', $productID, PDO::PARAM_INT);

        if ($statement->execute()) {
            $slots = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $slots ? $slots : null;
        } else {
            return null; // Failed to execute query
        }
    }

    /**
     * Retrieve all images for a product.
     * 
     * @param int $productID The unique identifier of the product.
     * @return array|null An array of image details or null if not found.
     */
    public function getProductImages($productID) {
        $query = "SELECT * FROM `ProductImages` WHERE `ProductID` = :productID";
        $statement = $this->database->prepare($query);
        $statement->bindParam(':productID', $productID, PDO::PARAM_INT);

        if ($statement->execute()) {
            $images = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $images ? $images : null;
        } else {
            return null; // Failed to execute query
        }
    }

    /**
     * Add an image for a product.
     * 
     * @param int $productID The unique identifier of the product.
     * @param string $imageName
     */
    public function addProductImage($productID, $imageName) {
        $insertQuery = "INSERT INTO `ProductImages` (`ProductID`, `FileName`) VALUES (:productID, :fileName)";
        $insertStatement = $this->database->prepare($insertQuery);
        $insertStatement->bindParam(':productID', $productID, PDO::PARAM_INT);
        $insertStatement->bindParam(':fileName', $imageName, PDO::PARAM_STR);

        try {
            return $insertStatement->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Update a field for an image
     * 
     * @param int $productID The unique identifier of the product.
     * @param string $imageName The current name of the image file.
     * @param string $field The field to update.
     * @param mixed $value The new value for the $field.
     * @return bool True if changed successfully, false otherwise
     */
    public function updateProductImage($productID, $imageName, $field, $value){
        $allowedFields = ['ProductID', 'FileName', 'MainImage'];
        if (!in_array($field, $allowedFields)) {
            return false;
        }

        $query = "UPDATE `Products` SET `$field` = :val WHERE `ProductID` = :productID AND `FileName` = :fileName";
        $statement = $this->database->prepare($query);
        $statement->bindParam(':val', $value);
        $statement->bindParam(':productID', $productID, PDO::PARAM_INT);
        $statement->bindParam(':fileName', $imageName, PDO::PARAM_STR);

        try {
            return $statement->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Delete an image for a product.
     *
     * @param int $productID The unique identifier of the product.
     * @param string $imageName The name of the image file.
     * @return bool True if the deletion is successful, false otherwise.
     */
    public function deleteProductImage($productID, $imageName) {
        $query = "DELETE FROM `ProductImages` WHERE `ProductID` = :productID AND `FileName` = :fileName";
        $statement = $this->database->prepare($query);
        $statement->bindParam(':productID', $productID, PDO::PARAM_INT);
        $statement->bindParam(':fileName', $imageName, PDO::PARAM_STR);

        try {
            return $statement->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    /**
     * Add compatibility for a product.
     *
     * @param int $productID The unique identifier of the product.
     * @param int $compatibilityID The unique identifier of the compatibility type.
     * @param string $slotType The type of slot compatibility (e.g., USB, PCIe).
     * @return bool True if the addition is successful, false otherwise.
     */
    public function addProductCompatibility($productID, $compatibilityID, $slotType) {
        $insertQuery = "INSERT INTO `ProductCompatibility` (`ProductID`, `CompatibilityID`, `SlotType`) VALUES (:productID, :compatibilityID, :slotType)";
        $insertStatement = $this->database->prepare($insertQuery);
        $insertStatement->bindParam(':productID', $productID, PDO::PARAM_INT);
        $insertStatement->bindParam(':compatibilityID', $compatibilityID, PDO::PARAM_INT);
        $insertStatement->bindParam(':slotType', $slotType, PDO::PARAM_STR);

        try {
            return $insertStatement->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Add slot type for a product.
     *
     * @param int $productID The unique identifier of the product.
     * @param string $slotType The type of slot compatibility (e.g., USB, PCIe).
     * @return bool True if the addition is successful, false otherwise.
     */
    public function addProductSlot($productID, $slotType) {
        $insertQuery = "INSERT INTO `ProductSlots` (`ProductID`, `SlotType`) VALUES (:productID, :slotType)";
        $insertStatement = $this->database->prepare($insertQuery);
        $insertStatement->bindParam(':productID', $productID, PDO::PARAM_INT);
        $insertStatement->bindParam(':slotType', $slotType, PDO::PARAM_STR);

        try {
            return $insertStatement->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Get all categories.
     * 
     * @return array|null An array of category details or null if not found.
     */
    function getCategories(){
        $query = 'SELECT * FROM `Categories`';
        $statement = $this->database->prepare($query);
        
        if ($statement->execute()){
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return null;
        }
    }
    
}

class Product {

    private $productID;
    private $name;
    private $price;
    private $stock;
    private $description;
    private $categoryID;
    private $categoryName;
    private $mainImage;
    private $otherImages;


    /**
     * Construct a new Product object by providing an associative array of product details.
     */
    public function __construct($productDetails) {
        $this->productID = $productDetails['ProductID'];
        $this->name = $productDetails['Name'];
        $this->price = $productDetails['Price'];
        $this->stock = $productDetails['Stock'];
        $this->description = $productDetails['Description'];
        $this->categoryID = $productDetails['CategoryID'];
        $this->categoryName = $productDetails['CategoryName'];
        $this->mainImage = $productDetails['MainImage'];
        $this->otherImages = $productDetails['OtherImages'];
    }


    /**
     * Get the unique identifier of the product.
     * @return int The unique identifier of the product.
     */
    public function getProductID() {
        return $this->productID;
    }

    /**
     * Set the unique identifier of the product.
     * @param int $productID The unique identifier of the product.
     */
    public function setProductID($productID) {
        $this->productID = $productID;
    }


    /**
     * Get the name of the product.
     * @return string The name of the product.
     */
    public function getName() {
        return $this->name;
    }


    /**
     * Set the name of the product.
     * @param string $name The name of the product.
     */
    public function setName($name) {
        $this->name = $name;
    }


    /**
     * Get the price of the product.
     * @return float The price of the product.
     */
    public function getPrice() {
        return $this->price;
    }


    /**
     * Set the price of the product.
     * @param float $price The price of the product.
     */
    public function setPrice($price) {
        $this->price = $price;
    }


    /**
     * Get the stock of the product.
     * @return int The stock of the product.
     */
    public function getStock() {
        return $this->stock;
    }


    /**
     * Set the stock of the product.
     * @param int $stock The stock of the product.
     */
    public function setStock($stock) {
        $this->stock = $stock;
    }


    /**
     * Get the description of the product.
     * @return string The description of the product.
     */
    public function getDescription() {
        return $this->description;
    }


    /**
     * Set the description of the product.
     * @param string $description The description of the product.
     */
    public function setDescription($description) {
        $this->description = $description;
    }


    /**
     * Get the category ID of the product.
     * @return int The category ID of the product.
     */
    public function getCategoryID() {
        return $this->categoryID;
    }


    /**
     * Set the category ID of the product.
     * @param int $categoryID The category ID of the product.
     */
    public function setCategoryID($categoryID) {
        $this->categoryID = $categoryID;
    }


    /**
     * Get the category name of the product.
     * @return string The category name of the product.
     */
    public function getCategoryName() {
        return $this->categoryName;
    }


    /**
     * Set the category name of the product.
     * @param string $categoryName The category name of the product.
     */
    public function setCategoryName($categoryName) {
        $this->categoryName = $categoryName;
    }


    /**
     * Get the main image of the product.
     * @return string The main image of the product.
     */
    public function getMainImage() {
        return $this->mainImage;
    }


    /**
     * Set the main image of the product.
     * @param string $mainImage The main image of the product.
     */
    public function setMainImage($mainImage) {
        $this->mainImage = $mainImage;
    }


    /**
     * Get the other images of the product.
     * @return array The other images of the product.
     */
    public function getOtherImages() {
        return $this->otherImages;
    }


    /**
     * Set the other images of the product.
     * @param array $otherImages The other images of the product.
     */
    public function setOtherImages($otherImages) {
        $this->otherImages = $otherImages;
    }



}

?>

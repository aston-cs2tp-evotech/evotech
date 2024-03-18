<?php

class OrdersModel {

    private $database;


    /**
     * OrdersModel constructor.
     * 
     * @param PDO $database The database connection.
     */
    public function __construct($database) {
        $this->database = $database;

    }

    /**
     * Retrieve all Orders in database.
     * 
     * @return array|null The Orders or null if not found
     */
    public function getAllOrders() {
        $query = "SELECT * FROM `Orders`";
        $statement = $this->database->prepare($query);

        if ($statement->execute()) {
            $orders = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $orders ? $orders : null;
        } else {
            return null; // Failed to execute query
        }
    }

    /**
     * Retrieve Order details by OrderID.
     * 
     * @param int $orderID The unique identifier of the Order.
     * @return array|null The Order details or null if not found.
     */
    public function getOrderByID($orderID) {
        $query = "SELECT * FROM `Orders` WHERE `OrderID` = :orderID";
        $statement = $this->database->prepare($query);
        $statement->bindParam(':orderID', $orderID, PDO::PARAM_INT);

        if ($statement->execute()) {
            $order = $statement->fetch(PDO::FETCH_ASSOC);
            return $order ? $order : null;
        } else {
            return null; // Failed to execute query
        }
    }


    /**
     * Retrieve all Orders for a given Customer.
     * 
     * @param int $customerID The unique identifier of the Customer.
     * @return array|null The Orders or null if not found.
     */
    public function getAllOrdersByCustomerID($customerID) {
        $query = "SELECT * FROM `Orders` WHERE `CustomerID` = :customerID";
        $statement = $this->database->prepare($query);
        $statement->bindParam(':customerID', $customerID, PDO::PARAM_INT);

        if ($statement->execute()) {
            $orders = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $orders ? $orders : null;
        } else {
            return null; // Failed to execute query
        }
    }


    /**
     * Retreive OrderStatusID by Name from OrderStatus table.
     * 
     * @param string $orderStatusName The name of the OrderStatus.
     * @return int|null The OrderStatusID or null if not found.
     */
    public function getOrderStatusIDByName($orderStatusName) {
        $query = "SELECT `OrderStatusID` FROM `OrderStatus` WHERE `Name` = :orderStatusName";
        $statement = $this->database->prepare($query);
        $statement->bindParam(':orderStatusName', $orderStatusName, PDO::PARAM_STR);

        if ($statement->execute()) {
            $orderStatusID = $statement->fetch(PDO::FETCH_ASSOC);
            return $orderStatusID ? $orderStatusID['OrderStatusID'] : null;
        } else {
            return null; // Failed to execute query
        }
    }

    /**
     * Retrieve all Orders for a given OrderStatus Name and CustomerID.
     * 
     * @param string $orderStatusName The name of the OrderStatus.
     * @param int $customerID The unique identifier of the Customer.
     * @return array|null The Orders or null if not found.
     */
    public function getAllOrdersByOrderStatusNameAndCustomerID($orderStatusName, $customerID) {
        $query = "SELECT * FROM `Orders` WHERE `OrderStatusID` = :orderStatusID AND `CustomerID` = :customerID";
        $statement = $this->database->prepare($query);
        $orderStatusID = $this->getOrderStatusIDByName($orderStatusName);
        $statement->bindParam(':orderStatusID', $orderStatusID, PDO::PARAM_INT);
        $statement->bindParam(':customerID', $customerID, PDO::PARAM_INT);

        if ($statement->execute()) {
            $orders = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $orders ? $orders : null;
        } else {
            return null; // Failed to execute query
        }
    }

    /**
     * Retrieve all OrderLines for a given OrderID.
     * 
     * @param int $orderID The unique identifier of the Order.
     * @return array|null The OrderLines or null if not found.
     */
    public function getAllOrderLinesByOrderID($orderID) {
        $query = "SELECT * FROM `OrderLines` WHERE `OrderID` = :orderID";
        $statement = $this->database->prepare($query);
        $statement->bindParam(':orderID', $orderID, PDO::PARAM_INT);

        if ($statement->execute()) {
            $orderLines = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $orderLines ? $orderLines : null;
        } else {
            return null; // Failed to execute query
        }
    }

    /**
     * Creates a new Order.
     * 
     * @param array $orderDetails The Order details
     * @return int|null The OrderID or null if failed to create.
     */
     public function createOrder($orderDetails) {
        $insertQuery = "INSERT INTO `Orders` (
                            `CustomerID`,
                            `TotalAmount`,
                            `OrderStatusID`
                        ) VALUES (
                            :customerID,
                            :totalAmount,
                            :orderStatusID
                        )";

        $insertStatement = $this->database->prepare($insertQuery);
        $insertStatement->bindParam(':customerID', $orderDetails['customerID'], PDO::PARAM_INT);
        $insertStatement->bindParam(':totalAmount', $orderDetails['totalAmount'], PDO::PARAM_STR);
        $insertStatement->bindParam(':orderStatusID', $orderDetails['orderStatusID'], PDO::PARAM_INT);
        
        return $insertStatement->execute() ? $this->database->lastInsertId() : null;
     }

     /**
      * Update an existing Order.
      * 
      * @param int $orderID The unique identifier of the Order.
      * @param string $field The field to update.
      * @param string $value The new value.
      * @return bool True if successful, false otherwise.
      */
    public function updateOrderDetails($orderID, $field, $value) {
        // Validate $field to prevent SQL injection
        $allowedFields = array('CustomerID', 'TotalAmount', 'OrderStatusID');
        if (!in_array($field, $allowedFields)) {
            return false;
        }

        $query = "UPDATE `Orders` SET `$field` = :value WHERE `OrderID` = :orderID";
        $statement = $this->database->prepare($query);
        $statement->bindParam(':value', $value, PDO::PARAM_STR);
        $statement->bindParam(':orderID', $orderID, PDO::PARAM_INT);

        try {
            return $statement->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Delete an existing Order.
     * 
     * @param int $orderID The unique identifier of the Order.
     * @return bool True if successful, false otherwise.
     */
    public function deleteOrder($orderID) {
        $query = "DELETE FROM `Orders` WHERE `OrderID` = :orderID";
        $statement = $this->database->prepare($query);
        $statement->bindParam(':orderID', $orderID, PDO::PARAM_INT);

        try {
            return $statement->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Creates a new OrderLine.
     * 
     * @param array $orderLineDetails The OrderLine details
     * @return int|null The ID of the newly created OrderLine or null if failed to create.
     */
    public function createOrderLine($orderLineDetails) {
        $insertQuery = "INSERT INTO `OrderLines` (
                            `OrderID`,
                            `ProductID`,
                            `Quantity`
                        ) VALUES (
                            :orderID,
                            :productID,
                            :quantity
                        )";

        $insertStatement = $this->database->prepare($insertQuery);
        $insertStatement->bindParam(':orderID', $orderLineDetails['orderID'], PDO::PARAM_INT);
        $insertStatement->bindParam(':productID', $orderLineDetails['productID'], PDO::PARAM_INT);
        $insertStatement->bindParam(':quantity', $orderLineDetails['quantity'], PDO::PARAM_INT);
        
        return $insertStatement->execute() ? $this->database->lastInsertId() : null;
    }

    /**
     * Update an existing OrderLine.
     * 
     * @param int $orderID The unique identifier of the Order
     * @param int $productID The unique identifier of the Product
     * @param string $field The field to update.
     * @param string $value The new value.
     * @return bool True if successful, false otherwise.
     */
    public function updateOrderLineDetails($orderID, $productID, $field, $value) {
        // Validate $field to prevent SQL injection
        $allowedFields = array('Quantity');
        if (!in_array($field, $allowedFields)) {
            return false;
        }

        $query = "UPDATE `OrderLines` SET `$field` = :value WHERE `OrderID` = :orderID AND `ProductID` = :productID";
        $statement = $this->database->prepare($query);
        $statement->bindParam(':value', $value, PDO::PARAM_STR);
        $statement->bindParam(':orderID', $orderID, PDO::PARAM_INT);
        $statement->bindParam(':productID', $productID, PDO::PARAM_INT);

        try {
            return $statement->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    
    /**
     * Delete an existing OrderLine.
     * 
     * @param int $orderID
     * @param int $productID
     * @return bool True if successful, false otherwise.
     */
    public function deleteOrderLine($orderID, $productID) {
        $query = "DELETE FROM `OrderLines` WHERE `OrderID` = :orderID AND `ProductID` = :productID";
        $statement = $this->database->prepare($query);
        $statement->bindParam(':orderID', $orderID, PDO::PARAM_INT);
        $statement->bindParam(':productID', $productID, PDO::PARAM_INT);

        try {
            return $statement->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Retrieve all OrderStatuses.
     * 
     * @return array|null The OrderStatuses or null if not found.
     */
    
    public function getAllOrderStatuses() {
        $query = "SELECT * FROM `OrderStatus`";
        $statement = $this->database->prepare($query);

        if ($statement->execute()) {
            $orderStatuses = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $orderStatuses ? $orderStatuses : null;
        } else {
            return null; // Failed to execute query
        }
    }

    /**
     * Creates a new OrderStatus.
     * 
     * @param string $orderStatusName The name of the OrderStatus.
     * @return int|null The ID of the newly created OrderStatus or null if failed to create.
     */
    public function createOrderStatus($orderStatusName) {
        $insertQuery = "INSERT INTO `OrderStatus` (
                            `Name`
                        ) VALUES (
                            :orderStatusName
                        )";

        $insertStatement = $this->database->prepare($insertQuery);
        $insertStatement->bindParam(':orderStatusName', $orderStatusName, PDO::PARAM_STR);
        
        return $insertStatement->execute() ? $this->database->lastInsertId() : null;
    }

    /**
     * Update an existing OrderStatus.
     * 
     * @param int $orderStatusID The unique identifier of the OrderStatus.
     * @param string $newStatusName The new name of the OrderStatus.
     * @return bool True if successful, false otherwise.
     */
    public function updateOrderStatus($orderStatusID, $newStatusName) {
        $query = "UPDATE `OrderStatus` SET `Name` = :newStatusName WHERE `OrderStatusID` = :orderStatusID";
        $statement = $this->database->prepare($query);
        $statement->bindParam(':newStatusName', $newStatusName, PDO::PARAM_STR);
        $statement->bindParam(':orderStatusID', $orderStatusID, PDO::PARAM_INT);

        try {
            return $statement->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Get count of all Orders.
     * 
     * @return int|null The count of Orders or null if failed to retrieve.
     */
    public function getOrderCount() {
        $query = "SELECT COUNT(*) FROM `Orders`";
        $statement = $this->database->prepare($query);

        if ($statement->execute()) {
            $orderCount = $statement->fetch(PDO::FETCH_ASSOC);
            return $orderCount ? $orderCount['COUNT(*)'] : 0;
        } else {
            return 0; // Failed to execute query
        }
    }
}

class OrderLine {
    
    private $productID;
    private $productName;
    private $quantity;
    private $totalStock;
    private $unitPrice;
    private $totalPrice;
    private $mainImage;
    private $otherImages;

    
    /**
     * Construct a new OrderLine with an associated array
     * 
     * @param array $orderLineDetails The OrderLine details
     */
    public function __construct($orderLineDetails) {
        $this->productID = $orderLineDetails['ProductID'];
        $this->productName = $orderLineDetails['ProductName'];
        $this->quantity = $orderLineDetails['Quantity'];
        $this->totalStock = $orderLineDetails['TotalStock'];
        $this->unitPrice = $orderLineDetails['UnitPrice'];
        $this->totalPrice = $this->unitPrice * $this->quantity;
        $this->mainImage = $orderLineDetails['MainImage'];
        $this->otherImages = $orderLineDetails['OtherImages'];
    }


    /**
     * Get the ProductID of the OrderLine.
     * 
     * @return int The ProductID.
     */
    public function getProductID() {
        return $this->productID;
    }


    /**
     * Set the ProductID of the OrderLine.
     * 
     * @param int $productID The ProductID.
     */
    public function setProductID($productID) {
        $this->productID = $productID;
    }


    /**
     * Get the ProductName of the OrderLine.
     * 
     * @return string The ProductName.
     */
    public function getProductName() {
        return $this->productName;
    }


    /**
     * Set the ProductName of the OrderLine.
     * 
     * @param string $productName The ProductName.
     */
    public function setProductName($productName) {
        $this->productName = $productName;
    }


    /**
     * Get the Quantity of the OrderLine.
     * 
     * @return int The Quantity.
     */
    public function getQuantity() {
        return $this->quantity;
    }


    /**
     * Set the Quantity of the OrderLine.
     * 
     * @param int $quantity The Quantity.
     */
    public function setQuantity($quantity) {
        $this->quantity = $quantity;

        // Update the TotalPrice
        $this->totalPrice = $this->unitPrice * $this->quantity;
    }


    /**
     * Get the TotalStock of the OrderLine.
     * 
     * @return int The TotalStock.
     */
    public function getTotalStock() {
        return $this->totalStock;
    }


    /**
     * Set the TotalStock of the OrderLine.
     * 
     * @param int $totalStock The TotalStock.
     */
    public function setTotalStock($totalStock) {
        $this->totalStock = $totalStock;
    }


    /**
     * Get the UnitPrice of the OrderLine.
     * 
     * @return float The UnitPrice.
     */
    public function getUnitPrice() {
        return $this->unitPrice;
    }


    /**
     * Set the UnitPrice of the OrderLine.
     * 
     * @param float $unitPrice The UnitPrice.
     */
    public function setUnitPrice($unitPrice) {
        $this->unitPrice = $unitPrice;

        // Update the TotalPrice
        $this->totalPrice = $this->unitPrice * $this->quantity;
    }


    /**
     * Get the TotalPrice of the OrderLine.
     * 
     * @return float The TotalPrice.
     */
    public function getTotalPrice() {
        return $this->totalPrice;
    }


    /**
     * Get the MainImage of the OrderLine.
     * 
     * @return string The MainImage.
     */
    public function getMainImage() {
        return $this->mainImage;
    }


    /**
     * Set the MainImage of the OrderLine.
     * 
     * @param string $mainImage The MainImage.
     */
    public function setMainImage($mainImage) {
        $this->mainImage = $mainImage;
    }


    /**
     * Get the OtherImages of the OrderLine.
     * 
     * @return array The OtherImages.
     */
    public function getOtherImages() {
        return $this->otherImages;
    }


    /**
     * Set the OtherImages of the OrderLine.
     * 
     * @param array $otherImages The OtherImages.
     */
    public function setOtherImages($otherImages) {
        $this->otherImages = $otherImages;
    }

}


class Order {

    private $orderID;
    private $customerID;
    private $totalAmount;
    private $orderStatusID;
    private $orderStatusName;
    private $orderLines;


    /**
     * Construct a new Order with an associated array
     * 
     * @param array $orderDetails The Order details
     */
    public function __construct($orderDetails) {
        $this->orderID = $orderDetails['OrderID'];
        $this->customerID = $orderDetails['CustomerID'];
        $this->totalAmount = $orderDetails['TotalAmount'];
        $this->orderStatusID = $orderDetails['OrderStatusID'];
        $this->orderStatusName = $orderDetails['OrderStatusName'];
        $this->orderLines = array();
    }


    /**
     * Get the OrderID of the Order.
     * 
     * @return int The OrderID.
     */
    public function getOrderID() {
        return $this->orderID;
    }


    /**
     * Set the OrderID of the Order.
     * 
     * @param int $orderID The OrderID.
     */
    public function setOrderID($orderID) {
        $this->orderID = $orderID;
    }


    /**
     * Get the CustomerID of the Order.
     * 
     * @return int The CustomerID.
     */
    public function getCustomerID() {
        return $this->customerID;
    }


    /**
     * Set the CustomerID of the Order.
     * 
     * @param int $customerID The CustomerID.
     */
    public function setCustomerID($customerID) {
        $this->customerID = $customerID;
    }


    /**
     * Get the TotalAmount of the Order.
     * 
     * @return float The TotalAmount.
     */
    public function getTotalAmount() {
        return $this->totalAmount;
    }


    /**
     * Set the TotalAmount of the Order.
     * 
     * @param float $totalAmount The TotalAmount.
     */
    public function setTotalAmount($totalAmount) {
        $this->totalAmount = $totalAmount;
    }


    /**
     * Get the OrderStatusID of the Order.
     * 
     * @return int The OrderStatusID.
     */
    public function getOrderStatusID() {
        return $this->orderStatusID;
    }


    /**
     * Set the OrderStatusID of the Order.
     * 
     * @param int $orderStatusID The OrderStatusID.
     */
    public function setOrderStatusID($orderStatusID) {
        $this->orderStatusID = $orderStatusID;
    }

    /**
     * Get the OrderStatusName of the Order.
     * 
     * @return string The OrderStatusName.
     */
    public function getOrderStatusName() {
        return $this->orderStatusName;
    }

    /**
     * Set the OrderStatusName of the Order.
     * 
     * @param string The OrderStatusName.
     */
    public function setOrderStatusName($orderStatusName) {
        $this->orderStatusName = $orderStatusName;
    }


    /**
     * Get the OrderLines of the Order.
     * 
     * @return array The OrderLines.
     */
    public function getOrderLines() {
        return $this->orderLines;
    }


    /**
     * Add an OrderLine to the Order.
     * 
     * @param OrderLine $orderLine The OrderLine to add.
     */
    public function addOrderLine($orderLine) {
        $this->orderLines[] = $orderLine;
    }


    /**
     * Remove an OrderLine from the Order.
     * 
     * @param int $productID The ProductID of the OrderLine to remove.
     */
    public function removeOrderLine($productID) {
        foreach ($this->orderLines as $key => $orderLine) {
            if ($orderLine->getProductID() == $productID) {
                unset($this->orderLines[$key]);
                break;
            }
        }
    }

}
?>
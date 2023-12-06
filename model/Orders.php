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
        $insertQuery = "INSERT INTO 'Orders' (
                        'CustomerID',
                        'TotalAmount',
                        'OrderStatusID'
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
        $insertQuery = "INSERT INTO 'OrderLines' (
                        'OrderID',
                        'ProductID',
                        'Quantity',
                    ) VALUES (
                        :orderID,
                        :productID,
                        :quantity,
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
}
?>
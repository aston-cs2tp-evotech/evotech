<?php
    /**
     * TODO List
     * 
     * - updateOrder (for any changes to the order like status changes)
     * - deleteOrder 
     * - createOrderLine (for when an item is added to the cart)
     * - updateOrderLine (for when the item quantity is changed)
     * - deleteOrderLine (for when an item is removed from the cart)
     * 
     */
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
        $statement->bindParam(':orderStatusID', $this->getOrderStatusIDByName($orderStatusName), PDO::PARAM_INT);
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

     
}
?>
<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . "/../model/Orders.php";

class OrdersTest extends TestCase {
    
    public function testConstructor() {
        $database = $this->createMock(PDO::class);
        $order = new OrdersModel($database);
        $this->assertInstanceOf(OrdersModel::class, $order);
    }

    public function testGetAllOrdersWhenOrders() {
        $testData = array(
            [
                "OrderID" => 1,
                "CustomerID" => 1,
                "TotalAmount" => 100.00,
                "OrderStatusID" => 1,
                "CheckedOut" => "2024-01-01 00:10:00",
                "CreatedAt" => "2024-01-01 00:00:00",
                "UpdatedAt" => "2024-01-01 00:01:00"
            ],[
                "OrderID" => 2,
                "CustomerID" => 2,
                "TotalAmount" => 200.00,
                "OrderStatusID" => 2,
                "CheckedOut" => "2024-01-01 00:20:00",
                "CreatedAt" => "2024-01-01 00:10:00",
                "UpdatedAt" => "2024-01-01 00:11:00"
            ]
        );

        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                            ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(true);
        $statement->method('fetchAll')->willReturn($testData);

        // Mock PDO object
        $pdo = $this -> getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock the OrdersModel class
        $ordersModel = new OrdersModel($pdo);

        // Call the method to test
        $orders = $ordersModel->getAllOrders();

        // Assert that the returned customer data matches the expected data
        $this->assertEquals($testData, $orders, "The returned orders data does not match the expected data");
    }

    public function testGetAllOrdersWhenNoOrders() {
        $testData = array();

        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                            ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(true);
        $statement->method('fetchAll')->willReturn($testData);

        // Mock PDO object
        $pdo = $this -> getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock the OrdersModel class
        $ordersModel = new OrdersModel($pdo);

        // Call the method to test
        $orders = $ordersModel->getAllOrders();

        // Assert that the returned customer data matches the expected data
        $this->assertNull($orders, "The returned orders data does not match the expected data");
    }

    public function testGetOrderByIdWhenOrderExists() {
        $testData = [
                "OrderID" => 1,
                "CustomerID" => 1,
                "TotalAmount" => 100.00,
                "OrderStatusID" => 1,
                "CheckedOut" => "2024-01-01 00:10:00",
                "CreatedAt" => "2024-01-01 00:00:00",
                "UpdatedAt" => "2024-01-01 00:01:00"
        ];

        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                            ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(true);
        $statement->method('fetch')->willReturn($testData);

        // Mock PDO object
        $pdo = $this -> getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock the OrdersModel class
        $ordersModel = new OrdersModel($pdo);

        // Call the method to test
        $order = $ordersModel->getOrderById(1);

        // Assert that the returned customer data matches the expected data
        $this->assertEquals($testData, $order, "The returned order data does not match the expected data");
    }

    public function testGetOrderByIdWhenOrderDoesNotExist() {
        $testData = null;

        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                            ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(true);
        $statement->method('fetch')->willReturn($testData);

        // Mock PDO object
        $pdo = $this -> getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock the OrdersModel class
        $ordersModel = new OrdersModel($pdo);

        // Call the method to test
        $order = $ordersModel->getOrderById(1);

        // Assert that the returned customer data matches the expected data
        $this->assertNull($order, "The returned order data does not match the expected data");
    }

    public function testGetAllOrdersByCustomerIDValidIDWithOrders() {
        $testData = array(
            [
                "OrderID" => 1,
                "CustomerID" => 1,
                "TotalAmount" => 100.00,
                "OrderStatusID" => 1,
                "CheckedOut" => "2024-01-01 00:10:00",
                "CreatedAt" => "2024-01-01 00:00:00",
                "UpdatedAt" => "2024-01-01 00:01:00"
            ],[
                "OrderID" => 2,
                "CustomerID" => 1,
                "TotalAmount" => 200.00,
                "OrderStatusID" => 2,
                "CheckedOut" => "2024-01-01 00:20:00",
                "CreatedAt" => "2024-01-01 00:10:00",
                "UpdatedAt" => "2024-01-01 00:11:00"
            ]
        );

        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                            ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(true);
        $statement->method('fetchAll')->willReturn($testData);

        // Mock PDO object
        $pdo = $this -> getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock the OrdersModel class
        $ordersModel = new OrdersModel($pdo);

        // Call the method to test
        $orders = $ordersModel->getAllOrdersByCustomerID(1);

        // Assert that the returned customer data matches the expected data
        $this->assertEquals($testData, $orders, "The returned orders data does not match the expected data");
    }

    public function testGetAllOrdersByCustomerIDValidIDWithNoOrders() {
        $testData = array();

        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                            ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(true);
        $statement->method('fetchAll')->willReturn($testData);

        // Mock PDO object
        $pdo = $this -> getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock the OrdersModel class
        $ordersModel = new OrdersModel($pdo);

        // Call the method to test
        $orders = $ordersModel->getAllOrdersByCustomerID(1);

        // Assert that the returned customer data matches the expected data
        $this->assertNull($orders, "The returned orders data does not match the expected data");
    }

    public function testGetAllOrdersByCustomerIDInvalidID() {
        $testData = [];

        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                            ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(true);
        $statement->method('fetchAll')->willReturn($testData);

        // Mock PDO object
        $pdo = $this -> getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock the OrdersModel class
        $ordersModel = new OrdersModel($pdo);

        // Call the method to test
        $orders = $ordersModel->getAllOrdersByCustomerID(1);

        // Assert that the returned customer data matches the expected data
        $this->assertNull($orders, "The returned orders data does not match the expected data");
    }

    public function testGetOrderStatusIDByNameValidName() {
        $testData = [
            "OrderStatusID" => 1,
            "OrderStatusName" => "basket"
        ];

        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                            ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(true);
        $statement->method('fetch')->willReturn($testData);

        // Mock PDO object
        $pdo = $this -> getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock the OrdersModel class
        $ordersModel = new OrdersModel($pdo);

        // Call the method to test
        $orderStatusID = $ordersModel->getOrderStatusIDByName("basket");

        // Assert that the returned customer data matches the expected data
        $this->assertEquals($testData["OrderStatusID"], $orderStatusID, "The returned order status ID does not match the expected data");
    }

    public function testGetOrderStatusIDByNameInvalidName() {
        $testData = null;

        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                            ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(true);
        $statement->method('fetch')->willReturn($testData);

        // Mock PDO object
        $pdo = $this -> getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock the OrdersModel class
        $ordersModel = new OrdersModel($pdo);

        // Call the method to test
        $orderStatusID = $ordersModel->getOrderStatusIDByName("invalidStatusName");

        // Assert that the returned customer data matches the expected data
        $this->assertNull($orderStatusID, "The returned order status ID does not match the expected data");
    }

    public function testGetAllOrdersByOrderStatusNameAndCustomerIDValidNameAndIDWithOrders() {
        $testData = array(
            [
                "OrderID" => 1,
                "CustomerID" => 1,
                "TotalAmount" => 100.00,
                "OrderStatusID" => 1,
                "CheckedOut" => "2024-01-01 00:10:00",
                "CreatedAt" => "2024-01-01 00:00:00",
                "UpdatedAt" => "2024-01-01 00:01:00"
            ],[
                "OrderID" => 2,
                "CustomerID" => 1,
                "TotalAmount" => 200.00,
                "OrderStatusID" => 1,
                "CheckedOut" => "2024-01-01 00:20:00",
                "CreatedAt" => "2024-01-01 00:10:00",
                "UpdatedAt" => "2024-01-01 00:11:00"
            ]
        );

        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                            ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(true);
        $statement->method('fetchAll')->willReturn($testData);

        // Mock PDO object
        $pdo = $this -> getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock the OrdersModel class
        $ordersModel = new OrdersModel($pdo);

        // Call the method to test
        $orders = $ordersModel->getAllOrdersByOrderStatusNameAndCustomerID("basket", 1);

        // Assert that the returned customer data matches the expected data
        $this->assertEquals($testData, $orders, "The returned orders data does not match the expected data");
    }

    public function testGetAllOrdersByOrderStatusNameAndCustomerIDValidNameAndIDWithNoOrders() {
        $testData = array();

        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                            ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(true);
        $statement->method('fetchAll')->willReturn($testData);

        // Mock PDO object
        $pdo = $this -> getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock the OrdersModel class
        $ordersModel = new OrdersModel($pdo);

        // Call the method to test
        $orders = $ordersModel->getAllOrdersByOrderStatusNameAndCustomerID("basket", 1);

        // Assert that the returned customer data matches the expected data
        $this->assertNull($orders, "The returned orders data does not match the expected data");
    }

    public function testGetAllOrdersByOrderStatusNameAndCustomerIDInvalidNameAndID() {
        $testData = [];

        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                            ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(true);
        $statement->method('fetchAll')->willReturn($testData);

        // Mock PDO object
        $pdo = $this -> getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock the OrdersModel class
        $ordersModel = new OrdersModel($pdo);

        // Call the method to test
        $orders = $ordersModel->getAllOrdersByOrderStatusNameAndCustomerID("invalidStatusName", 1);

        // Assert that the returned customer data matches the expected data
        $this->assertNull($orders, "The returned orders data does not match the expected data");
    }

    public function testGetAllOrderLinesByOrderIDValidIDWithOrderLines() {
        $testData = array(
            [
                "OrderLineID" => 1,
                "OrderID" => 1,
                "ProductID" => 1,
                "Quantity" => 1,
                "Price" => 100.00,
                "CreatedAt" => "2024-01-01 00:00:00",
                "UpdatedAt" => "2024-01-01 00:01:00"
            ],[
                "OrderLineID" => 2,
                "OrderID" => 1,
                "ProductID" => 2,
                "Quantity" => 2,
                "Price" => 200.00,
                "CreatedAt" => "2024-01-01 00:10:00",
                "UpdatedAt" => "2024-01-01 00:11:00"
            ]
        );

        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                            ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(true);
        $statement->method('fetchAll')->willReturn($testData);

        // Mock PDO object
        $pdo = $this -> getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock the OrdersModel class
        $ordersModel = new OrdersModel($pdo);

        // Call the method to test
        $orderLines = $ordersModel->getAllOrderLinesByOrderID(1);

        // Assert that the returned customer data matches the expected data
        $this->assertEquals($testData, $orderLines, "The returned order lines data does not match the expected data");
    }

    public function testGetAllOrderLinesByOrderIDValidIDWithNoOrderLines() {
        $testData = array();

        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                            ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(true);
        $statement->method('fetchAll')->willReturn($testData);

        // Mock PDO object
        $pdo = $this -> getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock the OrdersModel class
        $ordersModel = new OrdersModel($pdo);

        // Call the method to test
        $orderLines = $ordersModel->getAllOrderLinesByOrderID(1);

        // Assert that the returned customer data matches the expected data
        $this->assertNull($orderLines, "The returned order lines data does not match the expected data");
    }

    public function testGetAllOrderLinesByOrderIDInvalidID() {
        $testData = [];

        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                            ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(true);
        $statement->method('fetchAll')->willReturn($testData);

        // Mock PDO object
        $pdo = $this -> getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock the OrdersModel class
        $ordersModel = new OrdersModel($pdo);

        // Call the method to test
        $orderLines = $ordersModel->getAllOrderLinesByOrderID(1);

        // Assert that the returned customer data matches the expected data
        $this->assertNull($orderLines, "The returned order lines data does not match the expected data");
    }

    public function testCreateOrderValidData() {
        $testData = [
            "CustomerID" => 1,
            "TotalAmount" => 100.00,
            "OrderStatusID" => 1,
        ];
    
        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                           ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(true);
        $statement->method('fetch')->willReturn(1);
    
        // Mock PDO object
        $pdo = $this->getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('lastInsertId')->willReturn('1'); 
        $pdo->method('prepare')->willReturn($statement);
    
        // Mock the OrdersModel class
        $ordersModel = new OrdersModel($pdo);
    
        // Call the method to test
        $orderID = $ordersModel->createOrder($testData);
    
        // Assert that the returned customer data matches the expected data
        $this->assertEquals('1', $orderID, "The returned order ID does not match the expected data");
    }


    public function testCreateOrderInvalidData() {
        // Test data with missing or invalid keys
        $invalidTestData = [
            // Missing "CustomerID" key
            [
                "TotalAmount" => 100.00,
                "OrderStatusID" => 1,
            ],
            // Missing "TotalAmount" key
            [
                "CustomerID" => 1,
                "OrderStatusID" => 1,
            ],
            // Missing "OrderStatusID" key
            [
                "CustomerID" => 1,
                "TotalAmount" => 100.00,
            ],
            // Invalid data type for "CustomerID"
            [
                "CustomerID" => "invalid", // Should be an integer
                "TotalAmount" => 100.00,
                "OrderStatusID" => 1,
            ],
            // Invalid data type for "TotalAmount"
            [
                "CustomerID" => 1,
                "TotalAmount" => "invalid", // Should be a float
                "OrderStatusID" => 1,
            ],
            // Invalid data type for "OrderStatusID"
            [
                "CustomerID" => 1,
                "TotalAmount" => 100.00,
                "OrderStatusID" => "invalid", // Should be an integer
            ],
        ];

        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                        ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(false); // Simulate execution failure

        // Mock PDO object
        $pdo = $this->getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock the OrdersModel class
        $ordersModel = new OrdersModel($pdo);

        foreach ($invalidTestData as $data) {
            // Call the method to test with invalid data
            $orderID = $ordersModel->createOrder($data);

            // Assert that the method returns null for invalid data
            $this->assertNull($orderID, "The method should return null for invalid data");
        }
    }

    public function testUpdateOrderDetailsValidData() {
        $testData = [
            [
                "Field" => "CustomerID",
                "Value" => 2
            ],
            [
                "Field" => "TotalAmount",
                "Value" => 200.00
            ],
            [
                "Field" => "OrderStatusID",
                "Value" => 2
            ]
        ];

        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                           ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(true);
        $statement->method('fetch')->willReturn(true);

        // Mock PDO object
        $pdo = $this->getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock the OrdersModel class
        $ordersModel = new OrdersModel($pdo);

        foreach ($testData as $data) {
            // Call the method to test
            $result = $ordersModel->updateOrderDetails(1, $data["Field"], $data["Value"]);

            // Assert that the returned customer data matches the expected data
            $this->assertTrue($result, "The returned result does not match the expected data");
        }
    }
    

    public function testUpdateOrderDetailsInvalidData() {
        // Test data with missing or invalid keys
        $invalidTestData = [
            // Invalid field name
            [
                "Field" => "InvalidField",
                "Value" => 2
            ],

            
        ];
    
        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                        ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(true); // Simulate successful execution
    
        // Mock PDO object
        $pdo = $this->getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);
    
        // Mock the OrdersModel class
        $ordersModel = new OrdersModel($pdo);
    
        foreach ($invalidTestData as $data) {
            // Call the method to test with invalid data
            $result = $ordersModel->updateOrderDetails(1, $data["Field"], $data["Value"]);
    
            // Assert that the method returns null for invalid data
            $this->assertFalse($result, "The method should return false for invalid data");
        }
    }

    public function testUpdateOrderDetailsInvalidID() {
        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                        ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(false); // Simulate execution failure

        // Mock PDO object
        $pdo = $this->getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock the OrdersModel class
        $ordersModel = new OrdersModel($pdo);

        // Call the method to test
        $result = $ordersModel->updateOrderDetails(1, "CustomerID", 2);

        // Assert that the returned customer data matches the expected data
        $this->assertFalse($result, "The returned result does not match the expected data");
    }
    

    public function testDeleteOrderValidID() {
        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                        ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(true);

        // Mock PDO object
        $pdo = $this->getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock the OrdersModel class
        $ordersModel = new OrdersModel($pdo);

        // Call the method to test
        $result = $ordersModel->deleteOrder(1);

        // Assert that the returned customer data matches the expected data
        $this->assertTrue($result, "The returned result does not match the expected data");
    }

    public function testDeleteOrderInvalidID() {
        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                        ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(false); // Simulate execution failure

        // Mock PDO object
        $pdo = $this->getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock the OrdersModel class
        $ordersModel = new OrdersModel($pdo);

        // Call the method to test
        $result = $ordersModel->deleteOrder(1);

        // Assert that the returned customer data matches the expected data
        $this->assertFalse($result, "The returned result does not match the expected data");
    }

    public function testCreateOrderLineValidData() {
        $testData = [
            "OrderID" => 1,
            "ProductID" => 1,
            "Quantity" => 1,
        ];
    
        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                           ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(true);
        $statement->method('fetch')->willReturn(1);
    
        // Mock PDO object
        $pdo = $this->getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('lastInsertId')->willReturn('1'); 
        $pdo->method('prepare')->willReturn($statement);
    
        // Mock the OrdersModel class
        $ordersModel = new OrdersModel($pdo);
    
        // Call the method to test
        $orderLineID = $ordersModel->createOrderLine($testData);
    
        // Assert that the returned customer data matches the expected data
        $this->assertEquals('1', $orderLineID, "The returned order line ID does not match the expected data");
    }

    public function testCreateOrderLineInvalidData() {
        // Test data with missing or invalid keys
        $invalidTestData = [
            // Missing "OrderID" key
            [
                "ProductID" => 1,
                "Quantity" => 1,
            ],
            // Missing "ProductID" key
            [
                "OrderID" => 1,
                "Quantity" => 1,
            ],
            // Missing "Quantity" key
            [
                "OrderID" => 1,
                "ProductID" => 1,
            ],
            // Invalid data type for "OrderID"
            [
                "OrderID" => "invalid", // Should be an integer
                "ProductID" => 1,
                "Quantity" => 1,
            ],
            // Invalid data type for "ProductID"
            [
                "OrderID" => 1,
                "ProductID" => "invalid", // Should be an integer
                "Quantity" => 1,
            ],
            // Invalid data type for "Quantity"
            [
                "OrderID" => 1,
                "ProductID" => 1,
                "Quantity" => "invalid", // Should be an integer
            ],
        ];

        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                        ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(false); // Simulate execution failure

        // Mock PDO object
        $pdo = $this->getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock the OrdersModel class
        $ordersModel = new OrdersModel($pdo);

        foreach ($invalidTestData as $data) {
            // Call the method to test with invalid data
            $orderLineID = $ordersModel->createOrderLine($data);

            // Assert that the method returns null for invalid data
            $this->assertNull($orderLineID, "The method should return null for invalid data");
        }
    }

    public function testUpdateOrderLineDetailsValidData() {
        $testData = [
            [
                "Field" => "Quantity",
                "Value" => 2
            ]
        ];

        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                           ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(true);
        $statement->method('fetch')->willReturn(true);

        // Mock PDO object
        $pdo = $this->getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock the OrdersModel class
        $ordersModel = new OrdersModel($pdo);

        foreach ($testData as $data) {
            // Call the method to test
            $result = $ordersModel->updateOrderLineDetails(1, 1, $data["Field"], $data["Value"]);

            // Assert that the returned customer data matches the expected data
            $this->assertTrue($result, "The returned result does not match the expected data");
        }
    }

    public function testUpdateOrderLineDetailsInvalidData() {
        // Test data with missing or invalid keys
        $invalidTestData = [
            // Invalid field name
            [
                "Field" => "InvalidField",
                "Value" => 2
            ],
        ];
    
        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                        ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(true); // Simulate successful execution
    
        // Mock PDO object
        $pdo = $this->getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);
    
        // Mock the OrdersModel class
        $ordersModel = new OrdersModel($pdo);
    
        foreach ($invalidTestData as $data) {
            // Call the method to test with invalid data
            $result = $ordersModel->updateOrderLineDetails(1, 1, $data["Field"], $data["Value"]);
    
            // Assert that the method returns null for invalid data
            $this->assertFalse($result, "The method should return false for invalid data");
        }
    }

    public function testUpdateOrderLineDetailsInvalidID() {
        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                        ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(false); // Simulate execution failure
    
        // Mock PDO object
        $pdo = $this->getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);
    
        // Mock the OrdersModel class
        $ordersModel = new OrdersModel($pdo);
    
        // Call the method to test
        $result = $ordersModel->updateOrderLineDetails(1, 1, "Quantity", 2);
    
        // Assert that the returned customer data matches the expected data
        $this->assertFalse($result, "The returned result does not match the expected data");
    }

    public function testGetAllOrderStausesWhenOrderStatuses() {
        $testData = array(
            [
                "OrderStatusID" => 1,
                "OrderStatusName" => "basket"
            ],[
                "OrderStatusID" => 2,
                "OrderStatusName" => "ready"
            ]
        );
        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                            ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(true);
        $statement->method('fetchAll')->willReturn($testData);
    
        // Mock PDO object
        $pdo = $this->getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);
    
        // Mock the OrdersModel class
        $ordersModel = new OrdersModel($pdo);
    
        // Call the method to test
        $orderStatuses = $ordersModel->getAllOrderStatuses();
    
        // Assert that the returned order statuses data matches the expected data
        $this->assertIsArray($orderStatuses, "The returned order statuses should be an array");
        $this->assertEquals($testData, $orderStatuses, "The returned order statuses data does not match the expected data");
        $this->assertNotEmpty($orderStatuses, "The returned order statuses array should not be empty");
    }
    

    public function testGetAllOrderStausesWhenNoOrderStatuses() {
        $testData = array();
    
        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                            ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(true);
        $statement->method('fetchAll')->willReturn($testData);
    
        // Mock PDO object
        $pdo = $this->getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);
    
        // Mock the OrdersModel class
        $ordersModel = new OrdersModel($pdo);
    
        // Call the method to test
        $orderStatuses = $ordersModel->getAllOrderStatuses();
    
        // Assert that the returned order statuses data matches the expected data
        $this->assertEquals(null, $orderStatuses, "The returned order statuses data does not match the expected data");
        $this->assertEmpty($orderStatuses, "The returned order statuses array should be empty");
    }

    public function testGetOrderCountPass() {
        // Test data
        $testData = ['count' => 5]; // Mock the count value in an associative array
    
        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                          ->getMock();
        $statement->method('execute')->willReturn(true);
        $statement->method('fetch')->willReturn($testData);
    
        // Mock PDO object
        $pdo = $this->getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);
    
        // Mock the OrdersModel class
        $ordersModel = new OrdersModel($pdo);
    
        // Call the method to test
        $orderCount = $ordersModel->getOrderCount();
    
        // Assert that the returned order count matches the expected data
        $this->assertEquals($testData['count'], $orderCount, "The returned order count does not match the expected data");
    }

    public function testGetOrderCountFail() {
        // Test data
        $testData = ['count' => 0]; // Mock the count value in an associative array
    
        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                          ->getMock();
        $statement->method('execute')->willReturn(false); // Simulate execution failure
        $statement->method('fetch')->willReturn($testData);
    
        // Mock PDO object
        $pdo = $this->getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);
    
        // Mock the OrdersModel class
        $ordersModel = new OrdersModel($pdo);
    
        // Call the method to test
        $orderCount = $ordersModel->getOrderCount();
    
        // Assert that the returned order count matches the expected data
        $this->assertEquals(0, $orderCount, "The returned order count does not match the expected data");
    }
    
    public function testOrderLineClass() {

        $testDetails = [
            "ProductID" => 1,
            "ProductName" => "Test Product",
            "Quantity" => 1,
            "TotalStock" => 10,
            "UnitPrice" => 100.00,
            "MainImage" => "test.jpg",
            "OtherImages" => ["test1.jpg", "test2.jpg"],
        ];

        // Create a new OrderLine object
        $orderLine = new OrderLine($testDetails);


        // Test getters
        $this->assertEquals($testDetails["ProductID"], $orderLine->getProductID(), "The ProductID getter does not return the expected value");
        $this->assertEquals($testDetails["ProductName"], $orderLine->getProductName(), "The ProductName getter does not return the expected value");
        $this->assertEquals($testDetails["Quantity"], $orderLine->getQuantity(), "The Quantity getter does not return the expected value");
        $this->assertEquals($testDetails["TotalStock"], $orderLine->getTotalStock(), "The TotalStock getter does not return the expected value");
        $this->assertEquals($testDetails["UnitPrice"], $orderLine->getUnitPrice(), "The UnitPrice getter does not return the expected value");
        $this->assertEquals($testDetails["UnitPrice"] * $testDetails["Quantity"], $orderLine->getTotalPrice(), "The TotalPrice getter does not return the expected value");
        $this->assertEquals($testDetails["MainImage"], $orderLine->getMainImage(), "The MainImage getter does not return the expected value");
        $this->assertEquals($testDetails["OtherImages"], $orderLine->getOtherImages(), "The OtherImages getter does not return the expected value");

        // Test setters
        $newProductID = 2;
        $orderLine->setProductID($newProductID);
        $this->assertEquals($newProductID, $orderLine->getProductID(), "The ProductID setter does not set the expected value");

        $newProductName = "New Product";
        $orderLine->setProductName($newProductName);
        $this->assertEquals($newProductName, $orderLine->getProductName(), "The ProductName setter does not set the expected value");

        $newQuantity = 2;
        $orderLine->setQuantity($newQuantity);
        $this->assertEquals($newQuantity, $orderLine->getQuantity(), "The Quantity setter does not set the expected value");

        $newTotalStock = 20;
        $orderLine->setTotalStock($newTotalStock);
        $this->assertEquals($newTotalStock, $orderLine->getTotalStock(), "The TotalStock setter does not set the expected value");

        $newUnitPrice = 200.00;
        $orderLine->setUnitPrice($newUnitPrice);
        $this->assertEquals($newUnitPrice, $orderLine->getUnitPrice(), "The UnitPrice setter does not set the expected value");

        $newMainImage = "new.jpg";
        $orderLine->setMainImage($newMainImage);
        $this->assertEquals($newMainImage, $orderLine->getMainImage(), "The MainImage setter does not set the expected value");

        $newOtherImages = ["new1.jpg", "new2.jpg"];
        $orderLine->setOtherImages($newOtherImages);
        $this->assertEquals($newOtherImages, $orderLine->getOtherImages(), "The OtherImages setter does not set the expected value");

        // Test the getTotalPrice method
        $this->assertEquals($newUnitPrice * $newQuantity, $orderLine->getTotalPrice(), "The getTotalPrice method does not return the expected value");
    }
}

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

    
}

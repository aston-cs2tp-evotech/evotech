<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . "/../model/Customer.php";

class CustomerTest extends TestCase {

    public function testConstructor() {
        $database = $this->createMock(PDO::class);
        $customer = new CustomerModel($database);
        $this->assertInstanceOf(CustomerModel::class, $customer);
    }

    public function testGetCustomerByUID() {
        // Test data
        $testData = [
            'CustomerID' => 1, 
            'Email' => 'test@example.com',
            'Username' => 'testuser',
            'CustomerAddress' => '123 Test St',
            'PasswordHash' => 'testhash',
            'CreatedAt' => '2024-01-01 00:00:00',
            'UpdatedAt' => '2024-01-01 00:01:00'
        ];
        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                          ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(true);
        $statement->method('fetch')->willReturn($testData);

        // Mock PDO object
        $pdo = $this->getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock the CustomerModel with the mock PDO object
        $customerModel = new CustomerModel($pdo);

        // Call the method to test
        $customer = $customerModel->getCustomerByUID(1);

        // Assert that the returned customer data matches the expected data
        $this->assertEquals($testData, $customer);
    }

    public function testGetCustomerByUsername() {
        // Test data
        $testData = [
            'CustomerID' => 1, 
            'Email' => 'test@example.com',
            'Username' => 'testuser',
            'CustomerAddress' => '123 Test St',
            'PasswordHash' => 'testhash',
            'CreatedAt' => '2024-01-01 00:00:00',
            'UpdatedAt' => '2024-01-01 00:01:00'
        ];

        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                          ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(true);
        $statement->method('fetch')->willReturn($testData);

        // Mock PDO object
        $pdo = $this->getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock the CustomerModel with the mock PDO object
        $customerModel = new CustomerModel($pdo);

        // Call the method to test
        $customer = $customerModel->getCustomerByUsername('testuser');

        // Assert that the returned customer data matches the expected data
        $this->assertEquals($testData, $customer, 'Customer data does not match expected data');
    }

    public function testGetCustomerByEmail() {
        // Test data
        $testData = [
            'CustomerID' => 1, 
            'Email' => 'test@example.com',
            'Username' => 'testuser',
            'CustomerAddress' => '123 Test St',
            'PasswordHash' => 'testhash',
            'CreatedAt' => '2024-01-01 00:00:00',
            'UpdatedAt' => '2024-01-01 00:01:00'
        ];

        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                          ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(true);
        $statement->method('fetch')->willReturn($testData);

        // Mock PDO object
        $pdo = $this->getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock the CustomerModel with the mock PDO object
        $customerModel = new CustomerModel($pdo);

        // Call the method to test
        $customer = $customerModel->getCustomerByEmail('test@example.com');

        // Assert that the returned customer data matches the expected data
        $this->assertEquals($testData, $customer, 'Customer data does not match expected data');
    }

    public function testRegisterCustomer() {
        // Test data
        $testData = [
            'CustomerID' => 1, 
            'Email' => 'test@example.com',
            'Username' => 'testuser',
            'CustomerAddress' => '123 Test St',
            'PasswordHash' => 'testhash',
            'CreatedAt' => '2024-01-01 00:00:00',
            'UpdatedAt' => '2024-01-01 00:01:00'
        ];

        $testInput = [
            'Email' => $testData['Email'],
            'Username' => $testData['Username'],
            'CustomerAddress' => $testData['CustomerAddress'],
            'PasswordHash' => $testData['PasswordHash']
        ];

        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                          ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(true);
        $statement->method('fetch')->willReturn($testData);

        // Mock PDO object
        $pdo = $this->getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock the CustomerModel with the mock PDO object
        $customerModel = new CustomerModel($pdo);

        // Call the method to test
        $customer = $customerModel->registerCustomer($testInput);

        // Assert that the returned customer data matches the expected data
        $this->assertEquals($testData, $customer, 'Customer data does not match expected data');
    }

    public function testUpdateCustomerDetail() {
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

        // Mock the CustomerModel with the mock PDO object
        $customerModel = new CustomerModel($pdo);

        // Call the method to test
        $result = $customerModel->updateCustomerDetail(1, 'Email', 'newemail@example.com');

        // Assert that the update is successful
        $this->assertTrue($result, 'Update failed');
    }

    public function testGetCustomerCount() {
        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                          ->getMock();
        $statement->method('execute')->willReturn(true);
        $statement->method('fetchColumn')->willReturn(3); // Mock the returned count value

        // Mock PDO object
        $pdo = $this->getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock the CustomerModel with the mock PDO object
        $customerModel = new CustomerModel($pdo);

        // Call the method to test
        $count = $customerModel->getCustomerCount();

        // Assert that the returned count matches the expected count
        $this->assertEquals(3, $count);
    }

    public function testGetAllCustomers() {
        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                          ->getMock();
        $statement->method('execute')->willReturn(true);
        $statement->method('fetchAll')->willReturn([
            ['CustomerID' => 1, 'Email' => 'test1@example.com'],
            ['CustomerID' => 2, 'Email' => 'test2@example.com'],
            ['CustomerID' => 3, 'Email' => 'test3@example.com']
        ]); // Mock the returned array of customers

        // Mock PDO object
        $pdo = $this->getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock the CustomerModel with the mock PDO object
        $customerModel = new CustomerModel($pdo);

        // Call the method to test
        $customers = $customerModel->getAllCustomers();

        // Assert that the returned array of customers matches the expected array
        $this->assertEquals([
            ['CustomerID' => 1, 'Email' => 'test1@example.com'],
            ['CustomerID' => 2, 'Email' => 'test2@example.com'],
            ['CustomerID' => 3, 'Email' => 'test3@example.com']
        ], $customers);
    }

    public function testDeleteCustomer() {
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

        // Mock the CustomerModel with the mock PDO object
        $customerModel = new CustomerModel($pdo);

        // Call the method to test
        $result = $customerModel->deleteCustomer(1);

        // Assert that the deletion is successful
        $this->assertTrue($result);
    }

}
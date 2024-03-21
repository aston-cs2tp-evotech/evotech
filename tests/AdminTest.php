<?php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../model/Admin.php';

class AdminTest extends TestCase {

    public function testAdminModelConstructor(){
        $database = $this->createMock(PDO::class);
        $admin = new AdminModel($database);
        $this->assertInstanceOf(AdminModel::class, $admin);
    }

    public function testGetAdminByUIDValidUID(){
        $testData = [
            "AdminID" => 1,
            "Username" => "admin",
            "PasswordHash" => "hashedpassword",
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

        // Mock AdminModel with the mock PDO object
        $adminModel = new AdminModel($pdo);

        // Call method to test
        $admin = $adminModel->getAdminByUID(1);

        // Assert that the returned data matches the test data
        $this->assertEquals($testData, $admin, "The admin data does not match the test data");
    }

    public function testGetAdminByUIDInvalidUID(){

        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                     ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(true);
        $statement->method('fetch')->willReturn(false);

        // Mock PDO object
        $pdo = $this->getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock AdminModel with the mock PDO object
        $adminModel = new AdminModel($pdo);

        // Call method to test
        $admin = $adminModel->getAdminByUID(2);

        // Assert that the returned data is null
        $this->assertNull($admin, "The admin data should be null");
    }

    public function testGetAdminByUsernameValid(){
        $testData = [
            "AdminID" => 1,
            "Username" => "admin",
            "PasswordHash" => "hashedpassword",
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

        // Mock AdminModel with the mock PDO object
        $adminModel = new AdminModel($pdo);

        // Call method to test
        $admin = $adminModel->getAdminByUsername("admin");

        // Assert that the returned data matches the test data
        $this->assertEquals($testData, $admin, "The admin data does not match the test data");
    }

    public function testGetAdminByUsernameInvalid(){
        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                     ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(true);
        $statement->method('fetch')->willReturn(false);

        // Mock PDO object
        $pdo = $this->getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock AdminModel with the mock PDO object
        $adminModel = new AdminModel($pdo);

        // Call method to test
        $admin = $adminModel->getAdminByUsername("admin");

        // Assert that the returned data is null
        $this->assertNull($admin, "The admin data should be null");
    }

    public function testAddAdminRegistrationSuccess() {
        $testData = [
            "Username" => "admin",
            "PasswordHash" => "hashedpassword",
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

        // Mock AdminModel with the mock PDO object
        $adminModel = new AdminModel($pdo);

        // Call method to test
        $admin = $adminModel->addAdmin($testData);

        // Assert that the returned data is true
        $this->assertEquals($testData, $admin, "The admin data does not match the test data");
    }

    public function testAddAdminRegistrationFailure() {
        $testData = [
            "Username" => "admin",
            "PasswordHash" => "hashedpassword",
        ];

        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                     ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(false);
        $statement->method('fetch')->willReturn(false);

        // Mock PDO object
        $pdo = $this->getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock AdminModel with the mock PDO object
        $adminModel = new AdminModel($pdo);

        // Call method to test
        $admin = $adminModel->addAdmin($testData);

        // Assert that the returned data is false
        $this->assertNull($admin, "The admin data should be null");
    }

    public function testUpdateAdminValidFields(){
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

        // Mock AdminModel with the mock PDO object
        $adminModel = new AdminModel($pdo);

        // Call method to test
        $result = $adminModel->updateAdmin(1, "Username", "admin");
        $this->assertTrue($result, "The result should be true when updating a valid field");

        $result = $adminModel->updateAdmin(1, "PasswordHash", "hashedpassword");
        $this->assertTrue($result, "The result should be true when updating a valid field");
    }

    public function testUpdateAdminInvalidFields(){
        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                     ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(false);
        $statement->method('fetch')->willReturn(false);

        // Mock PDO object
        $pdo = $this->getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock AdminModel with the mock PDO object
        $adminModel = new AdminModel($pdo);

        // Call method to test
        $result = $adminModel->updateAdmin(1, "InvalidField", "admin");
        $this->assertFalse($result, "The result should be false when updating an invalid field");
    }

    public function testGetAllAdminsWhenNoAdmins() {
        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                     ->getMock();
        $statement->method('execute')->willReturn(true);
        $statement->method('fetchAll')->willReturn([]);

        // Mock PDO object
        $pdo = $this->getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock AdminModel with the mock PDO object
        $adminModel = new AdminModel($pdo);

        // Call method to test
        $result = $adminModel->getAllAdmins();

        // Assert that the returned data is null
        $this->assertNull($result, "The result should be null when there are no admins");
    }

    public function testGetAllAdminsWhenAdminsExist() {
        $testData = [
            [
                "AdminID" => 1,
                "Username" => "admin",
                "PasswordHash" => "hashedpassword",
            ],
            [
                "AdminID" => 2,
                "Username" => "admin2",
                "PasswordHash" => "hashedpassword2",
            ]
        ];

        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                     ->getMock();
        $statement->method('execute')->willReturn(true);
        $statement->method('fetchAll')->willReturn($testData);

        // Mock PDO object
        $pdo = $this->getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock AdminModel with the mock PDO object
        $adminModel = new AdminModel($pdo);

        // Call method to test
        $result = $adminModel->getAllAdmins();

        // Assert that the returned data matches the test data
        $this->assertEquals($testData, $result, "The admin data does not match the test data");
    }

    public function testGettersAndSetters(){
        $adminData = [
            "AdminID" => 1,
            "Username" => "admin",
            "PasswordHash" => "hashedpassword",
            "CreatedAt" => "2024-01-01 00:00:00",
            "UpdatedAt" => "2024-01-01 00:01:00"
        ];

        $admin = new Admin($adminData);

        // Test Getters
        $this->assertEquals($admin->getUID(), $adminData["AdminID"], "AdminID failed");
        $this->assertEquals($admin->getUsername(), $adminData["Username"], "Username failed");
        $this->assertEquals($admin->getPasswordHash(), $adminData["PasswordHash"], "PasswordHash failed");
        $this->assertEquals($admin->getCreatedAt(), $adminData["CreatedAt"], "CreatedAt failed");
        $this->assertEquals($admin->getUpdatedAt(), $adminData["UpdatedAt"], "UpdatedAt failed");

        // Test Setters
        $admin->setUID(2);
        $this->assertEquals($admin->getUID(), 2, "AdminID failed");

        $admin->setUsername("admin2");
        $this->assertEquals($admin->getUsername(), "admin2", "Username failed");

        $admin->setPasswordHash("hashedpassword2");
        $this->assertEquals($admin->getPasswordHash(), "hashedpassword2", "PasswordHash failed");

        $admin->setCreatedAt("2024-01-01 00:00:01");
        $this->assertEquals($admin->getCreatedAt(), "2024-01-01 00:00:01", "CreatedAt failed");

        $admin->setUpdatedAt("2024-01-01 00:01:01");
        $this->assertEquals($admin->getUpdatedAt(), "2024-01-01 00:01:01", "UpdatedAt failed");
    }
}
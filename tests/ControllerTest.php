<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . "/../model/Admin.php";
require_once __DIR__ . "/../model/Customer.php";
require_once __DIR__ . "/../model/Orders.php";
require_once __DIR__ . "/../model/Products.php";
require_once __DIR__ . "/../controller/Controller.php";

class ControllerTest extends TestCase
{
    public function testCheckExists(){
        // Test when a variable is set
        $this->assertTrue(CheckExists("test"));
        // Test when a variable is not set
        $this->assertFalse(CheckExists(null));
        // Test when a variable is set but empty
        $this->assertFalse(CheckExists(""));
    }

    public function testescapeHTML(){
        // Test when a string is passed
        $value = "<script>alert('test');</script>";
        $shouldBe = "&lt;script&gt;alert(&#039;test&#039;);&lt;/script&gt;";
        escapeHTML($value);
        $this->assertEquals($value, $shouldBe, "escapeHTML failed");
        // Test when a string is not passed

        $value = 1;
        $shouldBe = 1;
        escapeHTML($value);
        $this->assertEquals($value, $shouldBe, "escapeHTML failed");
    }

    public function testCreateSafeCustomer(){
        // Test when a valid customer is passed
        $details = array(
            "CustomerID" => 1,
            "Email" => "john@gmail.com",
            "Username" => "john",
            "CustomerAddress" => "123 Fake Street",
            "PasswordHash" => "hashedpassword",
            "CreatedAt" => "2024-01-01 00:00:00",
            "UpdatedAt" => "2024-01-01 00:01:00"
        );

        $customer = CreateSafeCustomer($details);
        $this->assertEquals($customer->getUID(), $details["CustomerID"], "CustomerID failed");
        $this->assertEquals($customer->getEmail(), $details["Email"], "Email failed");
        $this->assertEquals($customer->getUsername(), $details["Username"], "Username failed");
        $this->assertEquals($customer->getAddress(), $details["CustomerAddress"], "CustomerAddress failed");
        $this->assertEquals($customer->getPasswordHash(), $details["PasswordHash"], "PasswordHash failed");
        $this->assertEquals($customer->getCreatedAt(), $details["CreatedAt"], "CreatedAt failed");
        $this->assertEquals($customer->getUpdatedAt(), $details["UpdatedAt"], "UpdatedAt failed");


        // Test when an invalid customer is passed
        $details["Invalid Key"] = "Invalid Value";
        $customer = CreateSafeCustomer($details);
        $this->assertNull($customer, "Invalid Key failed");
    }

    public function testCreateSafeAdmin(){
        // Test when a valid admin is passed
        $details = array(
            "AdminID" => 1,
            "Username" => "john",
            "PasswordHash" => "hashedpassword",
            "CreatedAt" => "2024-01-01 00:00:00",
            "UpdatedAt" => "2024-01-01 00:01:00"
        );

        $admin = CreateSafeAdmin($details);
        $this->assertEquals($admin->getUID(), $details["AdminID"], "AdminID failed");
        $this->assertEquals($admin->getUsername(), $details["Username"], "Username failed");
        $this->assertEquals($admin->getPasswordHash(), $details["PasswordHash"], "PasswordHash failed");
        $this->assertEquals($admin->getCreatedAt(), $details["CreatedAt"], "CreatedAt failed");
        $this->assertEquals($admin->getUpdatedAt(), $details["UpdatedAt"], "UpdatedAt failed");

        // Test when an invalid admin is passed
        $details["Invalid Key"] = "Invalid Value";
        $admin = CreateSafeAdmin($details);
        $this->assertNull($admin, "Invalid Key failed");
    }

    public function testReLogInUser() {
        // Mocking the required global variables
        global $userInfo, $Customer;
        $userInfo = null; // Reset userInfo
        
        // Mocking the session
        $_SESSION["uid"] = 123; // Example UID
        
        // Mocking the Customer object
        $customerData = [
            "CustomerID" => 123,
            "Email" => "test@example.com",
            "Username" => "test_user",
            "CustomerAddress" => "123 Street",
            "PasswordHash" => "hashed_password",
            "CreatedAt" => "2024-01-01 00:00:00",
            "UpdatedAt" => "2024-01-01 00:00:00"
        ];
        $mockCustomer = new Customer($customerData);
        
        // Mocking the CustomerModel
        $customerModelMock = $this->getMockBuilder(CustomerModel::class)
                                  ->disableOriginalConstructor()
                                  ->getMock();
        
        // Mocking the result of getCustomerByUID method
        $customerModelMock->expects($this->once())
                          ->method('getCustomerByUID')
                          ->with($this->equalTo($_SESSION["uid"]))
                          ->willReturn($customerData);
        
        // Replace global $Customer with the mock object
        $Customer = $customerModelMock;
        
        // Call the function to test
        ReLogInUser();
        
        // Cast $userInfo to Customer object
        $userInfo = (object) $userInfo;
        
        // Assertions
        $this->assertInstanceOf(Customer::class, $userInfo, "userInfo should be an instance of Customer");
        $this->assertEquals($mockCustomer->getUID(), $userInfo->getUID(), "UID is not set correctly in userInfo");
        $this->assertEquals($mockCustomer->getEmail(), $userInfo->getEmail(), "Email is not set correctly in userInfo");
        $this->assertEquals($mockCustomer->getUsername(), $userInfo->getUsername(), "Username is not set correctly in userInfo");
        $this->assertEquals($mockCustomer->getAddress(), $userInfo->getAddress(), "Address is not set correctly in userInfo");
        $this->assertEquals($mockCustomer->getPasswordHash(), $userInfo->getPasswordHash(), "PasswordHash is not set correctly in userInfo");
        $this->assertEquals($mockCustomer->getCreatedAt(), $userInfo->getCreatedAt(), "CreatedAt is not set correctly in userInfo");
        $this->assertEquals($mockCustomer->getUpdatedAt(), $userInfo->getUpdatedAt(), "UpdatedAt is not set correctly in userInfo");
    }

    public function testCheckLoggedInWhenBothSet() {
        // Mocking the required global variable
        global $userInfo;
        
        // Setting up the scenario where both $_SESSION["uid"] and $userInfo are set
        $_SESSION["uid"] = 123; // Example UID
        $userInfo = new Customer([
            "CustomerID" => 123,
            "Email" => "test@example.com",
            "Username" => "test_user",
            "CustomerAddress" => "123 Street",
            "PasswordHash" => "hashed_password",
            "CreatedAt" => "2024-01-01 00:00:00",
            "UpdatedAt" => "2024-01-01 00:00:00"
        ]);
        
        // Asserting that CheckLoggedIn returns true
        $this->assertTrue(CheckLoggedIn(), "When both \$_SESSION[\"uid\"] and \$userInfo are set, CheckLoggedIn should return true");
    }

    public function testCheckLoggedInWhenUidSetButUserInfoNotSet() {
        // Mocking the required global variable
        global $userInfo;
        $userInfo = null;
        
        
        // Setting up the scenario where only $_SESSION["uid"] is set
        $_SESSION["uid"] = 123; // Example UID
        
        // Asserting that CheckLoggedIn returns false
        $this->assertFalse(CheckLoggedIn(), "When only \$_SESSION[\"uid\"] is set and \$userInfo is not set, CheckLoggedIn should return false");
    }
    
    public function testCheckLoggedInWhenUserInfoSetButUidNotSet() {
        // Mocking the required global variable
        global $userInfo;
        $userInfo = new Customer([
            "CustomerID" => 123,
            "Email" => "test@example.com",
            "Username" => "test_user",
            "CustomerAddress" => "123 Street",
            "PasswordHash" => "hashed_password",
            "CreatedAt" => "2024-01-01 00:00:00",
            "UpdatedAt" => "2024-01-01 00:00:00"
        ]);
        
        // Setting up the scenario where only $userInfo is set
        unset($_SESSION["uid"]);
        
        // Asserting that CheckLoggedIn returns false
        $this->assertFalse(CheckLoggedIn(), "When only \$userInfo is set and \$_SESSION[\"uid\"] is not set, CheckLoggedIn should return false");
    }

    public function testCheckLoggedInWhenNeitherSet() {
        // Mocking the required global variable
        global $userInfo;
        $userInfo = null;
        
        // Setting up the scenario where neither $_SESSION["uid"] nor $userInfo is set
        unset($_SESSION["uid"]);
        
        // Asserting that CheckLoggedIn returns false
        $this->assertFalse(CheckLoggedIn(), "When neither \$_SESSION[\"uid\"] nor \$userInfo is set, CheckLoggedIn should return false");
    }    
}
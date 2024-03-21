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

}
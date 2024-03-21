<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . "/../model/Products.php";

class ProductsTest extends TestCase {

    public function testConstructor() {
        $database = $this->createMock(PDO::class);
        $product = new ProductModel($database);
        $this->assertInstanceOf(ProductModel::class, $product);
    }

    public function testGetProductByValidID() {
        $testData = [
            "ProductID" => 1,
            "Name" => "Test Product",
            "Price" => 10.00,
            "Stock" => 10,
            "Description" => "Test Description",
            "CategoryID" => 1
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

        // Mock the ProductModel with the mock PDO object
        $productModel = new ProductModel($pdo);

        // Call the method to test
        $product = $productModel->getProductByID(1);

        // Assert that the returned customer data matches the expected data
        $this->assertEquals($testData, $product, "The product data does not match the expected data");
    }

    public function testGetProductByInvalidID() {
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

        // Mock the ProductModel with the mock PDO object
        $productModel = new ProductModel($pdo);

        // Call the method to test
        $product = $productModel->getProductByID(1);

        // Assert that the returned customer data matches the expected data
        $this->assertNull($product, "The product data should be null for an invalid ID");
    }

    public function testGetProductByValidName() {
        $testData = [
            "ProductID" => 1,
            "Name" => "Test Product",
            "Price" => 10.00,
            "Stock" => 10,
            "Description" => "Test Description",
            "CategoryID" => 1
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

        // Mock the ProductModel with the mock PDO object
        $productModel = new ProductModel($pdo);

        // Call the method to test
        $product = $productModel->getProductByName("Test Product");

        // Assert that the returned customer data matches the expected data
        $this->assertEquals($testData, $product, "The product data does not match the expected data");
        
    }

    public function testGetProductByInvalidName() {
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

        // Mock the ProductModel with the mock PDO object
        $productModel = new ProductModel($pdo);

        // Call the method to test
        $product = $productModel->getProductByName("Test Product");

        // Assert that the returned customer data matches the expected data
        $this->assertNull($product, "The product data should be null for an invalid name");
    }

    public function testGetAllProductsWhenMultipleProducts() {
        $testData = array(
            [
                "ProductID" => 1,
                "Name" => "Test Product",
                "Price" => 10.00,
                "Stock" => 10,
                "Description" => "Test Description",
                "CategoryID" => 1
            ],
            [
                "ProductID" => 2,
                "Name" => "Test Product 2",
                "Price" => 20.00,
                "Stock" => 20,
                "Description" => "Test Description 2",
                "CategoryID" => 2
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

        // Mock the ProductModel with the mock PDO object
        $productModel = new ProductModel($pdo);

        // Call the method to test
        $products = $productModel->getAllProducts();

        // Assert that the returned customer data matches the expected data
        $this->assertEquals($testData, $products, "The products data does not match the expected data");
    }

    public function testGetAllProductsWhenNoProducts() {
        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                          ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(true);
        $statement->method('fetchAll')->willReturn([]);
    
        // Mock PDO object
        $pdo = $this->getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock the ProductModel with the mock PDO object
        $productModel = new ProductModel($pdo);

        // Call the method to test
        $products = $productModel->getAllProducts();

        // Assert that the returned customer data matches the expected data
        $this->assertNull($products, "The products data should be null for no products");
    }

    public function testGetAllProductsWhenOneProduct() {
        $testData = [
            "ProductID" => 1,
            "Name" => "Test Product",
            "Price" => 10.00,
            "Stock" => 10,
            "Description" => "Test Description",
            "CategoryID" => 1
        ];

        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                          ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(true);
        $statement->method('fetchAll')->willReturn([$testData]);

        // Mock PDO object
        $pdo = $this->getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock the ProductModel with the mock PDO object
        $productModel = new ProductModel($pdo);

        // Call the method to test
        $products = $productModel->getAllProducts();

        // Assert that the returned customer data matches the expected data
        $this->assertEquals([$testData], $products, "The products data does not match the expected data");
    }

    public function testAddFunctionValidData() {
        $testData = [
            "Name" => "Test Product",
            "Price" => 10.00,
            "Stock" => 10,
            "Description" => "Test Description",
            "CategoryID" => 1
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

        // Mock the ProductModel with the mock PDO object
        $productModel = new ProductModel($pdo);

        // Call the method to test
        $product = $productModel->addProduct($testData);

        // Assert that the returned customer data matches the expected data
        $this->assertEquals($testData, $product, "The product data does not match the expected data");
    }

    public function testAddProductInvalidData() {
        $testData = [
            "Price" => 10.00,
            "Stock" => 10,
            "Description" => "Test Description",
            "CategoryID" => 1
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

        // Mock the ProductModel with the mock PDO object
        $productModel = new ProductModel($pdo);

        // Call the method to test
        $product = $productModel->addProduct($testData);

        // Assert that the returned customer data matches the expected data
        $this->assertNull($product, "The product data should be null for invalid data");
    }

    public function testUpdateProductDetailValidDetail() {

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

        // Mock the ProductModel with the mock PDO object
        $productModel = new ProductModel($pdo);

        // Call the method to test
        $result = $productModel->updateProductDetail(1, 'Name', 'Test Product');

        // Assert that the returned customer data matches the expected data
        $this->assertTrue($result, "Updating product detail should return true for valid data");

        $result = $productModel->updateProductDetail(1, 'Price', 10.00);
        $this->assertTrue($result, "Updating product detail should return true for valid data");

        $result = $productModel->updateProductDetail(1, 'Stock', 10);
        $this->assertTrue($result, "Updating product detail should return true for valid data");

        $result = $productModel->updateProductDetail(1, 'Description', 'Test Description');
        $this->assertTrue($result, "Updating product detail should return true for valid data");

        $result = $productModel->updateProductDetail(1, 'CategoryID', 3);
        $this->assertTrue($result, "Updating product detail should return true for valid data");
    }

    public function testUpdateProductDetailInvalidDetail() {

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

        // Mock the ProductModel with the mock PDO object
        $productModel = new ProductModel($pdo);

        // Call the method to test
        $result = $productModel->updateProductDetail(1, 'ProductName', 'Test Product');

        // Assert that the returned customer data matches the expected data
        $this->assertFalse($result, "Updating product detail should return false for invalid data");
    }
    
    public function testDeleteProductValidID() {

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

        // Mock the ProductModel with the mock PDO object
        $productModel = new ProductModel($pdo);

        // Call the method to test
        $result = $productModel->deleteProduct(1);

        // Assert that the returned customer data matches the expected data
        $this->assertTrue($result, "Deleting product should return true for valid ID");
    }

    public function testDeleteProductInvalidID() {

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

        // Mock the ProductModel with the mock PDO object
        $productModel = new ProductModel($pdo);

        // Call the method to test
        $result = $productModel->deleteProduct(1);

        // Assert that the returned customer data matches the expected data
        $this->assertFalse($result, "Deleting product should return false for invalid ID");
    }

    public function testGetProductImagesWhenExists() {
        $testData = [
            "ImageID" => 1,
            "ProductID" => 1,
            "FileName" => "test.jpg",
            "MainImage" => 1
        ];

        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                          ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(true);
        $statement->method('fetchAll')->willReturn([$testData]);

        // Mock PDO object
        $pdo = $this->getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock the ProductModel with the mock PDO object
        $productModel = new ProductModel($pdo);

        // Call the method to test
        $images = $productModel->getProductImages(1);

        // Assert that the returned customer data matches the expected data
        $this->assertEquals([$testData], $images, "The images data does not match the expected data");
    }

    public function testGetProductImagesWhenNotExists() {
        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                          ->getMock();
        $statement->method('bindParam')->willReturn(true);
        $statement->method('execute')->willReturn(true);
        $statement->method('fetchAll')->willReturn([]);

        // Mock PDO object
        $pdo = $this->getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock the ProductModel with the mock PDO object
        $productModel = new ProductModel($pdo);

        // Call the method to test
        $images = $productModel->getProductImages(1);

        // Assert that the returned customer data matches the expected data
        $this->assertNull($images, "The images data should be null for no images");
    }

    public function testUpdateProductImageValidField() {

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

        // Mock the ProductModel with the mock PDO object
        $productModel = new ProductModel($pdo);

        // Call the method to test
        $result = $productModel->updateProductImage(1, 'test.jpg', 'ProductID', 2);
        $this->assertTrue($result, "Updating product image should return true for valid data");

        $result = $productModel->updateProductImage(1, 'test.jpg', 'FileName', 'test2.jpg');
        $this->assertTrue($result, "Updating product image should return true for valid data");

        $result = $productModel->updateProductImage(1, 'test.jpg', 'MainImage', 0);
        $this->assertTrue($result, "Updating product image should return true for valid data");
    }

    public function testUpdateProductImageInvalidField() {

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

        // Mock the ProductModel with the mock PDO object
        $productModel = new ProductModel($pdo);

        // Call the method to test
        $result = $productModel->updateProductImage(1, 'test.jpg', 'ProdcutID', 2);
        $this->assertFalse($result, "Updating product image should return false for invalid data");
    }

    public function testDeleteProductImageValidID() {

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

        // Mock the ProductModel with the mock PDO object
        $productModel = new ProductModel($pdo);

        // Call the method to test
        $result = $productModel->deleteProductImage(1, 'test.jpg');

        // Assert that the returned customer data matches the expected data
        $this->assertTrue($result, "Deleting product image should return true for valid ID");
    }

    public function testDeleteProductImageInvalidID() {

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

        // Mock the ProductModel with the mock PDO object
        $productModel = new ProductModel($pdo);

        // Call the method to test
        $result = $productModel->deleteProductImage(1, 'test.jpg');

        // Assert that the returned customer data matches the expected data
        $this->assertFalse($result, "Deleting product image should return false for invalid ID");
    }

    public function testGetCategoriesWhenExists() {
        $testData = [
            "CategoryID" => 1,
            "CategoryName" => "Test Category"
        ];

        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                          ->getMock();
        $statement->method('execute')->willReturn(true);
        $statement->method('fetchAll')->willReturn([$testData]);

        // Mock PDO object
        $pdo = $this->getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock the ProductModel with the mock PDO object
        $productModel = new ProductModel($pdo);

        // Call the method to test
        $categories = $productModel->getCategories();

        // Assert that the returned customer data matches the expected data
        $this->assertEquals([$testData], $categories, "The categories data does not match the expected data");
    }

    public function testGetCategoriesWhenNotExists() {
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

        // Mock the ProductModel with the mock PDO object
        $productModel = new ProductModel($pdo);

        // Call the method to test
        $categories = $productModel->getCategories();

        // Assert that the returned customer data matches the expected data
        $this->assertEmpty($categories, "The categories data should be empty for no categories");
    }

    public function testGetProductCountWithProducts() {
        $testData = 5;

        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                          ->getMock();
        $statement->method('execute')->willReturn(true);
        $statement->method('fetchColumn')->willReturn($testData);

        // Mock PDO object
        $pdo = $this->getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock the ProductModel with the mock PDO object
        $productModel = new ProductModel($pdo);

        // Call the method to test
        $count = $productModel->getProductCount();

        // Assert that the returned customer data matches the expected data
        $this->assertEquals($testData, $count, "The product count does not match the expected data");
    }

    public function testGetProductCountWithoutProducts() {
        $testData = 0;

        // Mock PDO statement
        $statement = $this->getMockBuilder(PDOStatement::class)
                          ->getMock();
        $statement->method('execute')->willReturn(true);
        $statement->method('fetchColumn')->willReturn(null);

        // Mock PDO object
        $pdo = $this->getMockBuilder(PDO::class)
                    ->disableOriginalConstructor()
                    ->getMock();
        $pdo->method('prepare')->willReturn($statement);

        // Mock the ProductModel with the mock PDO object
        $productModel = new ProductModel($pdo);

        // Call the method to test
        $count = $productModel->getProductCount();

        // Assert that the returned customer data matches the expected data
        $this->assertNull($count, "The product count should be null for no products");
    }

    function testGettersAndSetters() {
        $testData = [
            "ProductID" => 1,
            "Name" => "Test Product",
            "Price" => 10.00,
            "Stock" => 10,
            "Description" => "Test Description",
            "CategoryID" => 1,
            "CategoryName" => "Test Category",
            "MainImage" => "test.jpg",
            "OtherImages" => ["test2.jpg", "test3.jpg"],
            "CreatedAt" => "2024-01-01 00:00:00",
            "UpdatedAt" => "2024-01-01 00:01:00"
        ];

        $product = new Product($testData);
        $product->setProductID($testData["ProductID"]);
        $product->setName($testData["Name"]);
        $product->setPrice($testData["Price"]);
        $product->setStock($testData["Stock"]);
        $product->setDescription($testData["Description"]);
        $product->setCategoryID($testData["CategoryID"]);
        $product->setCategoryName($testData["CategoryName"]);
        $product->setMainImage($testData["MainImage"]);
        $product->setOtherImages($testData["OtherImages"]);
        $product->setCreatedAt($testData["CreatedAt"]);
        $product->setUpdatedAt($testData["UpdatedAt"]);

        $this->assertEquals($testData["ProductID"], $product->getProductID(), "Product ID does not match");
        $this->assertEquals($testData["Name"], $product->getName(), "Name does not match");
        $this->assertEquals($testData["Price"], $product->getPrice(), "Price does not match");
        $this->assertEquals($testData["Stock"], $product->getStock(), "Stock does not match");
        $this->assertEquals($testData["Description"], $product->getDescription(), "Description does not match");
        $this->assertEquals($testData["CategoryID"], $product->getCategoryID(), "Category ID does not match");
        $this->assertEquals($testData["CategoryName"], $product->getCategoryName(), "Category Name does not match");
        $this->assertEquals($testData["MainImage"], $product->getMainImage(), "Main Image does not match");
        $this->assertEquals($testData["OtherImages"], $product->getOtherImages(), "Other Images does not match");
        $this->assertEquals($testData["CreatedAt"], $product->getCreatedAt(), "Created At does not match");
        $this->assertEquals($testData["UpdatedAt"], $product->getUpdatedAt(), "Updated At does not match");
    }

}
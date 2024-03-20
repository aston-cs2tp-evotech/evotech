-- Set SQL mode and start transaction
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Table for Order Status
-- The `OrderStatusID` is a unique identifier for each order status.
-- The `Name` field stores the descriptive name of the order status (e.g., Processing, Shipped).
-- This table is used to track and manage different order statuses.
-- --------------------------------------------------------
CREATE TABLE if not exists `OrderStatus` (
  `OrderStatusID` INT NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(200) NOT NULL,
  `CreatedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`OrderStatusID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

INSERT INTO `OrderStatus` (`Name`) VALUES
('basket'),
('ready'),
('processing'),
('delivering'),
('delivered'),
('cancelled'),
('returned');

-- --------------------------------------------------------
-- Table for Compatibility
-- The `CompatibilityID` is a unique identifier for each compatibility type.
-- The `CompatibilityName` field stores a descriptive name or identifier for each compatibility type (e.g., USB, PCIe).
-- This table is used to define various types of compatibility for products.
-- --------------------------------------------------------
CREATE TABLE if not exists `Compatibility` (
  `CompatibilityID` INT NOT NULL AUTO_INCREMENT,
  `CompatibilityName` VARCHAR(99) NOT NULL,
  `CreatedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`CompatibilityID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------
-- Table for Administrator Credentials
-- The `AdminID` is a unique identifier for each administrator.
-- The `Username` field stores the username of the administrator.
-- The `PasswordHash` field stores the hashed password using PHP's password_hash() function.
-- This table is used to manage administrator credentials.
-- --------------------------------------------------------
CREATE TABLE if not exists `AdminCredentials` (
  `AdminID` INT NOT NULL AUTO_INCREMENT,
  `Username` VARCHAR(200) NOT NULL,
  `PasswordHash` VARCHAR(255) NOT NULL,
  `CreatedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`AdminID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------
-- Table for Categories
-- The `CategoryID` is a unique identifier for each category.
-- The `CategoryName` field stores the name of the category.
-- This table is used to define and categorize products.
-- --------------------------------------------------------
CREATE TABLE if not exists `Categories` (
  `CategoryID` INT NOT NULL AUTO_INCREMENT,
  `CategoryName` VARCHAR(200) NOT NULL,
  `CreatedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`CategoryID`),
  UNIQUE KEY `CategoryName` (`CategoryName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------
-- Table for Products
-- The `ProductID` is a unique identifier for each product.
-- The `Name` field stores the name of the product.
-- The `Price` field represents the price of the product, and `Stock` represents the stock quantity.
-- The 'Category' field represents the category of the product (e.g., Motherboard, CPU).
-- The 'Description' field stores a description of the product.
-- This table is used to store detailed information about the products available in the system.
-- --------------------------------------------------------
CREATE TABLE if not exists `Products` (
  `ProductID` INT NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(200) NOT NULL,
  `Price` DECIMAL(10,2) NOT NULL,
  `Stock` INT NOT NULL,
  `Description` VARCHAR(200) NOT NULL,
  `CategoryID` INT NOT NULL,
  `CreatedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ProductID`),
  UNIQUE KEY `Name` (`Name`),
  FOREIGN KEY (`CategoryID`) REFERENCES `Categories` (`CategoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------
-- Table for Product Compatibility
-- The `ProductID` and `CompatibilityID` form a composite primary key,
-- representing the association between a product and a compatibility type.
-- `SlotType` represents the type of slot compatibility (e.g., USB, PCIe).
-- This table establishes compatibility relationships between products based on slot types.
-- --------------------------------------------------------
CREATE TABLE if not exists `ProductCompatibility` (
  `ProductID` INT NOT NULL,
  `CompatibilityID` INT NOT NULL,
  `SlotType` VARCHAR(50) NOT NULL,
  `CreatedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ProductID`, `CompatibilityID`),
  FOREIGN KEY (`ProductID`) REFERENCES `Products` (`ProductID`),
  FOREIGN KEY (`CompatibilityID`) REFERENCES `Compatibility` (`CompatibilityID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------
-- Table for Product Images
-- The `ImageID` is a unique identifier for each image.
-- The `ProductID` references the product to which the image belongs.
-- The `FileName` stores the name of the image file.
-- The 'MainImage' field is a boolean value that indicates whether the image is the main image for the product.
-- This table is used to associate image files with products.
-- --------------------------------------------------------
CREATE TABLE if not exists `ProductImages` (
  `ImageID` INT NOT NULL AUTO_INCREMENT,
  `ProductID` INT NOT NULL,
  `FileName` VARCHAR(255) NOT NULL,
  `MainImage` BOOLEAN NOT NULL,
  `CreatedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ImageID`),
  FOREIGN KEY (`ProductID`) REFERENCES `Products` (`ProductID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------
-- Table for Customers
-- The `CustomerID` is a unique identifier for each customer.
-- The `Email` field stores the email address of the customer.
-- The `Username` field stores the username of the customer, and `CustomerAddress` stores the address.
-- The `PasswordHash` field stores the hashed password using PHP's password_hash() function.
-- This table is used to manage detailed customer information.
-- --------------------------------------------------------
CREATE TABLE if not exists `Customers` (
  `CustomerID` INT NOT NULL AUTO_INCREMENT,
  `Email` VARCHAR(200) NOT NULL,
  `Username` VARCHAR(200) NOT NULL,
  `CustomerAddress` VARCHAR(200) NOT NULL,
  `PasswordHash` VARCHAR(255) NOT NULL,
  `CreatedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`CustomerID`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------
-- Table for Orders
-- The `OrderID` is a unique identifier for each order.
-- The `CustomerID` field references the customer placing the order.
-- `TotalAmount` represents the total amount of the order, and `OrderStatusID` represents the current status of the order.
-- This table is used to track and manage customer orders.
-- --------------------------------------------------------
CREATE TABLE if not exists `Orders` (
  `OrderID` INT NOT NULL AUTO_INCREMENT,
  `CustomerID` INT NOT NULL,
  `TotalAmount` DECIMAL(10,2) NOT NULL,
  `OrderStatusID` INT NOT NULL,
  -- CheckedOut is automatically set to the current timestamp when OrderStatusID is set to 2 (ready)
  `CheckedOutAt` TIMESTAMP NULL DEFAULT NULL,
  `CreatedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`OrderID`),
  FOREIGN KEY (`CustomerID`) REFERENCES `Customers` (`CustomerID`),
  FOREIGN KEY (`OrderStatusID`) REFERENCES `OrderStatus` (`OrderStatusID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

DELIMITER //

CREATE TRIGGER `update_checkedout_at` BEFORE UPDATE ON `Orders`
FOR EACH ROW
BEGIN
  IF NEW.OrderStatusID = 2 THEN
    SET NEW.CheckedOutAt = CURRENT_TIMESTAMP;
  END IF;
END;
//

DELIMITER ;

-- --------------------------------------------------------
-- Table for Order Lines (Items in an Order) with a composite key
-- The `OrderID` and `ProductID` form a composite primary key,
-- representing the association between an order and a product.
-- `Quantity` stores the quantity of the product in the order.
-- This table is used to track the items in each order.
-- --------------------------------------------------------
CREATE TABLE if not exists `OrderLines` (
  `OrderID` INT NOT NULL,
  `ProductID` INT NOT NULL,
  `Quantity` INT NOT NULL,
  `CreatedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`OrderID`, `ProductID`),
  FOREIGN KEY (`OrderID`) REFERENCES `Orders` (`OrderID`),
  FOREIGN KEY (`ProductID`) REFERENCES `Products` (`ProductID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------
-- Table for Product Slots
-- The `ProductID` and `SlotType` form a composite primary key,
-- representing the association between a product and a slot type.
-- This table is used to define and store various slot types associated with products.
-- --------------------------------------------------------
CREATE TABLE if not exists `ProductSlots` (
  `ProductID` INT NOT NULL,
  `SlotType` VARCHAR(50) NOT NULL,
  `CreatedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ProductID`, `SlotType`),
  FOREIGN KEY (`ProductID`) REFERENCES `Products` (`ProductID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


-- --------------------------------------------------------
-- Table for Product Reviews
-- The `ProductID` and `CustomerID` form a composite primary key,
-- representing the association between a product and a customer.
-- The `Rating` field stores the rating given by the customer, and `Review` stores the review text.
-- Rating must be filled in, but review is optional.
CREATE TABLE if not exists `ProductReviews` (
  `ProductID` INT NOT NULL,
  `CustomerID` INT NOT NULL,
  `Rating` INT NOT NULL,
  `Review` VARCHAR(200),
  `CreatedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ProductID`, `CustomerID`),
  FOREIGN KEY (`ProductID`) REFERENCES `Products` (`ProductID`),
  FOREIGN KEY (`CustomerID`) REFERENCES `Customers` (`CustomerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------
-- Table for API Tokens
-- The `Token` is the unique identifier for each token,
-- The `ExpiresAt` field is the timestamp for when it will expire
-- --------------------------------------------------------
CREATE TABLE if not exists `APITokens` (
  `AdminID` INT NOT NULL,
  `Token` VARCHAR(16) NOT NULL,
  `ExpiresAt` TIMESTAMP NULL DEFAULT NULL,
  `TokenName` VARCHAR(200) NOT NULL,
  `CreatedAt` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Token`),
  FOREIGN KEY (`AdminID`) REFERENCES `AdminCredentials` (`AdminID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
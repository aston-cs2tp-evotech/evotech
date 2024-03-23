<?php

// Check all the POST parameters are set

if (!isset($_POST['adminUsername']) || !isset($_POST['adminPassword'])) {
    http_response_code(400);
    echo "Missing parameters.";
    die("Missing parameters.");
}

// Try to connect to the database by loading config/database.php

try {
    require_once __DIR__ . "/../config/database.php";
} catch (Exception $e) {
    http_response_code(500);
    echo "Failed to load database config file.";
    die("Failed to load database config file: " . $e->getMessage());
}

// Check if admin user already exists

$selectQuery = "SELECT * FROM `AdminCredentials` WHERE `Username` = :username";
$selectStatement = $pdo->prepare($selectQuery);
$selectStatement->bindParam(':username', $_POST['adminUsername']);

try {
    $selectStatement->execute();
} catch (PDOException $e) {
    http_response_code(500);
    echo "Failed to check if admin user already exists.";
    die("Failed to check if admin user already exists: " . $e->getMessage());
}

if ($selectStatement->rowCount() > 0) {
    http_response_code(400);
    
    die("Admin user already exists." . $selectStatement->rowCount());
}

// Create Admin user

$insertQuery = "INSERT INTO `AdminCredentials` (
                            `Username`,
                            `PasswordHash`
                        ) VALUES (
                            :username,
                            :passwordHash
                        )"; 
$insertStatement = $pdo->prepare($insertQuery);
$insertStatement->bindParam(':username', $_POST['adminUsername']);
$hashedPassword = password_hash($_POST['adminPassword'], PASSWORD_DEFAULT);
$insertStatement->bindParam(':passwordHash', $hashedPassword);

try {
    $result = $insertStatement->execute();
} catch (PDOException $e) {
    http_response_code(500);
    echo "Failed to create admin user.";
    die("Failed to create admin user: " . $e->getMessage());
}

// Disconnect from the database
$pdo = null;

http_response_code(200);
echo "Admin `" . $_POST['adminUsername'] . "` created successfully.";


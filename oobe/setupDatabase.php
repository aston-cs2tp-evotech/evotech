<?php
function copyFolderStructure($source, $destination) {
    if (!file_exists($destination)) {
        mkdir($destination, 0777, true);
    }

    $dir = opendir($source);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($source . '/' . $file)) {
                copyFolderStructure($source . '/' . $file, $destination . '/' . $file);
            } else {
                copy($source . '/' . $file, $destination . '/' . $file);
            }
        }
    }
    closedir($dir);
}

if (!isset($_POST['dummyData'])) {
    http_response_code(400);
    die("Missing parameters.");
}

// Try load config file

try {
    require_once __DIR__ . "/../config.php";
} catch (Exception $e) {
    http_response_code(500);
    die("Failed to load config file: " . $e->getMessage());
}

// Try to connect to the database

try {
    $pdo = new PDO("mysql:host=" . $db_server . ";dbname=" . $db_database_name, $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    http_response_code(500);
    die("Connection failed: " . $e->getMessage());
}

// Check SQL file exists

if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/setup/evotechDB.sql")) {
    http_response_code(500);
    die("SQL file not found.");
}

// Load SQL file

$sql = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/setup/evotechDB.sql");

// Execute SQL

try {
    $pdo->exec($sql);
} catch (PDOException $e) {
    http_response_code(500);
    die("Failed to create database: " . $e->getMessage());
}

// Check if dummy data is required

if ($_POST['dummyData'] == "true") {
    // Check SQL file exists

    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/setup/dummyProductData.sql")) {
        http_response_code(500);
        die("Dummy data SQL file not found.");
    }

    // Load SQL file

    $sql = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/setup/dummyProductData.sql");

    // Execute SQL

    try {
        $pdo->exec($sql);
    } catch (PDOException $e) {
        http_response_code(500);
        die("Failed to create dummy data: " . $e->getMessage());
    }

    // Copy file and folder structure from /setup/examplePhotos/products/* to /view/images/products/*

    $source = $_SERVER['DOCUMENT_ROOT'] . "/setup/examplePhotos/products";
    $destination = $_SERVER['DOCUMENT_ROOT'] . "/view/images/products";

    copyFolderStructure($source, $destination);
}

// Disconnect from the database
$pdo = null;

// Everything was successful
http_response_code(200);
echo "Database created successfully.";


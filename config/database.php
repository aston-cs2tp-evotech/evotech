<?php
// Include the config.php file
include __DIR__ . '/../config.php';

// PDO database connection
try {
    $pdo = new PDO("mysql:host=$db_server;dbname=$db_database_name", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    // Handle connection errors
    die("Connection failed: " . $e->getMessage());
}
?>

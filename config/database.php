<?php
// Include the config.php file
$configPath = __DIR__ . '/../config.php';
if (file_exists($configPath)) {
    include $configPath;

    // Check if $config is an array before extracting
    if (is_array($config)) {
        extract($config);
    } else {
        die("Configuration is not an array in $configPath");
    }
} else {
    die("Config file not found at $configPath");
}

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

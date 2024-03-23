<?php 

// Check all the POST parameters are set

if (!isset($_POST['dbUsername']) || !isset($_POST['dbPassword']) || !isset($_POST['dbName']) || !isset($_POST['dbHost'])) {
    http_response_code(400);
    echo "Missing parameters.";
    die("Missing parameters.");
}

// Try to connect to the database, if database does not exist, create it

try {
    // Connect to MySQL as usual
    $pdo = new PDO("mysql:host=" . $_POST['dbHost'], $_POST['dbUsername'], $_POST['dbPassword']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    // Check if the database exists
    $dbName = $_POST['dbName'];
    $result = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbName'");

    // If the database doesn't exist, create it
    if ($result->rowCount() == 0) {
        $pdo->query("CREATE DATABASE $dbName");
    }

    // Now connect to the database
    $pdo = new PDO("mysql:host=" . $_POST['dbHost'] . ";dbname=" . $_POST['dbName'], $_POST['dbUsername'], $_POST['dbPassword']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    http_response_code(500);
    echo "Connection failed: " . $e->getMessage();
    die("Connection failed: " . $e->getMessage());
}

// Disconnect from the database
$pdo = null;

// Create a config.php file with the database connection details
$configFile = fopen(__DIR__ . "/../config.php", "w") or die("Unable to open file!");
fwrite($configFile, "<?php\n");
fwrite($configFile, "// The username for the database connection" . "\n");
fwrite($configFile, "\$db_username = \"" . $_POST['dbUsername'] . "\";" . "\n");
fwrite($configFile, "\n");
fwrite($configFile, "// The password for the database connection" . "\n");
fwrite($configFile, "\$db_password = \"" . $_POST['dbPassword'] . "\";" . "\n");
fwrite($configFile, "\n");
fwrite($configFile, "// The name of the database to connect to" . "\n");
fwrite($configFile, "\$db_database_name = \"" . $_POST['dbName'] . "\";" . "\n");
fwrite($configFile, "\n");
fwrite($configFile, "// The server where the database is hosted" . "\n");
fwrite($configFile, "\$db_server = \"" . $_POST['dbHost'] . "\";" . "\n");
fwrite($configFile, "?>");

http_response_code(200);
echo "Database connection successful.";

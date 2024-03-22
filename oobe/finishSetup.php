<?php

// Verify config.php exists
if (!file_exists(__DIR__ . "/../config.php")) {
    http_response_code(500);
    die("Config file not found. Can't finish setup.");
}

// Write /config/oobeHasRun file
$oobeHasRunFile = fopen($_SERVER['DOCUMENT_ROOT'] . "/config/oobeHasRun", "w") or die("Unable to open file!");
fwrite($oobeHasRunFile, "true");
fclose($oobeHasRunFile);

http_response_code(200);
echo "Setup complete";

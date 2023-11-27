<#
    This script is used to copy setup/example_config.php to config.php and to prompt 
    the user for the database connection information.

#>

# Halts the script if an error occurs
$ErrorActionPreference = "Stop"

# Change the working directory to the parent directory of this scripts directory
Write-Host "Changing the working directory to the parent directory of this scripts directory"
Set-Location $PSScriptRoot/..

# Delete the config file if it exists
if (Test-Path config.php) {
    Write-Host "Deleting the config file"
    Remove-Item config.php
}

# Copy the example config file to the config file
Write-Host "Copying the example config file to the config file"
Copy-Item setup/example_config.php config.php

# Prompt the user for the database username
$db_username = Read-Host "Enter the database username (Default: your_username)"

# Set the default database username if the user did not enter one
if ($db_username -eq "") {
    $db_username = "your_username"
}

# Get password and confirm password from the user, using secure input, loop until they match
do {
    $db_password = Read-Host "Enter the database password (Default: your_password)" -AsSecureString
    $db_password_confirm = Read-Host "Confirm the database password (Default: your_password)" -AsSecureString

    # Convert the secure strings to plain text
    $db_password = [Runtime.InteropServices.Marshal]::PtrToStringAuto([Runtime.InteropServices.Marshal]::SecureStringToBSTR($db_password))
    $db_password_confirm = [Runtime.InteropServices.Marshal]::PtrToStringAuto([Runtime.InteropServices.Marshal]::SecureStringToBSTR($db_password_confirm))

    # Prompt when passwords don't match
    if ($db_password -ne $db_password_confirm) {
        Write-Host "Passwords do not match. Please try again."
    }

} while ($db_password -ne $db_password_confirm)

# Prompt the user for the database name
$db_database_name = Read-Host "Enter the database name (Default: your_database_name)"

# Set the default database name if the user did not enter one

if ($db_database_name -eq "") {
    $db_database_name = "your_database_name"
}

# Prompt the user for the database server
$db_server = Read-Host "Enter the database server (Default: localhost)"

# Set the default database server if the user did not enter one
if ($db_server -eq "") {
    $db_server = "localhost"
}

# Replace the default values in the config file with the values the user entered without replacing comment
(Get-Content config.php) | ForEach-Object {$_ -replace '= "your_username"', "= `"$db_username`""} | Set-Content config.php
(Get-Content config.php) | ForEach-Object {$_ -replace '= "your_password"', "= `"$db_password`""} | Set-Content config.php
(Get-Content config.php) | ForEach-Object {$_ -replace '= "your_database_name"', "= `"$db_database_name`""} | Set-Content config.php
(Get-Content config.php) | ForEach-Object {$_ -replace '= "localhost"', "= `"$db_server`""} | Set-Content config.php

# Start the apache and mysql services using the batch files
Write-Host "Starting the apache and mysql services"
Start-Process -FilePath "C:\xampp\apache_start.bat"
Start-Process -FilePath "C:\xampp\mysql_start.bat"

# Launch the browser to the website and phpmyadmin in the default browser
Write-Host "Launching the pages..."
Start-Process -FilePath "http://localhost/"
Start-Process -FilePath "http://localhost/phpmyadmin"

Write-Host "Setup complete!"

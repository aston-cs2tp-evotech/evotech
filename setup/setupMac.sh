#!/bin/bash

# Halts the script if an error occurs
set -e

# Change the working directory to the parent directory of this script's directory
echo "Changing the working directory to the parent directory of this script's directory"
cd "$(dirname "$0")/.."

# Delete the config file if it exists
if [ -e "config.php" ]; then
    echo "Deleting the config file"
    rm config.php
fi

# Copy the example config file to the config file
echo "Copying the example config file to the config file"
cp setup/example_config.php config.php

# Prompt the user for the database username
read -p "Enter the database username (Default: your_username): " db_username
db_username=${db_username:-your_username}

# Prompt the user for the database password
read -s -p "Enter the database password (Default: your_password): " db_password
echo
read -s -p "Confirm the database password (Default: your_password): " db_password_confirm
echo

# Loop until passwords match
while [ "$db_password" != "$db_password_confirm" ]; do
    echo "Passwords do not match. Please try again."
    read -s -p "Enter the database password: " db_password
    echo
    read -s -p "Confirm the database password: " db_password_confirm
    echo
done

# Prompt the user for the database name
read -p "Enter the database name (Default: your_database_name): " db_database_name
db_database_name=${db_database_name:-your_database_name}

# Prompt the user for the database server
read -p "Enter the database server (Default: localhost): " db_server
db_server=${db_server:-localhost}

# Replace the default values in the config file with the user-entered values without replacing comments
sed -i'' -e "s/= 'your_username'/= '$db_username'/" config.php
sed -i'' -e "s/= 'your_password'/= '$db_password'/" config.php
sed -i'' -e "s/= 'your_database_name'/= '$db_database_name'/" config.php
sed -i'' -e "s/= 'localhost'/= '$db_server'/" config.php

# Start the apache and mysql services using XAMPP bin scripts
echo "Starting the apache and mysql services"
/Applications/XAMPP/xamppfiles/xampp startapache
/Applications/XAMPP/xamppfiles/xampp startmysql

# Launch the browser to the website and phpmyadmin in the default browser
echo "Launching the pages..."
open "http://localhost/"
open "http://localhost/phpmyadmin"

echo "Setup complete!"

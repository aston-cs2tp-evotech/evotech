#!/bin/bash

# Halts the script if an error occurs
set -e

# Change the working directory to the parent directory of this script's directory
echo "Changing the working directory to the parent directory of this script's directory"
cd "$(dirname "$0")/.."

# Delete the config file if it exists
if [ -e config.php ]; then
    echo "Deleting the config file"
    rm config.php
fi

# Copy the example config file to the config file
echo "Copying the example config file to the config file"
cp setup/example_config.php config.php

# Prompt the user for the database username
read -p "Enter the database username (Default: root): " db_username

# Set the default database username if the user did not enter one
if [ -z "$db_username" ]; then
    db_username="root"
fi

# Get password and confirm password from the user, using secure input, loop until they match
while true; do
    read -s -p "Enter the database password (Default: none): " db_password
    echo
    read -s -p "Confirm the database password (Default: none): " db_password_confirm
    echo

    # Prompt when passwords don't match
    if [ "$db_password" != "$db_password_confirm" ]; then
        echo "Passwords do not match. Please try again."
    else
        break
    fi
done

# Concatenate username with _db to get default database name
db_database_name="${db_username}_db"

# Prompt the user for the database name
read -p "Enter the database name (Default: $db_database_name): " db_database_name_input

# Set the default database name if the user did not enter one
if [ -z "$db_database_name_input" ]; then
    db_database_name="$db_database_name"
else
    db_database_name="$db_database_name_input"
fi

# Prompt the user for the database server
read -p "Enter the database server (Default: localhost): " db_server

# Set the default database server if the user did not enter one
if [ -z "$db_server" ]; then
    db_server="localhost"
fi

# Replace the default values in the config file with the values the user entered without replacing comments
sed -i'' -e "s/= 'your_username'/= '$db_username'/" config.php
sed -i'' -e "s/= 'your_password'/= '$db_password'/" config.php
sed -i'' -e "s/= 'your_database_name'/= '$db_database_name'/" config.php
sed -i'' -e "s/= 'localhost'/= '$db_server'/" config.php

# Change instances of DocumentRoot and Directory in the apache config file to the current directory
echo "Changing the apache config file to use the current directory"
sudo sed -i'' -e "s@DocumentRoot \"/opt/lampp/htdocs\"@DocumentRoot \"'$PWD'\"@" /opt/lampp/etc/httpd.conf
sudo sed -i'' -e "s@<Directory \"/opt/lampp/htdocs\">@<Directory \"'$PWD'\">@" /opt/lampp/etc/httpd.conf

# Set User and Group in the apache config file to the current user
echo "Setting User and Group in the apache config file to the current user"
sudo sed -i'' -e "s/User daemon/User $USER/" /opt/lampp/etc/httpd.conf

# Start the apache and mysql services using the control scripts
echo "Starting the apache and mysql services"
sudo /opt/lampp/lampp startapache
sudo /opt/lampp/lampp startmysql

# Create the database if it does not exist
echo "Creating the database if it does not exist"
sudo /opt/lampp/bin/mysql -u"$db_username" -p"$db_password" -e "CREATE DATABASE IF NOT EXISTS $db_database_name"

# Launch the browser to the website and phpmyadmin in the default browser
echo "Launching the pages..."
xdg-open "http://localhost/"
xdg-open "http://localhost/phpmyadmin"

echo "Setup complete!"

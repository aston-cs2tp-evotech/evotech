#!/bin/bash

# Halts the script if an error occurs
set -e

# Change the working directory to the parent directory of this script's directory
echo "Changing the working directory to the parent directory of this script's directory"
cd "$(dirname "$0")/.."

# Change instances of DocumentRoot and Directory in the apache config file to the current directory
echo "Changing the apache config file to use the current directory"
sed -i '' -e 's@DocumentRoot "/Applications/XAMPP/xamppfiles/htdocs"@DocumentRoot "'"$PWD"'@' /Applications/XAMPP/xamppfiles/etc/httpd.conf
sed -i '' -e 's@<Directory "/Applications/XAMPP/xamppfiles/htdocs">@<Directory "'"$PWD"'">@' /Applications/XAMPP/xamppfiles/etc/httpd.conf

# Set User in the apache config file to the current user
echo "Setting the apache config file to use the current user"
sed -i '' -e 's/User daemon/User '"$USER"'/' /Applications/XAMPP/xamppfiles/etc/httpd.conf

echo "Setup complete!"

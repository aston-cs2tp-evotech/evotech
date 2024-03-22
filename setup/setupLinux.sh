#!/bin/bash

# Halts the script if an error occurs
set -e

# Change the working directory to the parent directory of this script's directory
echo "Changing the working directory to the parent directory of this script's directory"
cd "$(dirname "$0")/.."


# Change instances of DocumentRoot and Directory in the apache config file to the current directory
echo "Changing the apache config file to use the current directory"
sudo sed -i'' -e "s@DocumentRoot \"/opt/lampp/htdocs\"@DocumentRoot \"'$PWD'\"@" /opt/lampp/etc/httpd.conf
sudo sed -i'' -e "s@<Directory \"/opt/lampp/htdocs\">@<Directory \"'$PWD'\">@" /opt/lampp/etc/httpd.conf

# Set User and Group in the apache config file to the current user
echo "Setting User and Group in the apache config file to the current user"
sudo sed -i'' -e "s/User daemon/User $USER/" /opt/lampp/etc/httpd.conf

echo "Setup complete!"

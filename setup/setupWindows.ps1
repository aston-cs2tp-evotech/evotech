<#
    This script is used to to setup XAMPP and the evotech website on Windows.
#>

# Halts the script if an error occurs
$ErrorActionPreference = "Stop"

# Change the working directory to the parent directory of this scripts directory
Write-Host "Changing the working directory to the parent directory of this scripts directory"
Set-Location $PSScriptRoot/..

# Get the parent directory of the script root
$evotechPath = (Get-Item $PSScriptRoot).Parent.FullName

# Update DocumentRoot and Directory paths in httpd.conf
(Get-Content C:\xampp\apache\conf\httpd.conf) | ForEach-Object {$_ -replace 'DocumentRoot "C:/xampp/htdocs"', "DocumentRoot `"$evotechPath`""} | Set-Content C:\xampp\apache\conf\httpd.conf
(Get-Content C:\xampp\apache\conf\httpd.conf) | ForEach-Object {$_ -replace '<Directory "C:/xampp/htdocs">', "<Directory `"$evotechPath`">"} | Set-Content C:\xampp\apache\conf\httpd.conf

Write-Host "Setup complete!"

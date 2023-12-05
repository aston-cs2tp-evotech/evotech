# evotech
Team Project for the CS2TP module

# Contributing
For information on how to contribute to the project, please see the [CONTRIBUTING.md](CONTRIBUTING.md) file.

# Running locally
These instructions will guide you through setting up the project to run locally on your machine for development and testing purposes.
## Windows

1. Install XAMPP
    ```powershell
    winget install -e --id ApacheFriends.Xampp.8.2
    ```

2. Clone the repository using `git clone` and change directory to the root of the repository
    ```powershell
    cd evotech
    ```

3. Run the setup script for Windows
    ```powershell
    .\setup\setupWindows.ps1
    ```
    - This will also start Apache and MySQL in XAMPP and launch the sites

The script will have configured everything so in the future if you need to run locally again:

1. Start Apache and MySQL in XAMPP

2. Open the following links in your browser:
    - http://localhost/phpmyadmin
    - http://localhost

## macOS

1. Install xcode command line tools
    ```bash
    xcode-select --install
    ```

2. Install brew
    ```bash
    /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh) NONINTERACTIVE=1"
    ```
3. Install XAMPP
    ```bash
    brew install --cask xampp
    ```
    
4. Clone the repository using `git clone` and change directory to the root of the repository
    ```bash
    cd evotech
    ```
5. Run the setup script for macOS
    ```bash
    ./setup/setupMac.sh
    ```
    - This will also start Apache and MySQL in XAMPP and launch the sites

The script will have configured everything so in the future if you need to run locally again:

1. Start Apache and MySQL in XAMPP

2. Open the following links in your browser:
    - http://localhost/phpmyadmin
    - http://localhost

## Linux

1. Install XAMPP
    ```bash
    wget "https://sourceforge.net/projects/xampp/files/XAMPP%20Linux/8.2.12/xampp-linux-x64-8.2.12-0-installer.run"
    chmod +x xampp-linux-x64-8.2.12-0-installer.run
    sudo ./xampp-linux-x64-8.2.12-0-installer.run
    ```

2. Clone the repository using `git clone`

3. Run the setup script for Linux
    ```bash
    ./setup/setupLinux.sh
    ```
    - This will also start Apache and MySQL in XAMPP and launch the sites

The script will have configured everything so in the future if you need to run locally again:

1. Start Apache and MySQL in XAMPP

2. Open the following links in your browser:
    - http://localhost/phpmyadmin
    - http://localhost

# Credits

220064521 - Mohammed Kalam  
220073925 - Viktor Dvornik  
220043504 - Aaron Dosanjh  
220095086 - Thomas Evans  
220216876 - Reece Edwards  
220164359 - Gurjot Dhillon  
220070614 - Hanzalah Naguthane  
220098548 - Mahdi Egal  

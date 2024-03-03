# evotech
![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)
![MariaDB](https://img.shields.io/badge/MariaDB-003545?style=for-the-badge&logo=mariadb&logoColor=white)
![HTML5](https://img.shields.io/badge/html5-%23E34F26.svg?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/css3-%231572B6.svg?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/javascript-%23323330.svg?style=for-the-badge&logo=javascript&logoColor=%23F7DF1E)
![Apache](https://img.shields.io/badge/apache-%23D42029.svg?style=for-the-badge&logo=apache&logoColor=white)

![GitHub commit activity (branch)](https://img.shields.io/github/commit-activity/m/aston-cs2tp-evotech/evotech)
![GitHub contributors](https://img.shields.io/github/contributors/aston-cs2tp-evotech/evotech)
![GitHub Workflow Status (with event)](https://img.shields.io/github/actions/workflow/status/aston-cs2tp-evotech/evotech/test_and_depoly.yml)


An e-commerce website for selling computer parts and accessories built using PHP, MariaDB, HTML, CSS and JavaScript.
Built as part of the Aston University Computer Science Team Project module.

## Table of Contents
- [Contributing](#contributing)
- [Running locally](#running-locally)
    - [Windows](#windows)
    - [macOS](#macos)
    - [Linux](#linux)
- [Credits](#credits)


## Contributing
For information on how to contribute to the project, please see the [CONTRIBUTING.md](CONTRIBUTING.md) file.

## Running locally
These instructions will guide you through setting up the project to run locally on your machine for development and testing purposes.
### Windows

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

### macOS

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

### Linux

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

## Credits

- [220064521 - Mohammed Kalam](https://github.com/YourKalamity)
- [220073925 - Viktor Dvornik](https://github.com/ToadallyStupid)
- [220043504 - Aaron Dosanjh](https://github.com/Aaron3455454)
- [220095086 - Thomas Evans](https://github.com/TomE134)
- [220216876 - Reece Edwards](https://github.com/Reece-Edwards)
- [220164359 - Gurjot Dhillon](https://github.com/gurjotsd)
- [220070614 - Hanzalah Naguthane](https://github.com/realhanzalah)
- [220098548 - Mahdi Egal](https://github.com/PhantomCodeing)

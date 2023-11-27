# evotech
Team Project for the CS2TP module

# Running locally

## Windows

1. Install XAMPP
    ```powershell
    winget install -e --id ApacheFriends.Xampp.8.2
    ```

2. Clone the repository using `git clone`

3. Change the DocumentRoot in `C:\xampp\apache\conf\httpd.conf` from `C:/xampp/htdocs` to the root of the repository
    - There are two instances of DocumentRoot in the file, change both

4. Start Apache and MySQL in XAMPP

5. Open the following links in your browser:
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
    
4. Clone the repository using `git clone`

5. Change the DocumentRoot in `/Applications/XAMPP/xamppfiles/etc/httpd.conf` from `/Applications/XAMPP/xamppfiles/htdocs` to the root of the repository
    - There are two instances of DocumentRoot in the file, change both

6. Start Apache and MySQL in XAMPP

7. Open the following links in your browser:
    - http://localhost/phpmyadmin
    - http://localhost
    

## Linux

1. Install XAMPP
    ```bash
    wget "https://sourceforge.net/projects/xampp/files/XAMPP%20Linux/8.2.12/xampp-linux-x64-8.2.12-0-installer.run"
    chmod +x xampp-linux-x64-8.2.12-0-installer.run
    sudo ./xampp-linux-x64-8.2.12-0-installer.run
    ```

2. Clone repository into `/opt/lampp/htdocs`
    1. Change directory to `/opt/lampp/htdocs`
        ```bash
        cd /opt/lampp/htdocs
        ```
    2. Clone repository using `git clone`

3. Start Apache and MySQL in XAMPP

4. Open the following links in your browser:
    - http://localhost/phpmyadmin
    - http://localhost/evotech

# Credits

220064521 - Mohammed Kalam  
220073925 - Viktor Dvornik  
220043504 - Aaron Dosanjh  
220095086 - Thomas Evans  
220216876 - Reece Edwards  
220164359 - Gurjot Dhillon  
220070614 - Hanzalah Naguthane  
220098548 - Mahdi Egal  

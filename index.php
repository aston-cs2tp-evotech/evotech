<?php

$request = $_SERVER['REQUEST_URI'];

switch ($request) {

    case '/':
        require __DIR__ . '/view/home.php';
        break;

    case '/aboutus':
        require __DIR__ . '/view/aboutus.php';
        break;

    case '/login':
        require __DIR__ . '/view/login.php';
        break;

    case '/register':
        require __DIR__ . '/view/register.php';
        break;
    
    default:
        require __DIR__ . '/view/home.php';
        break;
}

include 'config/database.php';

?>

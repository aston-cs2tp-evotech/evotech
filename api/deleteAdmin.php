<?php

if (!isset($_POST["adminID"])) {
    http_response_code(400);
    echo "Admin ID not specified";
    exit();
}

$adminID = $_POST['adminID'];

$message = DeleteAdminByAdmin($adminID);

if ($message == "") {
    echo "Admin " . $adminID . " has been deleted.";
} else {
    http_response_code(400);
    echo $message;
}
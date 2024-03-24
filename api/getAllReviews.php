<?php

$success = GetAllReviews();
if (!$success) {
    http_response_code(418);
    echo "Error retrieving reviews";
    exit();
}
else {
    echo json_encode($success);
    exit();
}
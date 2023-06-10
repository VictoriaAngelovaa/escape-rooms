<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(404);
    return;
}

session_start();
session_destroy();
        
echo json_encode(['logged' => false]);

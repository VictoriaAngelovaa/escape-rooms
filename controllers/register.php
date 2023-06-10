<?php
require_once __DIR__.'./../models/user.php';
require_once __DIR__.'./../database/UserAlreadyExistsException.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(404);
}

$json = file_get_contents('php://input');
$registerData = json_decode($json, true);

$username = $registerData['username'];
$password = $registerData['password'];

try {
    $user = User::fromData($username, $password);
}
catch (UserAlreadyExistsException $e) {
     http_response_code(409);
    echo json_encode(['message' => 'User already exists']);
}
catch (Exception $e) {
    http_response_code(500);
    return;
}

session_start();
echo json_encode(['username' => $user->getUsername()]);
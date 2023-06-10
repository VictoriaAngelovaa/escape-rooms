<?php
require_once __DIR__.'./../database/select.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(404);
    return;
}

$json = file_get_contents('php://input');
$loginData = json_decode($json, true);

$username = $loginData['username'];
$password = $loginData['password'];

$user = DBSelect::selectUser($username);

if($user == null || !password_verify($password, $user->getPassword())) {
    http_response_code(401);
    echo json_encode(['message' => 'Невалидно потребителско име или парола'], JSON_UNESCAPED_UNICODE);
    return;
}

session_start();
$_SESSION['username'] = $user->getUsername();
$_SESSION['user_role'] = $user->getRole();

echo json_encode(['message' => 'Успешно влизане'], JSON_UNESCAPED_UNICODE);

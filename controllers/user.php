<?php
require_once __DIR__.'/../models/user.php';
require_once __DIR__.'/../database/select.php';


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET': {
        if(isset($_GET['user'])) {
            $userInfo = DBSelect::selectUserLevels($_GET['user']);

            echo json_encode($userInfo);
        }

        break;
    }
}

<?php
require_once __DIR__.'./../models/level.php';
require_once __DIR__.'./../database/select.php';
require_once __DIR__.'./../models/game.php';


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST': {
        
        $json = file_get_contents('php://input');
        $gameData = json_decode($json, true);

        $gameId = $gameData['gameId'];
        $selectedLevels = $gameData['levels'];
        
        DBSelect::addLevelsToGame($gameId, $selectedLevels);

        echo json_encode($gameData);

        break;
    }
    case 'GET': {
        if(isset($_GET['id'])) {
            $gameId = (int)trim($_GET['id']);

            $levels = DBSelect::selectAvailableLevels($gameId, $_GET['user']);

            echo json_encode($levels);
        }
        else {
            $levels = DBSelect::selectAllLevels();

            echo json_encode($levels);
        }

        break;
    }
    case 'DELETE': { // logout
        session_start();
        session_destroy();
        
        echo json_encode(['logged' => false]);
        break;
    }
}

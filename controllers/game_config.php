<?php
require_once __DIR__.'/../models/game.php';
require_once __DIR__.'/../database/select.php';

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST': {
        if(isset($_FILES['logo-image'])) {
            $logo_tmp = $_FILES['logo-image']['tmp_name'];

           $game = Game::fromData($_POST['name'], "");

            $logo = '../generated/game_logos/' . strval($game->getId());
            move_uploaded_file($logo_tmp, $logo);

            $game->updateLogoPath($logo);

            echo json_encode(['message' => 'Success.', 'game'=> $game->toArray()]);
        }
        else {
            $json = file_get_contents('php://input');
            $gameData = json_decode($json, true);

            if(null !== $gameData) {
                $game = Game::fromJson($gameData); 
                if(!$game) {
                    echo json_encode(['message' => 'Invalid data in json file.']);
                }
                else {
                    echo json_encode(['message' => 'Success.', 'game'=> $game->toArray()]); 
                }
            }
            else {
                echo json_encode(['message' => 'ERROR.']);
            }
        }

        break;
    }
    case 'GET': {
        if(isset($_GET['id'])) {
            $id = (int)trim($_GET['id']);

            $game = DBSelect::selectGame($id);

            echo json_encode($game);
        }
        else {
            $games = DBSelect::selectAllGames();

            echo json_encode($games);
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

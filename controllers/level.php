<?php
require_once __DIR__.'./../models/level.php';
require_once __DIR__.'./../database/select.php';

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST': {
        if (!isset($_SESSION['username']))
            header('Location: ../views/login.php');

        if(isset($_FILES['logo-image']) && isset($_FILES['resource'])) {
            $logo_tmp = $_FILES['logo-image']['tmp_name'];
            $res_tmp = $_FILES['resource']['tmp_name'];
            $res_name = $_FILES['resource']['name'];
            $res_ext = pathinfo($res_name, PATHINFO_EXTENSION);

            $level = Level::fromData($_POST['name'], "", LevelType::tryFrom($_POST['type']), $_POST['lock'], $_POST['category'],
                                     $_POST['answer'], $_POST['attempts'], $_POST['points'], "", $_POST['desc'], $_POST['open'], $_POST['duration'],
                                     1, 1, "", 1, "dark", "bg", $_SESSION['username']);

            $logo = '../generated/level_logos/' . strval($level->getId());
            $res = '../generated/resources/' . strval($level->getId()) . '.' . $res_ext;
            move_uploaded_file($logo_tmp, $logo);
            move_uploaded_file($res_tmp, $res);

            $level->updatePaths($logo, $res);

            echo json_encode(['message' => 'Success.', 'level'=> $level->toArray()]);
        }
        else {
            $json = file_get_contents('php://input');
            $levelData = json_decode($json, true);

            if(null !== $levelData) {
                if(isset($levelData['answer']) && isset($levelData['user'])) {
                    if($levelData['answer'] === true) {
                        if(DBSelect::addLevelToUser($levelData['id'], $levelData['user'])) {
                            echo json_encode(['message' => 'Congrats. The level was added to your profile.']);
                            return;
                        }
                        else {
                            echo json_encode(['message' => 'Congrats. The level is already added to your profile.']);
                            return;
                        }
                    }
                }
                else if (isset($levelData['update']) && $levelData['update'] === "true") {
                    $level = Level::updateJson($levelData['level']); 
                    if(!$level) {
                        echo json_encode(['message' => 'Invalid data in json file.']);
                    }
                    else {
                        echo json_encode(['message' => 'Success.', 'level'=> $level]); 
                    }
                }
                else {
                    $level = Level::fromJson($levelData, $_SESSION['username']); 
                    if(!$level) {
                        echo json_encode(['message' => 'Invalid data in json file.']);
                    }
                    else {
                        echo json_encode(['message' => 'Success.', 'level'=> $level->toArray()]); 
                    }
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
            $id = $_GET['id'];

            $level = DBSelect::selectLevel($id);

            if(isset($_GET['user']) && $_GET['user'] === 'guest' && !isset($_SESSION['username'])) {
                echo json_encode(['level' => $level]);
            }
            else if(isset($_SESSION['username'])) {
                if(isset($_GET['is_return']) && $_GET['is_return'] === "true" &&
                isset($_GET['action']) && $_GET['action'] === "finished"   &&
                isset($_GET['lock_result']) && isset($_GET['user'])) {
                    if($_GET['lock_result'] == "yes") {
                        if(DBSelect::addLevelToUser($level['id'], $_GET['user'])) {
                            echo json_encode(['message' => 'Congrats. The level was added to your profile.', 'level'=> $level]);
                            return;
                        }
                        else {
                            echo json_encode(['message' => 'Congrats. The level is already added to your profile.', 'level'=> $level]);
                            return;
                        }
                    }
                    else {
                        echo json_encode(['message' => 'Try again', 'level'=> $level]);
                        return;
                    }
                }
                else {
                    echo json_encode(['level' => $level]);
                }
            }
            else {
                header('Location: ../views/login.php');
            }
        }
        else {
            if(isset($_GET['filter'])) {
                $levels = DBSelect::selectAllLevels($_GET['filter'], $_GET['value']);
            }
            else {
                $levels = DBSelect::selectAllLevels();
            }

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

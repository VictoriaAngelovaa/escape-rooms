<?php

require_once __DIR__."/Db.php";
require_once __DIR__."./../models/level.php";
require_once __DIR__."./../models/game.php";
require_once __DIR__."./../models/user.php";

class DBSelect {
    public static function selectAllLevels($filter = "name", $value = "%%") {
        $dbConn = new Db();

        $query = "SELECT * FROM levels WHERE $filter like :value";
        
        $rows = $dbConn->fetch($query, array('value' => "%".$value."%"));

        $levels = [];
        foreach ($rows as $levelInfo) {
            $level = new Level($levelInfo['name'], $levelInfo['logo'], 
                                LevelType::tryFrom($levelInfo['type']), 
                                $levelInfo['lock_type'], $levelInfo['category'], 
                                $levelInfo['answer'], $levelInfo['attempts'], 
                                $levelInfo['points'], $levelInfo['resource'],
                                $levelInfo['description'], $levelInfo['open_url'],
                                $levelInfo['duration'], $levelInfo['public'],
                                $levelInfo['show_config'], $levelInfo['config_pass'],
                                $levelInfo['check_answer'], $levelInfo['theme'],
                                $levelInfo['language'], $levelInfo['user']);
            $level->setId($levelInfo['id']);
            $levels[] = $level->toArray();
        }

        return $levels;
    }

    public static function selectLevel($id) {
        $dbConn = new Db();

        $query = "SELECT * FROM levels WHERE id = :id LIMIT 1;";

        $rows = $dbConn->fetch($query, array('id' => $id));

        if(!empty($rows)) {
            $level = new Level($rows[0]['name'], $rows[0]['logo'], 
                                LevelType::tryFrom($rows[0]['type']), 
                                $rows[0]['lock_type'], $rows[0]['category'], 
                                $rows[0]['answer'], $rows[0]['attempts'], 
                                $rows[0]['points'], $rows[0]['resource'],
                                $rows[0]['description'], $rows[0]['open_url'],
                                $rows[0]['duration'], $rows[0]['public'],
                                $rows[0]['show_config'], $rows[0]['config_pass'],
                                $rows[0]['check_answer'], $rows[0]['theme'],
                                $rows[0]['language'], $rows[0]['user']);
            $level->setId($rows[0]['id']);
            return $level->toArray();
        }

        return null;
    }

    public static function selectGame($id) {
        $dbConn = new Db();

        $query = "SELECT * FROM games WHERE id = :id LIMIT 1;";

        $rows = $dbConn->fetch($query, array('id' => $id));

        if(!empty($rows)) {
            $query = "SELECT * FROM levels WHERE id IN (SELECT level_id FROM games_levels WHERE game_id = :id);";
            $levels = $dbConn->fetch($query, array('id' => $id));

            $gameLevels = [];
            foreach ($levels as $levelInfo) {
                $new =  new Level($levelInfo['name'], $levelInfo['logo'], 
                                  LevelType::tryFrom($levelInfo['type']), 
                                  $levelInfo['lock_type'], $levelInfo['category'], 
                                  $levelInfo['answer'], $levelInfo['attempts'], 
                                  $levelInfo['points'], $levelInfo['resource'],
                                  $levelInfo['description'], $levelInfo['open_url'],
                                  $levelInfo['duration'], $levelInfo['public'],
                                  $levelInfo['show_config'], $levelInfo['config_pass'],
                                  $levelInfo['check_answer'], $levelInfo['theme'],
                                  $levelInfo['language'], $levelInfo['user']);
                $new->setId($levelInfo['id']);
                $gameLevels[] = $new->toArray();
            }

            $game = new Game($rows[0]['name'], $rows[0]['logo'], count($levels), $gameLevels);
            $game->setId($rows[0]['id']);

            return $game->toArray();
        }

        return null;
    }

    public static function selectAllGames() {
        $dbConn = new Db();

        $query = "SELECT * FROM games;";

        $rows = $dbConn->fetch($query);

        $games = [];
        foreach ($rows as $game) {
            $query = "SELECT COUNT(level_id) as count FROM games_levels where game_id = :id;";
            $levelsCount = $dbConn->fetch($query, array('id' => $game['id']));

            $new = new Game($game['name'], $game['logo'], $levelsCount[0]['count'], []);
            $new->setId($game['id']);
            $games[] =  $new->toArray();
        }

        return $games;
    }

    public static function selectAvailableLevels($gameId, $user) {
        $dbConn = new Db();

        $query = "SELECT * FROM levels WHERE id NOT IN (SELECT level_id FROM games_levels WHERE game_id = :id)
                                       AND (public = true or user = :user);";
        
        $rows = $dbConn->fetch($query, array('id' => $gameId, 'user' => $user));

        $levels = [];
        foreach ($rows as $levelInfo) {
            $level = new Level($levelInfo['name'], $levelInfo['logo'], 
                                LevelType::tryFrom($levelInfo['type']), 
                                $levelInfo['lock_type'], $levelInfo['category'], 
                                $levelInfo['answer'], $levelInfo['attempts'], 
                                $levelInfo['points'], $levelInfo['resource'],
                                $levelInfo['description'], $levelInfo['open_url'],
                                $levelInfo['duration'], $levelInfo['public'],
                                $levelInfo['show_config'], $levelInfo['config_pass'],
                                $levelInfo['check_answer'], $levelInfo['theme'],
                                $levelInfo['language'], $levelInfo['user']);
            $level->setId($levelInfo['id']);
            $levels[] = $level->toArray();
        }

        return $levels;
    }

    public static function addLevelsToGame($gameId, $selectedLevels) {
        $dbConn = new Db();
        
        foreach ($selectedLevels as $levelId) {
            $query = "INSERT INTO games_levels (game_id, level_id) VALUES(:id, :levelid);";
            $dbConn->insert($query, array( 'id'      => $gameId,
                                           'levelid' => $levelId
                                        ));
        }
    }
        
    public static function selectUser($username) {
        $dbConn = new Db();

        $query = "SELECT * FROM users WHERE username = :username LIMIT 1;";

        $rows = $dbConn->fetch($query, array('username' => $username));

        if(empty($rows)) {
            return null;
        }

        $userData = $rows[0];

        return new User(
            $userData['username'],
            $userData['password'], 
            $userData['role']
        );
    }

    public static function addLevelToUser($levelId, $username) {
        $dbConn = new Db();

        $query = "SELECT COUNT(username) as count FROM users_levels where username = :user and level_id = :level;";
        $alreadyAdded = $dbConn->fetch($query, array('user' => $username, 'level' => $levelId));

        if($alreadyAdded[0]['count'] === 0) {
            $query = "INSERT INTO users_levels (username, level_id) VALUES(:user, :level) ON DUPLICATE KEY UPDATE username = username;";
            $dbConn->insert($query, array( 'user'  => $username,
                                           'level' => $levelId
                                        ));

            return true;
        }

        return false;
    }

    public static function selectUserLevels($username) {
        $dbConn = new Db();

        $query = "SELECT * FROM levels where id in (select level_id from users_levels where username = :user);";
        $rows = $dbConn->fetch($query, array('user' => $username));

        $levels = [];
        foreach ($rows as $levelInfo) {
            $level = new Level($levelInfo['name'], $levelInfo['logo'], 
                                LevelType::tryFrom($levelInfo['type']), 
                                $levelInfo['lock_type'], $levelInfo['category'],
                                $levelInfo['answer'], $levelInfo['attempts'], 
                                $levelInfo['points'], $levelInfo['resource'],
                                $levelInfo['description'], $levelInfo['open_url'],
                                $levelInfo['duration'], $levelInfo['public'],
                                $levelInfo['show_config'], $levelInfo['config_pass'],
                                $levelInfo['check_answer'], $levelInfo['theme'],
                                $levelInfo['language'], $levelInfo['user']);
            $level->setId($levelInfo['id']);
            $levels[] = $level->toArray();
        }

        return $levels;
    }
}
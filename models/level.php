<?php

require_once __DIR__."./../database/Db.php";
require_once __DIR__."/model.php";

enum LevelType: string {
    case Online = 'Online';
    case Live = 'Live';
}

class Level extends Model{
    private $id;
    private $name;
    private $logo;
    private $type;
    private $category;
    private $lockType;
    private $answer;
    private $attempts;
    private $points;
    private $resource;
    private $description;
    private $open_url;
    private $duration;
    private $public;
    private $show_config;
    private $config_pass;
    private $check_answer;
    private $theme;
    private $language;
    private $user;

    public function __construct($name, $logo, $type, $lockType, $category, $answer, $attempts, $points, $resource, $description, 
                                $openUrl, $duration, $public, $show_config, $config_pass, $check_answer, $theme, $language, $user) {
        parent::__construct();

        $this->name = $name;
        $this->logo = $logo;
        $this->type = $type;
        $this->lockType = $lockType;
        $this->category = $category;
        $this->answer = $answer;
        $this->attempts = $attempts;
        $this->points = $points;
        $this->resource = $resource;
        $this->description = $description;
        $this->open_url = $openUrl;
        $this->duration = $duration;
        $this->public = $public;
        $this->show_config = $show_config;
        $this->config_pass = $config_pass;
        $this->check_answer = $check_answer;
        $this->theme = $theme;
        $this->language = $language;
        $this->user = $user;
    }

    private static function isValidJson($json) {
        return array_key_exists('name', $json)        && 
               array_key_exists('logo', $json)        &&
               array_key_exists('type', $json)        &&
               array_key_exists('lockType', $json)    &&
               array_key_exists('category', $json)    &&
               array_key_exists('answer', $json)      &&
               array_key_exists('attempts', $json)    &&
               array_key_exists('points', $json)      &&
               array_key_exists('resource', $json)    &&
               array_key_exists('description', $json) &&
               array_key_exists('open_url', $json)    &&
               array_key_exists('duration', $json)    &&
               array_key_exists('public', $json)      &&
               array_key_exists('show_config', $json) &&
               array_key_exists('config_pass', $json) &&
               array_key_exists('check_answer', $json)&&
               array_key_exists('theme', $json)       &&
               array_key_exists('language', $json);
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getId(): int {
        return $this->id;
    }

    public function toArray() {
        return array(
            'id'          => $this->id,
            'name'        => $this->name,
            'logo'        => $this->logo,
            'type'        => $this->type->value,
            'category'    => $this->category,
            'lockType'    => $this->lockType,
            'answer'      => $this->answer,
            'attempts'    => $this->attempts,
            'points'      => $this->points,
            'resource'    => $this->resource,
            'description' => $this->description,
            'open_url'    => $this->open_url,
            'duration'    => $this->duration,
            'public'      => $this->public,
            'show_config' => $this->show_config,
            'config_pass' => $this->config_pass,
            'check_answer'=> $this->check_answer,
            'theme'       => $this->theme,
            'language'    => $this->language,
            'user'        => $this->user
        );
    }

    public static function fromJson($json, $user) {
        $json = json_decode($json, true);
        if(!empty($json) && self::isValidJson($json)) {
            $new = new static($json["name"], $json["logo"], LevelType::tryFrom($json["type"]), 
                              $json["lockType"], $json["category"], $json["answer"], $json["attempts"], 
                              $json["points"], $json["resource"], $json["description"], $json["open_url"], 
                              $json["duration"], $json["public"], $json["show_config"], $json["config_pass"], 
                              $json["check_answer"], $json["theme"], $json["language"], $user);

            $new->insertToDB();
            $new->setError("success");

            return $new;
        }
        else {
            return null;
        }
    }

    public static function updateJson($json) {
        if(!empty($json) && self::isValidJson($json)) {
            $dbConn = new Db();

            $query = "UPDATE levels SET name = :name, logo = :logo, type = :type, lock_type = :lock_type, category = :category, answer = :answer, attempts = :attempts, points = :points, 
                                        resource = :resource, description = :description, open_url = :open_url, duration = :duration, public = :public, 
                                        show_config = :show_config, config_pass = :config_pass, check_answer = :check_answer, theme = :theme, language = :language
                                    WHERE id = :id;";
    
            $dbConn->insert($query, array( 'name'         => $json["name"],
                                            'logo'        => $json["logo"],
                                            'type'        => $json["type"],
                                            'lock_type'   => $json["lockType"],
                                            'category'    => $json["category"],
                                            'answer'      => $json["answer"],
                                            'attempts'    => $json["attempts"],
                                            'points'      => $json["points"], 
                                            'resource'    => $json["resource"],
                                            'description' => $json["description"],
                                            'open_url'    => $json["open_url"],
                                            'duration'    => $json["duration"],
                                            'public'      => $json["public"],
                                            'show_config' => $json["show_config"],
                                            'config_pass' => $json["config_pass"],
                                            'check_answer'=> $json["check_answer"],
                                            'theme'       => trim($json["theme"]),
                                            'language'    => trim($json["language"]),
                                            'id'          => $json["id"]));
            
            $query = "SELECT * FROM levels WHERE id = :id LIMIT 1;";

            $rows = $dbConn->fetch($query, array('id' => $json["id"]));
    
            if(!empty($rows)) {
                $level = new Level($rows[0]['name'], $rows[0]['logo'], 
                                    LevelType::tryFrom($rows[0]['type']), 
                                    $rows[0]['lock_type'], 
                                    $rows[0]['category'], 
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
        else {
            return null;
        }
    }

    public static function fromData($name, $logo, $type, $lockType, $category, $answer, $attempts, $points, $resource, $description, 
                                    $open_url, $duration, $public, $show_config, $config_pass, $check_answer, $theme, $language, $user) {
        $new = new static($name, $logo, $type, $lockType, $category, $answer, $attempts, $points, $resource, $description, 
                          $open_url, $duration, $public, $show_config, $config_pass, $check_answer, $theme, $language, $user);

        $new->insertToDB();

        return $new;
    }

    private function insertToDB() {
        $dbConn = new Db();

        $query = "INSERT INTO levels (name, logo, type, lock_type, category, answer, attempts, points, resource, description, open_url, duration, public, show_config, config_pass, check_answer, theme, language, user)
                  VALUES (:name, :logo, :type, :lock_type, :category, :answer, :attempts, :points, :resource, :description, :open_url , :duration, :public, :show_config, :config_pass, :check_answer, :theme, :language, :user);";

        $dbConn->insert($query, array( 'name'        => $this->name,
                                       'logo'        => $this->logo,
                                       'type'        => $this->type->value,
                                       'lock_type'   => $this->lockType,
                                       'category'    => $this->category,
                                       'answer'      => $this->answer,
                                       'attempts'    => $this->attempts,
                                       'points'      => $this->points, 
                                       'resource'    => $this->resource,
                                       'description' => $this->description,
                                       'open_url'    => $this->open_url,
                                       'duration'    => $this->duration,
                                       'public'      => $this->public,
                                       'show_config' => $this->show_config,
                                       'config_pass' => $this->config_pass,
                                       'check_answer'=> $this->check_answer,
                                       'theme'       => trim($this->theme),
                                       'language'    => trim($this->language),
                                       'user'        => $this->user));

        $this->id = $dbConn->getConnection()->lastInsertId();
    }

    public function updatePaths($logo, $res) {
        $dbConn = new Db();

        $query = "update levels set logo = :logo, resource = :resource where Id = :id";

        $dbConn->insert($query, array('logo' => $logo, 'resource' => $res, 'id' => $this->id));

        $this->logo = $logo;
        $this->resource = $res;
    }
}

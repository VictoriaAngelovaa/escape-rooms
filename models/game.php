<?php

require_once __DIR__."./../database/Db.php";
require_once __DIR__."./../database/select.php";
require_once __DIR__."/model.php";

class Game extends Model {
    private $id;
    private $name;
    private $logo;
    private $levelsCount;
    private $levels = array();

    public function __construct($name, $logo, $levelsCount, $levels) {
        $this->name        = $name;
        $this->logo        = $logo;
        $this->levelsCount = $levelsCount;
        $this->levels      = $levels;
    }

    private static function isValidJson($json) {
        return array_key_exists('name', $json)        && 
               array_key_exists('logo', $json);
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getId(): int {
        return $this->id;
    }

    public function toArray() {
        return array(
            'id'        => $this->id,
            'name'      => $this->name,
            'logo'      => $this->logo,
            'levelsCount'=> $this->levelsCount,
            'levels'    => $this->levels
        );
    }

    public static function fromJson($json) {
        $json = json_decode($json, true);
        if(!empty($json) && self::isValidJson($json)) {
            $levels_json = $json["levels"];
            $level_ids = [];
            
            foreach ($levels_json as $level_data) {
                $level = Level::fromJson(json_encode($level_data), $_SESSION['username']);
                $level_ids[] = $level->getId();
            }

            $new = new static($json["name"], $json["logo"], 0, array());

            $new->insertToDB();
            
            DBSelect::addLevelsToGame($new->getId(), $level_ids);
            $new->setError("success");

            return $new;
        }
        else {
            return null;
        }
    }

    public static function fromData($name, $logo) {
        $new = new static($name, $logo, 0, array());
    
        $new->insertToDB();
        
        return $new;
    }

    private function insertToDB() {
        $dbConn = new Db();

        $query = "Insert Into games (name, logo)
                  Values (:name, :logo);";

        $dbConn->insert($query, array( 'name'      => $this->name,
                                       'logo'      => $this->logo));

        $this->id = $dbConn->getConnection()->lastInsertId();
    }

    public function updateLogoPath($logo) {
        $dbConn = new Db();

        $query = "update games set logo = :logo where id = :id";

        $dbConn->insert($query, array('logo' => $logo, 'id' => $this->id));

        $this->logo = $logo;
    }
}
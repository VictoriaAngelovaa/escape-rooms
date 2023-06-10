<?php

require_once __DIR__."./../database/Db.php";
require_once __DIR__."/model.php";
require_once __DIR__."/../database/UserAlreadyExistsException.php";

class User extends Model {
    private $username;
    private $password;
    private $role;

    public function __construct(string $username, string $password, string $role) {
        parent::__construct();
        
        $this->username = $username;
        $this->password = $password;
        $this->role = $role;
    }

    private function insertToDB() {
        $dbConn = new Db();

        $query = "SELECT * FROM users WHERE username = :name;";
        $rows = $dbConn->fetch($query, array('name' => $this->username));

        if(!empty($rows)) {
            throw new UserAlreadyExistsException();
        }

        $query = "Insert Into users (username, password, role)
                      Values (:name, :pass, :role);";

        $dbConn->insert($query, array( 'name'      => $this->username,
                                        'pass'      => $this->password,
                                        'role'      => $this->role ));
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getRole(): string {
        return $this->role;
    }

    public static function fromData($username, $password, $role = "user") {
        $new = new static($username, password_hash($password, PASSWORD_DEFAULT), $role);

        $new->insertToDB();
        
        return $new;
    }
}
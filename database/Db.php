<?php

class Db {
    private $connection;

    public function __construct() {
        $dbhost = "localhost";
        $dbName = "escape_rooms";
        $userName = "root";
        $userPassword = "";

        $this->connection = new PDO("mysql:host=$dbhost;dbname=$dbName", $userName, $userPassword,
            [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
    }

    public function getConnection() {
        return $this->connection;
    }

    // Executes insert or delete statement, because they are using the same PDO interface
    private function executeStatement(string $sql, array $params) {
        $statement = $this->getConnection()->prepare($sql);

        $isSuccessful = false;
        try {
            $isSuccessful = $statement->execute($params); 
        } catch(Exception $e) {
            throw $e;
        }
    
        if (!$isSuccessful) {
            throw new Exception("Internal server error");
        }
    }
    
    public function insert(string $sql, array $params) {
        $this->executeStatement($sql, $params);
    }

    public function delete(string $sql, array $params) {
        $this->executeStatement($sql, $params);
    }

    public function fetch(string $sql, array $params = []) {
        $selectStatement = $this->getConnection()->prepare($sql);
        $isSuccessful = false;
        try {
            $isSuccessful = $selectStatement->execute($params); 
        } catch(Exception $e) {
            throw $e;
        }
        if (!$isSuccessful) {
            throw new Exception("Internal server error");
        }

        return $selectStatement->fetchAll();
    }
}

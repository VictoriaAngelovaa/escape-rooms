<?php 

class Model {
    public $error;

    protected function __construct() {
        $this->error = "success";
    }

    public function setError($error) {
        $this->error = $error;
    }

    public function hasError() {
        return $this->error === "fail";
    }
}
<?php

namespace MyApp;

class Model {
    protected $db;
    public function __construct() {
        try {
            $this->db = new \PDO(DSN, DB_USERNAME, DB_PASSWORD);
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }
}
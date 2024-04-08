<?php

namespace app\services;

class App {
    private static $connect;

    public static function start() {
        self::db();
    }

    public static function get_connect() {
        return self::$connect;
    }

    private static function db() {
        $config = require_once "configs/db.php";
        try {
            self::$connect = new \PDO('mysql:host='.$config['host'].';dbname='.$config['dbname'], $config['user'], $config['pass']);
        } catch (\PDOException $e) {
            die("Error: ".$e->getMessage()."<br>");
        }
    }
}

?>
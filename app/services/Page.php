<?php

namespace app\services;

class Page {
    public static function part($part_name) {
        require_once "views/components/".$part_name.".php";
    }
}

?>
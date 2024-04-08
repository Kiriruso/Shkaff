<?php

namespace app\services;
use app\controllers\Auth;

class Router {
    private static $list = [];
    
    public static function page($uri, $page_name, $category) {
        self::$list[] = [
            "uri" => $uri,
            "page" => $page_name,
            "category" => $category
        ];
    }
    
    public static function post($uri, $class, $method, $data = false, $files = false) {
        self::$list[] = [
            "uri" => $uri,
            "class" => $class,
            "method" => $method,
            "data" => $data,
            "files" => $files,
            "is_post" => true
        ];
    }

    public static function enable() {
        if (isset($_GET['q'])) {
            $query = $_GET['q'];
            foreach (self::$list as $route) {
                if ($route['uri'] === '/'.$query) {
                    if (isset($route['is_post']) && $_SERVER['REQUEST_METHOD'] === "POST") {
                        $action = new $route['class'];
                        $method = $route['method'];
                        if ($route['data'] && $route['files'])
                            $action->$method($_POST, $_FILES);
                        else if ($route['data'])
                            $action->$method($_POST);
                        else 
                            $action->$method();
                        die();
                    } else if (isset($route['page'])) {
                        if (isset($_SESSION['user'])) {
                            if ($route['page'] === 'login' || $route['page'] === 'register') {
                                self::error(409);
                                die();
                            }
                            if ($_SESSION['user']['category'] >= $route['category']) {
                                require_once "views/pages/".$route['page'].".php";
                                die();
                            }
                            self::error(403);
                            die();
                        }
                        if ($route['category'] == Auth::GUEST) {
                            require_once "views/pages/".$route['page'].".php";
                            die();
                        }
                        self::redirect('/login');
                        die();
                    }
                }
            }
        } else {
            self::redirect('/catalog');
            die();
        }
        self::error(404);
    }

    public static function error($code) {
        require_once "views/errors/".$code.".php";
    }

    public static function redirect($uri) {
        header('Location: '.$uri);
    }
}

?>
<?php

namespace app\controllers;
use app\services\App;
use app\services\Router;

class Auth {
    const GUEST = 0;
    const COMMON = 1;
    const ADMIN = 2;

    public function login($data) {
        $email = $data['email'];
        $password = $data['password'];

        $connect = App::get_connect();
        $stmt = $connect->prepare("SELECT * FROM `user` WHERE email = ?");
        $stmt->execute(array($email));
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user != null && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user'] = [
                "id" => $user['id'],
                "email" => $user['email'],
                "full_name" => $user['full_name'],
                "category" => $user['category']
            ];
            Router::redirect('/catalog');
        } else {
            $_SESSION['auth'] = "Неверный логин или пароль";
            Router::redirect('/login');
        }
    }

    public function register($data) {
        $full_name = $data['full_name'];
        $email = $data['email'];
        $password = $data['password'];
        $confirm = $data['confirm'];

        if ($password === $confirm) {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $connect = App::get_connect();
            $stmt = $connect->prepare("INSERT INTO `user` (email, password, full_name, category) VALUES (?, ?, ?, ?)");
            try {
                $stmt->execute(array($email, $password, $full_name, self::COMMON));
            } catch (\PDOException $e) {
                $_SESSION['auth'] = "Такой пользователь уже зарегистрирован";
                Router::redirect('/register');
                die();
            }
            Router::redirect('/login');
        } else {
            $_SESSION['auth'] = "Вы не подтвердили пароль";
            Router::redirect('/register');
        }
    }

    public static function logout() {
        unset($_SESSION['user']);
        Router::redirect('/catalog');
    }
}
?>
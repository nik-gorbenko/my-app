<?php
require_once "db_settings.php";
class DB
{
    private static $instance;

    public static function getInstance() {
        global $db_settings;
        $dsn = "mysql:dbname=".
                $db_settings['db_name'].
                ";host=".
                $db_settings['host'];
        $user = $db_settings['user_name'];
        $password = $db_settings['user_pass'];

        try {
            if (self::$instance) return self::$instance;
            self::$instance = new PDO($dsn, $user, $password);
        } catch (PDOException $e) {
            echo 'Подключение не удалось: ' . $e->getMessage();
        }
        return self::$instance;

    }

    private function __construct() {}
    private function __clone() {}
    private function __wakeup() {}
}
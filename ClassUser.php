<?php
require_once "ClassDB.php";

class User extends DB
{
    public $name;
    public $family;
    public $email;
    public $login;
    public $pass;
    public $type;
    private static $dbh;

    public function __construct($name, $family, $email, $login, $pass, $type = 0)
    {
        self::$dbh = DB::getInstance();
        $this->name = $name;
        $this->family = $family;
        $this->email = $email;
        $this->login = $login;
        $this->pass = $pass;
        $this->type = $type;
    }
    public function create() {
        $sql = 'INSERT INTO `users` (`name`, `family`, `email`, `login`, `pass`, `type`)
                                                                VALUES (:name, :family, :email, :login, :pass, :type)';
        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':name', $this->name, PDO::PARAM_STR);
        $sth->bindParam(':family', $this->family, PDO::PARAM_STR);
        $sth->bindParam(':email', $this->email, PDO::PARAM_STR);
        $sth->bindParam(':login', $this->login, PDO::PARAM_STR);
        $sth->bindParam(':pass', $this->pass, PDO::PARAM_STR);
        $sth->bindParam(':type', $this->type, PDO::PARAM_INT);
        if ($sth->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public static function delete($id) {
        self::$dbh = DB::getInstance();
        $sth = self::$dbh->prepare('DELETE FROM `users` WHERE `id` = :id');
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        $sth->execute();
    }
    public static function get($id) {
        self::$dbh = DB::getInstance();
        $sql = 'SELECT * FROM `users` WHERE `id` = :id';
        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    public static function isAdmin($login) {
        self::$dbh = DB::getInstance();
        $sql = 'SELECT `type` FROM `users` WHERE `id` = :login';
        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':login', $id, PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return (bool) $result;
    }
    public static function loginExist($login) {
        self::$dbh = DB::getInstance();
        $sql = 'SELECT `login` FROM `users` WHERE `login` = :login';
        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':login', $login, PDO::PARAM_STR);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        if (count($result) == 0) {
            return $result;
        } else {
            return $result;
        }
    }
    public static function issetUser($login, $pass) {
        self::$dbh = DB::getInstance();
        $sql = 'SELECT `name`, `id`, `type` FROM `users` WHERE (`login` = :login) AND (`pass` = :pass)';
        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':login', $login, PDO::PARAM_STR);
        $sth->bindParam(':pass', $pass, PDO::PARAM_STR);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return  $result;
    }
}
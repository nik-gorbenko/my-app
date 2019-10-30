<?php
require_once "ClassDB.php";

class Comment extends DB
{
    public $user_id;
    public $text;
    public $date;
    public $element_id;
    private static $dbh;

    public function __construct($user_id, $text, $date, $element_id)
    {
        self::$dbh = DB::getInstance();
        $this->user_id = $user_id;
        $this->text = $text;
        $this->date = $date;
        $this->element_id = $element_id;
    }
    public function create() {
        $sql = 'INSERT INTO `comments` (`user_id`, `text`, `date`, `element_id`)
                VALUES (:user_id, :text, :date, :element_id)';
        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
        $sth->bindParam(':text', $this->text, PDO::PARAM_STR);
        $sth->bindParam(':date', $this->date, PDO::PARAM_STR);
        $sth->bindParam(':element_id', $this->element_id, PDO::PARAM_STR);
        $sth->execute();
    }
    public static function update($id, $text) {
        self::$dbh = DB::getInstance();
        $sql = 'UPDATE `comments` 
                SET `text` = :text
                WHERE `id` = :id';
        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':text', $text, PDO::PARAM_STR);
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        if ($sth->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public static function delete($id) {
        self::$dbh = DB::getInstance();
        $sth = self::$dbh->prepare('DELETE FROM `comments` WHERE `id` = :id');
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        if ($sth->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public static function get($id, $element_id) {
        self::$dbh = DB::getInstance();
        $sql = 'SELECT * FROM `comments` WHERE (`user_id` = :id) AND (`element_id` = :element_id)';
        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        $sth->bindParam(':element_id', $element_id, PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    public static function fetch($element_id) {
        self::$dbh = DB::getInstance();
        $sql = 'SELECT `users`.`name`, `users`.`family`, `comments`.`id`, `comments`.`text`, `comments`.`date`, `comments`.`user_id` 
                FROM `comments` LEFT JOIN `users` ON `users`.`id` = `comments`.`user_id` WHERE `comments`.`element_id` = :element_id';
        $sth = self::$dbh->prepare($sql);
        $sth->bindParam(':element_id', $element_id, PDO::PARAM_STR);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}
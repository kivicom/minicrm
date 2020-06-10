<?php
namespace DB;

use PDO;

class Connection
{
    private $pdo;


    public static function make()
    {
        $dsn = 'mysql:host=127.0.0.1; dbname=dbname;charset=utf8';
        $user = 'dbuser';
        $password = '123456';

        try {
            $pdo = new PDO($dsn, $user, $password,  array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        } catch (PDOException $e) {
            echo "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        return $pdo;
    }

}
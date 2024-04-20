<?php

namespace App\Core\Database;

class Connection
{
    public static ?\PDO $connection = null;
    public static string $host = 'localhost';
    public static string $username = 'root';
    public static string $dbname = 'bank';
    public static string $password = 'password';


    public static function getConnection(): \PDO
    {
        return (self::$connection == null)
            ? self::$connection = new \PDO("mysql:host=".self::$host.";dbname=" . self::$dbname, self::$username, self::$password)
            : self::$connection;
    }
}
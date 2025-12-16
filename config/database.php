<?php

class Database
{

    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        $host = '127.0.0.1';
        $dbname = 'jsp c quoi';
        $user = 'non plus';
        $password = 'non plus.';
        $dsn = "mysql:host=$host;dbname=$dbname;charset=UTF8";

        try {
            $this->pdo = new PDO($dsn, $user, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->pdo;
    }
}

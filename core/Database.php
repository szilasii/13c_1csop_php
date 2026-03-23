<?php
class Database { 
    protected $pdo;
    public function __construct($config) {
        try {
            $dsn = "mysql:host={$config['host']};dbname={$config['name']};charset=utf8";
            $this->pdo = new PDO($dsn, $config['user'], $config['password']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function __destruct()
    {
        $this->pdo = null;
    }
}
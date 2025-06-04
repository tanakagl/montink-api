<?php
class DataBase {
    private $config;
    
    public function __construct() {
        $this->config = require_once 'config/database.php';
    }

    public function getConnection() {
        $this->conn = null;
        try {
            $dsn = "mysql:host=" . $this->config['host'] . ";dbname=" . $this->config['dbname'] . ";charset=" . $this->config['charset'];
            $this->conn = new PDO($dsn, $this->config['username'], $this->config['password'], $this->config['options']);
        } catch(PDOException $e) {
            echo "Erro na conexão: " . $e->getMessage();
        }
        return $this->conn;
    }
}
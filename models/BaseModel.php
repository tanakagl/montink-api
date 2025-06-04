<?php
class BaseModel {
    protected $pdo;
    
    public function __construct() {
        $this->initializeConnection();
    }
    
    private function initializeConnection() {
        $configPath = __DIR__ . '/../config/database.php';
        
        if (!file_exists($configPath)) {
            throw new Exception("Arquivo de configuração não encontrado: $configPath");
        }
        
        $config = require $configPath;
        
        if (!isset($config['host']) || !isset($config['dbname'])) {
            throw new Exception("Configuração de banco inválida");
        }
        
        try {
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
            $this->pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
            
            $this->pdo->query("SELECT 1");
            
        } catch(PDOException $e) {
            error_log("Erro de conexão PDO: " . $e->getMessage());
            throw new Exception("Erro na conexão com banco de dados: " . $e->getMessage());
        }
    }
    
    protected function execute($sql, $params = []) {
        if (!$this->pdo) {
            throw new Exception("Conexão PDO não inicializada");
        }
        
        try {
            $stmt = $this->pdo->prepare($sql);
            if (!$stmt) {
                throw new Exception("Erro ao preparar statement");
            }
            
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Erro na query: $sql - " . $e->getMessage());
            throw new Exception("Erro na query: " . $e->getMessage());
        }
    }
    
    protected function fetchAll($sql, $params = []) {
        $stmt = $this->execute($sql, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    protected function fetch($sql, $params = []) {
        $stmt = $this->execute($sql, $params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>

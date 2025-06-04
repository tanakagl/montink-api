<?php
require_once 'models/BaseModel.php';

class Produto extends BaseModel {
    protected $table = 'produtos';

    public function create($data) {
        $query = "INSERT INTO produtos (nome, preco, descricao) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$data['nome'], $data['preco'], $data['descricao'] ?? '']);
        return $this->conn->lastInsertId();
    }

    public function findById($id) {
        return $this->fetch("SELECT * FROM produtos WHERE id = ?", [$id]);
    }

    public function findAll() {
        return $this->fetchAll("SELECT * FROM produtos ORDER BY nome");
    }

    public function update($id, $data) {
        $query = "UPDATE produtos SET nome = ?, preco = ?, descricao = ? WHERE id = ?";
        return $this->execute($query, [$data['nome'], $data['preco'], $data['descricao'] ?? '', $id]);
    }

    public function delete($id) {
        return $this->execute("DELETE FROM produtos WHERE id = ?", [$id]);
    }

    public function getVariacoes($produtoId) {
        return $this->fetchAll("SELECT * FROM produto_variacoes WHERE produto_id = ?", [$produtoId]);
    }

    public function createVariacao($produtoId, $nome, $valorAdicional = 0) {
        $query = "INSERT INTO produto_variacoes (produto_id, nome, valor_adicional) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$produtoId, $nome, $valorAdicional]);
        return $this->conn->lastInsertId();
    }

    public function deleteVariacao($id) {
        return $this->execute("DELETE FROM produto_variacoes WHERE id = ?", [$id]);
    }
}
?>
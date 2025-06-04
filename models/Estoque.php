<?php
require_once 'models/BaseModel.php';

class Estoque extends BaseModel {
    protected $table = 'estoque';

    public function getByProduto($produtoId, $variacaoId = null) {
        $query = "SELECT * FROM estoque WHERE produto_id = ?";
        $params = [$produtoId];
        
        if ($variacaoId) {
            $query .= " AND variacao_id = ?";
            $params[] = $variacaoId;
        } else {
            $query .= " AND variacao_id IS NULL";
        }
        
        return $this->fetch($query, $params);
    }

    public function updateEstoque($produtoId, $quantidade, $variacaoId = null) {
        $existing = $this->getByProduto($produtoId, $variacaoId);
        
        if ($existing) {
            $query = "UPDATE estoque SET quantidade = ? WHERE produto_id = ?";
            $params = [$quantidade, $produtoId];
            
            if ($variacaoId) {
                $query .= " AND variacao_id = ?";
                $params[] = $variacaoId;
            } else {
                $query .= " AND variacao_id IS NULL";
            }
            
            return $this->execute($query, $params);
        } else {
            $query = "INSERT INTO estoque (produto_id, variacao_id, quantidade) VALUES (?, ?, ?)";
            return $this->execute($query, [$produtoId, $variacaoId, $quantidade]);
        }
    }

    public function diminuirEstoque($produtoId, $quantidade, $variacaoId = null) {
        $estoque = $this->getByProduto($produtoId, $variacaoId);
        if ($estoque && $estoque['quantidade'] >= $quantidade) {
            $novaQuantidade = $estoque['quantidade'] - $quantidade;
            return $this->updateEstoque($produtoId, $novaQuantidade, $variacaoId);
        }
        return false;
    }

    public function aumentarEstoque($produtoId, $quantidade, $variacaoId = null) {
        $estoque = $this->getByProduto($produtoId, $variacaoId);
        $novaQuantidade = ($estoque ? $estoque['quantidade'] : 0) + $quantidade;
        return $this->updateEstoque($produtoId, $novaQuantidade, $variacaoId);
    }
}
?>
<?php
require_once 'models/BaseModel.php';

class Pedido extends BaseModel {
    protected $table = 'pedidos';

    public function create($data) {
        $query = "INSERT INTO pedidos (subtotal, frete, desconto, total, cupom_codigo, cliente_nome, cliente_email, cliente_cep, cliente_endereco) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            $data['subtotal'],
            $data['frete'],
            $data['desconto'],
            $data['total'],
            $data['cupom_codigo'],
            $data['cliente_nome'],
            $data['cliente_email'],
            $data['cliente_cep'],
            $data['cliente_endereco']
        ]);
        
        return $this->conn->lastInsertId();
    }

    public function addItem($pedidoId, $produtoId, $variacaoId, $quantidade, $precoUnitario) {
        $query = "INSERT INTO pedido_itens (pedido_id, produto_id, variacao_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?, ?)";
        return $this->execute($query, [$pedidoId, $produtoId, $variacaoId, $quantidade, $precoUnitario]);
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE pedidos SET status = ? WHERE id = ?";
        return $this->execute($query, [$status, $id]);
    }

    public function findById($id) {
        return $this->fetch("SELECT * FROM pedidos WHERE id = ?", [$id]);
    }

    public function delete($id) {
        return $this->execute("DELETE FROM pedidos WHERE id = ?", [$id]);
    }

    public function calcularFrete($subtotal) {
        if ($subtotal >= 200) {
            return 0; // Frete grátis
        } elseif ($subtotal >= 52 && $subtotal <= 166.59) {
            return 15;
        } else {
            return 20;
        }
    }
}
?>
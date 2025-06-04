<?php
require_once 'models/BaseModel.php';

class Cupom extends BaseModel {
    protected $table = 'cupons';

    public function findByCodigo($codigo) {
        $query = "SELECT * FROM cupons WHERE codigo = ? AND ativo = 1 AND data_validade >= CURDATE()";
        return $this->fetch($query, [$codigo]);
    }

    public function calcularDesconto($codigo, $subtotal) {
        $cupom = $this->findByCodigo($codigo);
        
        if (!$cupom || $subtotal < $cupom['valor_minimo']) {
            return 0;
        }

        if ($cupom['desconto_tipo'] === 'fixo') {
            return $cupom['desconto_valor'];
        } else {
            return ($subtotal * $cupom['desconto_valor']) / 100;
        }
    }

    public function findAll() {
        return $this->fetchAll("SELECT * FROM cupons ORDER BY created_at DESC");
    }

    public function create($data) {
        $query = "INSERT INTO cupons (codigo, desconto_tipo, desconto_valor, valor_minimo, data_validade) VALUES (?, ?, ?, ?, ?)";
        return $this->execute($query, [
            $data['codigo'],
            $data['desconto_tipo'],
            $data['desconto_valor'],
            $data['valor_minimo'],
            $data['data_validade']
        ]);
    }
}
?>
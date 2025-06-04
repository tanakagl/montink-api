<?php
require_once 'models/Pedido.php';
require_once 'models/Estoque.php';

class WebhookController {
    private $pedido;
    private $estoque;

    public function __construct() {
        $this->pedido = new Pedido();
        $this->estoque = new Estoque();
    }

    public function statusUpdate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['id']) || !isset($input['status'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }

        $pedidoId = $input['id'];
        $status = $input['status'];

        $pedidoExistente = $this->pedido->findById($pedidoId);
        if (!$pedidoExistente) {
            http_response_code(404);
            echo json_encode(['error' => 'Pedido não encontrado']);
            return;
        }

        if ($status === 'cancelado') {
            // Restaurar estoque antes de deletar
            $this->restaurarEstoque($pedidoId);
            $this->pedido->delete($pedidoId);
            
            echo json_encode(['message' => 'Pedido cancelado e removido']);
        } else {
            $this->pedido->updateStatus($pedidoId, $status);
            echo json_encode(['message' => 'Status do pedido atualizado']);
        }
    }

    private function restaurarEstoque($pedidoId) {
        $itens = $this->pedido->fetchAll(
            "SELECT produto_id, variacao_id, quantidade FROM pedido_itens WHERE pedido_id = ?", 
            [$pedidoId]
        );

        foreach ($itens as $item) {
            $this->estoque->aumentarEstoque($item['produto_id'], $item['quantidade'], $item['variacao_id']);
        }
    }
}
?>
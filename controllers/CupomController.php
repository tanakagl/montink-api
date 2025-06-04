<?php
require_once 'models/Cupom.php';

class CupomController {
    private $cupom;

    public function __construct() {
        $this->cupom = new Cupom();
    }

    public function index() {
        $cupons = $this->cupom->findAll();
        include 'views/cupons/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'codigo' => strtoupper($_POST['codigo']),
                'desconto_tipo' => $_POST['desconto_tipo'],
                'desconto_valor' => floatval($_POST['desconto_valor']),
                'valor_minimo' => floatval($_POST['valor_minimo']),
                'data_validade' => $_POST['data_validade']
            ];

            if ($this->cupom->create($data)) {
                $_SESSION['success'] = 'Cupom criado com sucesso!';
                header('Location: index.php?action=cupons');
                exit;
            } else {
                $_SESSION['error'] = 'Erro ao criar cupom. Código pode já existir.';
            }
        }
        
        include 'views/cupons/create.php';
    }
}
?>
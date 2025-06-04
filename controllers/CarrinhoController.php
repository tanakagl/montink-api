<?php
require_once 'models/Produto.php';
require_once 'models/Estoque.php';
require_once 'models/Cupom.php';
require_once 'models/Pedido.php';
require_once 'services/EmailService.php';
require_once 'services/CepService.php';

class CarrinhoController {
    private $produto;
    private $estoque;
    private $cupom;
    private $pedido;

    public function __construct() {
        $this->produto = new Produto();
        $this->estoque = new Estoque();
        $this->cupom = new Cupom();
        $this->pedido = new Pedido();
        
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }
    }

    public function adicionar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $produtoId = intval($_POST['produto_id']);
            $variacaoId = !empty($_POST['variacao_id']) ? intval($_POST['variacao_id']) : null;
            $quantidade = intval($_POST['quantidade']);

            $produto = $this->produto->findById($produtoId);
            if (!$produto) {
                $_SESSION['error'] = 'Produto não encontrado';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit;
            }

            $estoque = $this->estoque->getByProduto($produtoId, $variacaoId);
            if (!$estoque || $estoque['quantidade'] < $quantidade) {
                $_SESSION['error'] = 'Estoque insuficiente';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit;
            }

            $key = $produtoId . '_' . ($variacaoId ?? '0');
            
            if (isset($_SESSION['carrinho'][$key])) {
                $_SESSION['carrinho'][$key]['quantidade'] += $quantidade;
            } else {
                $_SESSION['carrinho'][$key] = [
                    'produto_id' => $produtoId,
                    'variacao_id' => $variacaoId,
                    'quantidade' => $quantidade,
                    'produto' => $produto
                ];
            }

            $_SESSION['success'] = 'Produto adicionado ao carrinho';
            header('Location: index.php?action=carrinho');
            exit;
        }
    }

    public function index() {
        $carrinho = $_SESSION['carrinho'];
        $subtotal = $this->calcularSubtotal();
        $frete = $this->pedido->calcularFrete($subtotal);
        
        include 'views/carrinho/index.php';
    }

    public function aplicarCupom() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $codigo = $_POST['cupom_codigo'];
            $subtotal = $this->calcularSubtotal();
            
            $desconto = $this->cupom->calcularDesconto($codigo, $subtotal);
            
            if ($desconto > 0) {
                $_SESSION['cupom'] = [
                    'codigo' => $codigo,
                    'desconto' => $desconto
                ];
                $_SESSION['success'] = 'Cupom aplicado com sucesso!';
            } else {
                $_SESSION['error'] = 'Cupom inválido ou não atende aos requisitos';
            }
        }
        
        header('Location: index.php?action=carrinho');
        exit;
    }

    public function checkout() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $subtotal = $this->calcularSubtotal();
            $frete = $this->pedido->calcularFrete($subtotal);
            $desconto = isset($_SESSION['cupom']) ? $_SESSION['cupom']['desconto'] : 0;
            $total = $subtotal + $frete - $desconto;

            // Verificar CEP
            $cepService = new CepService();
            $endereco = $cepService->buscarCep($_POST['cep']);
            
            if (!$endereco) {
                $_SESSION['error'] = 'CEP inválido';
                header('Location: index.php?action=carrinho');
                exit;
            }

            $enderecoCompleto = $endereco['logradouro'] . ', ' . $_POST['numero'] . 
                              (!empty($_POST['complemento']) ? ', ' . $_POST['complemento'] : '') .
                              ' - ' . $endereco['bairro'] . ' - ' . $endereco['localidade'] . '/' . $endereco['uf'];

            // Criar pedido
            $pedidoData = [
                'subtotal' => $subtotal,
                'frete' => $frete,
                'desconto' => $desconto,
                'total' => $total,
                'cupom_codigo' => isset($_SESSION['cupom']) ? $_SESSION['cupom']['codigo'] : null,
                'cliente_nome' => $_POST['nome'],
                'cliente_email' => $_POST['email'],
                'cliente_cep' => $_POST['cep'],
                'cliente_endereco' => $enderecoCompleto
            ];

            $pedidoId = $this->pedido->create($pedidoData);

            // Adicionar itens do pedido e diminuir estoque
            foreach ($_SESSION['carrinho'] as $item) {
                $this->pedido->addItem($pedidoId, $item['produto_id'], $item['variacao_id'], 
                                     $item['quantidade'], $item['produto']['preco']);
                
                $this->estoque->diminuirEstoque($item['produto_id'], $item['quantidade'], $item['variacao_id']);
            }

            // Enviar email
            $emailService = new EmailService();
            $emailService->enviarConfirmacaoPedido($_POST['email'], $pedidoId, $pedidoData);

            // Limpar carrinho
            $_SESSION['carrinho'] = [];
            unset($_SESSION['cupom']);

            $_SESSION['success'] = 'Pedido realizado com sucesso! ID: ' . $pedidoId;
            header('Location: index.php?action=carrinho');
            exit;
        }

        include 'views/carrinho/checkout.php';
    }

    private function calcularSubtotal() {
        $subtotal = 0;
        foreach ($_SESSION['carrinho'] as $item) {
            $subtotal += $item['produto']['preco'] * $item['quantidade'];
        }
        return $subtotal;
    }

    public function remover($key) {
        if (isset($_SESSION['carrinho'][$key])) {
            unset($_SESSION['carrinho'][$key]);
            $_SESSION['success'] = 'Item removido do carrinho';
        }
        header('Location: index.php?action=carrinho');
        exit;
    }
}
?>
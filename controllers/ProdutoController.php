<?php
require_once 'models/Produto.php';
require_once 'models/Estoque.php';

class ProdutoController {
    private $produto;
    private $estoque;

    public function __construct() {
        $this->produto = new Produto();
        $this->estoque = new Estoque();
    }

    public function index() {
        $produtos = $this->produto->findAll();
        include 'views/produtos/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nome' => $_POST['nome'],
                'preco' => floatval($_POST['preco']),
                'descricao' => $_POST['descricao'] ?? ''
            ];

            $produtoId = $this->produto->create($data);
            
            // Criar estoque inicial
            $this->estoque->updateEstoque($produtoId, intval($_POST['estoque']));

            // Criar variações se existirem
            if (!empty($_POST['variacoes'])) {
                foreach ($_POST['variacoes'] as $variacao) {
                    if (!empty($variacao['nome'])) {
                        $variacaoId = $this->produto->createVariacao($produtoId, $variacao['nome'], $variacao['valor_adicional'] ?? 0);
                        $this->estoque->updateEstoque($produtoId, $variacao['estoque'] ?? 0, $variacaoId);
                    }
                }
            }

            header('Location: index.php?action=produtos');
            exit;
        }
        
        include 'views/produtos/create.php';
    }

    public function edit($id) {
        $produto = $this->produto->findById($id);
        if (!$produto) {
            header('Location: index.php?action=produtos');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nome' => $_POST['nome'],
                'preco' => floatval($_POST['preco']),
                'descricao' => $_POST['descricao'] ?? ''
            ];

            $this->produto->update($id, $data);
            $this->estoque->updateEstoque($id, intval($_POST['estoque']));

            header('Location: index.php?action=produtos');
            exit;
        }

        $variacoes = $this->produto->getVariacoes($id);
        $estoque = $this->estoque->getByProduto($id);
        
        include 'views/produtos/edit.php';
    }

    public function show($id) {
        $produto = $this->produto->findById($id);
        if (!$produto) {
            header('Location: index.php?action=produtos');
            exit;
        }

        $variacoes = $this->produto->getVariacoes($id);
        $estoque = $this->estoque->getByProduto($id);
        
        include 'views/produtos/show.php';
    }
}
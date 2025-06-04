<?php
session_start();

// Autoload das classes
spl_autoload_register(function ($class) {
    $paths = [
        'controllers/',
        'models/',
        'services/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

$action = $_GET['action'] ?? 'produtos';
$method = $_GET['method'] ?? 'index';
$id = $_GET['id'] ?? null;

try {
    switch ($action) {
        case 'produtos':
            $controller = new ProdutoController();
            break;
            
        case 'carrinho':
            $controller = new CarrinhoController();
            break;
            
        case 'cupons':
            $controller = new CupomController();
            break;
            
        case 'webhook':
            $controller = new WebhookController();
            break;
            
        default:
            $controller = new ProdutoController();
            $method = 'index';
    }

    if (method_exists($controller, $method)) {
        if ($id) {
            $controller->$method($id);
        } else {
            $controller->$method();
        }
    } else {
        throw new Exception("Método não encontrado");
    }
    
} catch (Exception $e) {
    $_SESSION['error'] = 'Erro: ' . $e->getMessage();
    header('Location: index.php');
    exit;
}
?>
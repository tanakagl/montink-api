<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Montink - E-commerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="fas fa-store"></i> Montink</a>
            
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php?action=produtos">
                    <i class="fas fa-box"></i> Produtos
                </a>
                <a class="nav-link" href="index.php?action=carrinho">
                    <i class="fas fa-shopping-cart"></i> Carrinho 
                    <?php if (!empty($_SESSION['carrinho'])): ?>
                        <span class="badge bg-danger"><?= count($_SESSION['carrinho']) ?></span>
                    <?php endif; ?>
                </a>
                <a class="nav-link" href="index.php?action=cupons">
                    <i class="fas fa-tags"></i> Cupons
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= $_SESSION['success'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= $_SESSION['error'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
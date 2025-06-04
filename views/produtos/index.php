<?php include 'views/layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-box"></i> Produtos</h1>
    <a href="index.php?action=produtos&method=create" class="btn btn-primary">
        <i class="fas fa-plus"></i> Novo Produto
    </a>
</div>

<?php if (empty($produtos)): ?>
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> Nenhum produto cadastrado ainda.
    </div>
<?php else: ?>
    <div class="row">
        <?php foreach ($produtos as $produto): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($produto['nome']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($produto['descricao']) ?></p>
                        <p class="card-text">
                            <strong class="text-success">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></strong>
                        </p>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="btn-group w-100">
                            <a href="index.php?action=produtos&method=show&id=<?= $produto['id'] ?>" 
                               class="btn btn-outline-primary">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                            <a href="index.php?action=produtos&method=edit&id=<?= $produto['id'] ?>" 
                               class="btn btn-outline-secondary">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include 'views/layout/footer.php'; ?>
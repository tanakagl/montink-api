<?php include 'views/layout/header.php'; ?>

<h1><i class="fas fa-shopping-cart"></i> Meu Carrinho</h1>

<?php if (empty($carrinho)): ?>
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> Seu carrinho está vazio.
        <a href="index.php?action=produtos" class="btn btn-primary ms-3">
            <i class="fas fa-shopping-bag"></i> Continuar Comprando
        </a>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <?php foreach ($carrinho as $key => $item): ?>
                        <div class="row align-items-center border-bottom py-3">
                            <div class="col-md-6">
                                <h5><?= htmlspecialchars($item['produto']['nome']) ?></h5>
                                <?php if ($item['variacao_id']): ?>
                                    <small class="text-muted">Variação: ID <?= $item['variacao_id'] ?></small>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-2">
                                <strong>R$ <?= number_format($item['produto']['preco'], 2, ',', '.') ?></strong>
                            </div>
                            <div class="col-md-2">
                                <span class="badge bg-secondary">Qty: <?= $item['quantidade'] ?></span>
                            </div>
                            <div class="col-md-2">
                                <a href="index.php?action=carrinho&method=remover&key=<?= $key ?>" 
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Remover este item do carrinho?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Resumo do Pedido</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <span>Subtotal:</span>
                        <strong>R$ <?= number_format($subtotal, 2, ',', '.') ?></strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Frete:</span>
                        <strong>R$ <?= number_format($frete, 2, ',', '.') ?></strong>
                    </div>
                    
                    <?php if (isset($_SESSION['cupom'])): ?>
                        <div class="d-flex justify-content-between text-success">
                            <span>Desconto (<?= $_SESSION['cupom']['codigo'] ?>):</span>
                            <strong>-R$ <?= number_format($_SESSION['cupom']['desconto'], 2, ',', '.') ?></strong>
                        </div>
                        <?php $total = $subtotal + $frete - $_SESSION['cupom']['desconto']; ?>
                    <?php else: ?>
                        <?php $total = $subtotal + $frete; ?>
                    <?php endif; ?>
                    
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Total:</strong>
                        <strong class="text-success">R$ <?= number_format($total, 2, ',', '.') ?></strong>
                    </div>

                    <hr>

                    <!-- Aplicar Cupom -->
                    <?php if (!isset($_SESSION['cupom'])): ?>
                        <form method="POST" action="index.php?action=carrinho&method=aplicarCupom">
                            <div class="mb-3">
                                <label for="cupom_codigo" class="form-label">Cupom de Desconto:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="cupom_codigo" 
                                           placeholder="Digite o cupom">
                                    <button type="submit" class="btn btn-outline-secondary">
                                        <i class="fas fa-tag"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    <?php endif; ?>

                    <a href="index.php?action=carrinho&method=checkout" class="btn btn-success w-100 btn-lg">
                        <i class="fas fa-credit-card"></i> Finalizar Compra
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php include 'views/layout/footer.php'; ?>
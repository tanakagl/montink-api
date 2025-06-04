<?php include 'views/layout/header.php'; ?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h1><?= htmlspecialchars($produto['nome']) ?></h1>
                <p class="text-muted"><?= htmlspecialchars($produto['descricao']) ?></p>
                <h3 class="text-success">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></h3>
                
                <p><strong>Estoque disponível:</strong> <?= $estoque ? $estoque['quantidade'] : 0 ?> unidades</p>
                
                <?php if (!empty($variacoes)): ?>
                    <h5>Variações disponíveis:</h5>
                    <ul class="list-group mb-3">
                        <?php foreach ($variacoes as $variacao): ?>
                            <?php $estoqueVariacao = $this->estoque->getByProduto($produto['id'], $variacao['id']); ?>
                            <li class="list-group-item d-flex justify-content-between">
                                <span><?= htmlspecialchars($variacao['nome']) ?></span>
                                <span>
                                    <?php if ($variacao['valor_adicional'] > 0): ?>
                                        +R$ <?= number_format($variacao['valor_adicional'], 2, ',', '.') ?>
                                    <?php endif; ?>
                                    (<?= $estoqueVariacao ? $estoqueVariacao['quantidade'] : 0 ?> em estoque)
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <form method="POST" action="index.php?action=carrinho&method=adicionar">
                    <input type="hidden" name="produto_id" value="<?= $produto['id'] ?>">
                    
                    <?php if (!empty($variacoes)): ?>
                        <div class="mb-3">
                            <label for="variacao_id" class="form-label">Escolha uma variação:</label>
                            <select class="form-select" name="variacao_id" id="variacao_id">
                                <option value="">Produto padrão</option>
                                <?php foreach ($variacoes as $variacao): ?>
                                    <option value="<?= $variacao['id'] ?>">
                                        <?= htmlspecialchars($variacao['nome']) ?>
                                        <?php if ($variacao['valor_adicional'] > 0): ?>
                                            (+R$ <?= number_format($variacao['valor_adicional'], 2, ',', '.') ?>)
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="quantidade" class="form-label">Quantidade:</label>
                        <input type="number" class="form-control" name="quantidade" id="quantidade" 
                               min="1" max="<?= $estoque ? $estoque['quantidade'] : 0 ?>" value="1" required>
                    </div>

                    <?php if ($estoque && $estoque['quantidade'] > 0): ?>
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-shopping-cart"></i> Comprar
                        </button>
                    <?php else: ?>
                        <button type="button" class="btn btn-secondary btn-lg" disabled>
                            <i class="fas fa-times"></i> Sem Estoque
                        </button>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Ações</h5>
            </div>
            <div class="card-body">
                <a href="index.php?action=produtos&method=edit&id=<?= $produto['id'] ?>" 
                   class="btn btn-outline-primary mb-2 w-100">
                    <i class="fas fa-edit"></i> Editar Produto
                </a>
                <a href="index.php?action=produtos" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-arrow-left"></i> Voltar para Lista
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>
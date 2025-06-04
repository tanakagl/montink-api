<?php include 'views/layout/header.php'; ?>

<div class="row">
    <div class="col-md-8">
        <h1><i class="fas fa-edit"></i> Editar Produto</h1>

        <form method="POST">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome do Produto *</label>
                        <input type="text" class="form-control" id="nome" name="nome" 
                               value="<?= htmlspecialchars($produto['nome']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="preco" class="form-label">Preço *</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" class="form-control" id="preco" name="preco" 
                                   step="0.01" min="0" value="<?= $produto['preco'] ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="3"><?= htmlspecialchars($produto['descricao']) ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="estoque" class="form-label">Estoque *</label>
                        <input type="number" class="form-control" id="estoque" name="estoque" 
                               min="0" value="<?= $estoque ? $estoque['quantidade'] : 0 ?>" required>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Salvar Alterações
                </button>
                <a href="index.php?action=produtos&method=show&id=<?= $produto['id'] ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        </form>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>
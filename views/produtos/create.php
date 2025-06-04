<?php include 'views/layout/header.php'; ?>

<div class="row">
    <div class="col-md-8">
        <h1><i class="fas fa-plus"></i> Novo Produto</h1>

        <form method="POST" id="produtoForm">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome do Produto *</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
                    </div>

                    <div class="mb-3">
                        <label for="preco" class="form-label">Preço *</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" class="form-control" id="preco" name="preco" 
                                   step="0.01" min="0" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="estoque" class="form-label">Estoque Inicial *</label>
                        <input type="number" class="form-control" id="estoque" name="estoque" 
                               min="0" value="0" required>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5>Variações do Produto</h5>
                </div>
                <div class="card-body">
                    <div id="variacoes-container">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            As variações são opcionais. Deixe em branco se o produto não possui variações.
                        </div>
                    </div>
                    
                    <button type="button" class="btn btn-outline-secondary" onclick="adicionarVariacao()">
                        <i class="fas fa-plus"></i> Adicionar Variação
                    </button>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Salvar Produto
                </button>
                <a href="index.php?action=produtos" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
let variacaoCount = 0;

function adicionarVariacao() {
    const container = document.getElementById('variacoes-container');
    const div = document.createElement('div');
    div.className = 'row mb-3 variacao-item';
    div.innerHTML = `
        <div class="col-md-4">
            <input type="text" class="form-control" name="variacoes[${variacaoCount}][nome]" 
                   placeholder="Nome da variação">
        </div>
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text">R$</span>
                <input type="number" class="form-control" name="variacoes[${variacaoCount}][valor_adicional]" 
                       placeholder="0.00" step="0.01">
            </div>
        </div>
        <div class="col-md-3">
            <input type="number" class="form-control" name="variacoes[${variacaoCount}][estoque]" 
                   placeholder="Estoque" min="0" value="0">
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger" onclick="removerVariacao(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    container.appendChild(div);
    variacaoCount++;
}

function removerVariacao(button) {
    button.closest('.variacao-item').remove();
}
</script>

<?php include 'views/layout/footer.php'; ?>
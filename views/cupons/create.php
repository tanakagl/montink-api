<?php include 'views/layout/header.php'; ?>

<div class="row">
    <div class="col-md-6">
        <h1><i class="fas fa-plus"></i> Novo Cupom</h1>

        <form method="POST">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="codigo" class="form-label">Código do Cupom *</label>
                        <input type="text" class="form-control" id="codigo" name="codigo" 
                               style="text-transform: uppercase;" maxlength="20" required>
                        <div class="form-text">Use apenas letras e números. Exemplo: DESC10, PROMO50</div>
                    </div>

                    <div class="mb-3">
                        <label for="desconto_tipo" class="form-label">Tipo de Desconto *</label>
                        <select class="form-select" id="desconto_tipo" name="desconto_tipo" required>
                            <option value="">Selecione...</option>
                            <option value="fixo">Valor Fixo (R$)</option>
                            <option value="percentual">Percentual (%)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="desconto_valor" class="form-label">Valor do Desconto *</label>
                        <input type="number" class="form-control" id="desconto_valor" name="desconto_valor" 
                               step="0.01" min="0" required>
                        <div class="form-text" id="desconto-help">Digite o valor do desconto</div>
                    </div>

                    <div class="mb-3">
                        <label for="valor_minimo" class="form-label">Valor Mínimo da Compra</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" class="form-control" id="valor_minimo" name="valor_minimo" 
                                   step="0.01" min="0" value="0">
                        </div>
                        <div class="form-text">Valor mínimo para aplicar o cupom (0 = sem limite)</div>
                    </div>

                    <div class="mb-3">
                        <label for="data_validade" class="form-label">Data de Validade *</label>
                        <input type="date" class="form-control" id="data_validade" name="data_validade" 
                               min="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Criar Cupom
                </button>
                <a href="index.php?action=cupons" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('desconto_tipo').addEventListener('change', function() {
    const helpText = document.getElementById('desconto-help');
    const input = document.getElementById('desconto_valor');
    
    if (this.value === 'fixo') {
        helpText.textContent = 'Digite o valor em reais (ex: 10.50)';
        input.placeholder = '0.00';
    } else if (this.value === 'percentual') {
        helpText.textContent = 'Digite o percentual (ex: 15 para 15%)';
        input.placeholder = '0';
        input.max = '100';
    } else {
        helpText.textContent = 'Digite o valor do desconto';
        input.placeholder = '';
        input.removeAttribute('max');
    }
});

document.getElementById('codigo').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
});
</script>

<?php include 'views/layout/footer.php'; ?>

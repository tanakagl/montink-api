<?php include 'views/layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-tags"></i> Cupons de Desconto</h1>
    <a href="index.php?action=cupons&method=create" class="btn btn-primary">
        <i class="fas fa-plus"></i> Novo Cupom
    </a>
</div>

<?php if (empty($cupons)): ?>
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> Nenhum cupom cadastrado ainda.
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Tipo</th>
                    <th>Desconto</th>
                    <th>Valor Mínimo</th>
                    <th>Validade</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cupons as $cupom): ?>
                    <tr>
                        <td><code><?= htmlspecialchars($cupom['codigo']) ?></code></td>
                        <td>
                            <span class="badge bg-<?= $cupom['desconto_tipo'] === 'fixo' ? 'success' : 'primary' ?>">
                                <?= ucfirst($cupom['desconto_tipo']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($cupom['desconto_tipo'] === 'fixo'): ?>
                                R$ <?= number_format($cupom['desconto_valor'], 2, ',', '.') ?>
                            <?php else: ?>
                                <?= $cupom['desconto_valor'] ?>%
                            <?php endif; ?>
                        </td>
                        <td>R$ <?= number_format($cupom['valor_minimo'], 2, ',', '.') ?></td>
                        <td><?= date('d/m/Y', strtotime($cupom['data_validade'])) ?></td>
                        <td>
                            <?php if ($cupom['ativo'] && $cupom['data_validade'] >= date('Y-m-d')): ?>
                                <span class="badge bg-success">Ativo</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inativo</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" onclick="copiarCupom('<?= $cupom['codigo'] ?>')">
                                <i class="fas fa-copy"></i> Copiar
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<script>
function copiarCupom(codigo) {
    navigator.clipboard.writeText(codigo).then(function() {
        alert('Código do cupom copiado: ' + codigo);
    });
}
</script>

<?php include 'views/layout/footer.php'; ?>
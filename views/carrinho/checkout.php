<?php include 'views/layout/header.php'; ?>

<h1><i class="fas fa-credit-card"></i> Finalizar Compra</h1>

<div class="row">
    <div class="col-md-8">
        <form method="POST" id="checkoutForm">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Dados Pessoais</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome Completo *</label>
                                <input type="text" class="form-control" id="nome" name="nome" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5>Endereço de Entrega</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="cep" class="form-label">CEP *</label>
                                <input type="text" class="form-control" id="cep" name="cep" 
                                       placeholder="00000-000" maxlength="9" required>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="endereco" class="form-label">Endereço</label>
                                <input type="text" class="form-control" id="endereco" name="endereco" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="numero" class="form-label">Número *</label>
                                <input type="text" class="form-control" id="numero" name="numero" required>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="mb-3">
                                <label for="complemento" class="form-label">Complemento</label>
                                <input type="text" class="form-control" id="complemento" name="complemento">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="bairro" class="form-label">Bairro</label>
                                <input type="text" class="form-control" id="bairro" name="bairro" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="cidade" class="form-label">Cidade</label>
                                <input type="text" class="form-control" id="cidade" name="cidade" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="uf" class="form-label">UF</label>
                                <input type="text" class="form-control" id="uf" name="uf" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success btn-lg">
                <i class="fas fa-check"></i> Confirmar Pedido
            </button>
            <a href="index.php?action=carrinho" class="btn btn-secondary btn-lg">
                <i class="fas fa-arrow-left"></i> Voltar ao Carrinho
            </a>
        </form>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Resumo do Pedido</h5>
            </div>
            <div class="card-body">
                <?php 
                $subtotal = 0;
                foreach ($_SESSION['carrinho'] as $item) {
                    $subtotal += $item['produto']['preco'] * $item['quantidade'];
                }
                $frete = (new Pedido())->calcularFrete($subtotal);
                $desconto = isset($_SESSION['cupom']) ? $_SESSION['cupom']['desconto'] : 0;
                $total = $subtotal + $frete - $desconto;
                ?>

                <div class="d-flex justify-content-between">
                    <span>Subtotal:</span>
                    <strong>R$ <?= number_format($subtotal, 2, ',', '.') ?></strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Frete:</span>
                    <strong>R$ <?= number_format($frete, 2, ',', '.') ?></strong>
                </div>
                
                <?php if ($desconto > 0): ?>
                    <div class="d-flex justify-content-between text-success">
                        <span>Desconto:</span>
                        <strong>-R$ <?= number_format($desconto, 2, ',', '.') ?></strong>
                    </div>
                <?php endif; ?>
                
                <hr>
                <div class="d-flex justify-content-between">
                    <strong>Total:</strong>
                    <strong class="text-success">R$ <?= number_format($total, 2, ',', '.') ?></strong>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('cep').addEventListener('blur', function() {
    const cep = this.value.replace(/\D/g, '');
    
    if (cep.length === 8) {
        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(response => response.json())
            .then(data => {
                if (!data.erro) {
                    document.getElementById('endereco').value = data.logradouro;
                    document.getElementById('bairro').value = data.bairro;
                    document.getElementById('cidade').value = data.localidade;
                    document.getElementById('uf').value = data.uf;
                } else {
                    alert('CEP não encontrado!');
                }
            })
            .catch(error => {
                console.error('Erro ao buscar CEP:', error);
                alert('Erro ao buscar CEP. Tente novamente.');
            });
    }
});

// Máscara para CEP
document.getElementById('cep').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    value = value.replace(/^(\d{5})(\d)/, '$1-$2');
    e.target.value = value;
});
</script>

<?php include 'views/layout/footer.php'; ?>
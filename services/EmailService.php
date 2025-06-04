<?php
class EmailService {
    
    public function enviarConfirmacaoPedido($email, $pedidoId, $dadosPedido) {
        $assunto = "Confirmação de Pedido #$pedidoId - Montink";
        
        $mensagem = "
        <html>
        <head>
            <title>Confirmação de Pedido</title>
        </head>
        <body>
            <h2>Pedido Confirmado!</h2>
            <p>Olá {$dadosPedido['cliente_nome']},</p>
            <p>Seu pedido #{$pedidoId} foi confirmado com sucesso!</p>
            
            <h3>Detalhes do Pedido:</h3>
            <ul>
                <li>Subtotal: R$ " . number_format($dadosPedido['subtotal'], 2, ',', '.') . "</li>
                <li>Frete: R$ " . number_format($dadosPedido['frete'], 2, ',', '.') . "</li>
                <li>Desconto: R$ " . number_format($dadosPedido['desconto'], 2, ',', '.') . "</li>
                <li><strong>Total: R$ " . number_format($dadosPedido['total'], 2, ',', '.') . "</strong></li>
            </ul>
            
            <h3>Endereço de Entrega:</h3>
            <p>{$dadosPedido['cliente_endereco']}</p>
            <p>CEP: {$dadosPedido['cliente_cep']}</p>
            
            <p>Obrigado por escolher a Montink!</p>
        </body>
        </html>
        ";

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: noreply@montink.com' . "\r\n";

        // Em produção, use uma biblioteca como PHPMailer ou SwiftMailer
        return mail($email, $assunto, $mensagem, $headers);
    }
}
?>
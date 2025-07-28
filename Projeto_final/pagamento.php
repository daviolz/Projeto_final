<?php
include_once 'php/conexao.php';
session_start();
$carrinho = isset($_SESSION['carrinho']) ? $_SESSION['carrinho'] : [];
$total = 0.0;
foreach ($carrinho as $produto) {
    $subtotal = $produto['preco'] * $produto['qte'];
    $total += $subtotal;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprar</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/pagamento.css">
    
</head>
<body>
    <main>
        <h2>Pagamento</h2>
        <div class="container">
            <div class="pedido" style="margin: 0 auto;">
                    <form action="" method="post"> 
                    <div class="input-container">
                        <label class="pagamento-opcao">
                            <input type="radio" name="pagamento" value="1" required>
                            <img src="../img/cartao.png" alt="Cartão de Crédito">
                            <span>Cartão de Crédito</span>
                        </label>
                        <label class="pagamento-opcao">
                            <input type="radio" name="pagamento" value="2">
                            <img src="../img/cartao.png" alt="Cartão de Débito">
                            <span>Cartão de Débito</span>
                        </label>
                        <label class="pagamento-opcao">
                            <input type="radio" name="pagamento" value="3">
                            <img src="../img/pix.png" alt="Pix">
                            <span>Pix</span>
                        </label>
                        <label class="pagamento-opcao">
                            <input type="radio" name="pagamento" value="4">
                            <img src="../img/dinheriro.png" alt="Dinheiro">
                            <span>Dinheiro</span>
                        </label>
                    </div>
                    <div class="botaopag">
                    <button type="submit" class="button_atualiza">Fechar pedido</button>
                        </div>
            </div>
    </main>
    <footer>
         <img src="../img/Logo.png" alt="logo" class="logo">
    <div class="op">
      <div class="total">
        <h2>Total: R$ <span id="footer-total"><?php echo number_format($total, 2, ',', '.'); ?></span></h2>
      </div>
        <div class = "baixo">
        <button class='op-btn cancelar' onclick="window.location.href='php/deletar_conta.php'">Cancelar Pedido</button>
        <button class='op-btn fazer' onclick="window.location.href='php/carrinho.html'">Fazer pedido</button>
        </div>
    </div>
    </footer>
</body>
</html>
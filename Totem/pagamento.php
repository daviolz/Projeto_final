<?php
include_once 'php/conexao.php';
session_start();
if (!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
    header("Location: carrinho.php");
    exit();
}

if (!isset($_SESSION['cod_comanda'])) {
    header("Location: index.php");
    exit();
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
    <link rel="icon" type="image/png" href="../img/Comes-_1_.ico">
    <script src="js/inatividade.js"></script>

</head>

<body>
    <main class="main-pagamento">
        <div class="voltar">
            <a href=".php"></a>
        </div>

        <div class="titulo-pagamento">
            <h2>Pagamento</h2>
        </div>
        <div class="container">
            <div class="opcoes-pagamento">
                <form class="pagamento-form" action="php/registrar_pedidos.php" method="post">
                    <div class="opcao">
                        <input type="radio" name="pagamento" id="credito" value="Crédito" required>
                        <label for="credito"><img src="../img/cartao.png" alt="Cartão de Crédito">Crédito</label>
                    </div>
                    <div class="opcao">
                        <input type="radio" name="pagamento" id="debito" value="Débito" required>
                        <label for="debito"><img src="../img/cartao.png" alt="Cartão de Débito">Débito</label>
                    </div>
                    <div class="opcao">
                        <input type="radio" name="pagamento" id="pix" value="Pix" required>
                        <label for="pix"><img src="../img/pix.png" alt="Pix">Pix</label>
                    </div>
                    <div class="opcao">
                        <input type="radio" name="pagamento" id="dinheiro" value="Dinheiro" required>
                        <label for="dinheiro"><img src="../img/dinheriro.png" alt="Dinheiro">Dinheiro</label>
                    </div>

                </form>
            </div>
        </div>
    </main>
    <footer>
        <img src="../img/Logo.png" alt="logo" class="logo">
        <div class="op">
            <div class="total">
                <h2>Total: R$ <span id="footer-total"><?php echo number_format($_SESSION['carrinho_total'], 2, ',', '.'); ?></span></h2>
            </div>
            <div class="baixo">
                <button class='op-btn cancelar' onclick="window.location.href='carrinho.php'">Voltar</button>
                <button class='op-btn fazer' disabled>Fazer pedido</button>
            </div>
        </div>
    </footer>

    <script>
        const fazerBtn = document.querySelector('.fazer');
        const opcoes = document.getElementsByName('pagamento');

        opcoes.forEach(opcao => {
            opcao.addEventListener('change', () => {
                fazerBtn.disabled = false;
            });
        });


        if (fazerBtn) {
            fazerBtn.addEventListener('click', function() {
                // Procura o formulário de adicionar produto e envia
                const form = document.querySelector('.pagamento-form');
                if (form) {
                    form.submit();
                }
            });
        }
    </script>


</body>

</html>
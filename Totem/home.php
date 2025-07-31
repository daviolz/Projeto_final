<?php
include_once 'php/conexao.php';
session_start();

if (!isset($_SESSION['cod_comanda'])) {
    header("Location: index.php");
    exit();
}

// Inicializa o carrinho se não existir
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = []; // array de itens: cada item é ['cod_variacao'=>..., 'qte'=>..., 'preco'=>...]
    $_SESSION['carrinho_total'] = 0.0;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/inatividade.js"></script>
</head>

<body>
    <nav>
        <form action="escolher.php" method="post">
            <ul>
                <li>
                    <button type="submit" name="tipo_produto" value="salgado" class='btn-nav'>
                        <img src="../img/nav_salgados.png" alt="salgados"><br>Salgados
                    </button>
                </li>
                <li>
                    <button type="submit" name="tipo_produto" value="bebida" class='btn-nav'>
                        <img src="../img/nav_bebidas.png" alt="bebidas"><br>Bebidas
                    </button>
                </li>
                <li>
                    <button type="submit" name="tipo_produto" value="doce" class='btn-nav'>
                        <img src="../img/nav_doces.png" alt="doces"><br>Doces
                    </button>
                </li>
                <li>
                    <button type="submit" name="tipo_produto" value="combo" class='btn-nav'>
                        <img src="../img/nav_combos.png" alt="combos"><br>Combos
                    </button>
                </li>
            </ul>
        </form>
    </nav>
    <main>
        <div class="bemvindo">
            <p>Bem vindo à nossa loja de salgados!
                <br>Escolha o que deseja comprar.
            </p>
        </div>
    </main>
    <footer>
        <img src="../img/Logo.png" alt="logo" class="logo">
        <div class="op">
            <div class="total">
                <h2>Total: R$ <?php echo number_format($_SESSION['carrinho_total'], 2, ',', '.'); ?></h2>
            </div>
            <div class="baixo">
                <button class='op-btn cancelar' onclick="window.location.href='php/deletar_comanda.php'">Cancelar Pedido</button>
                <button class='op-btn fazer' onclick="window.location.href='carrinho.php'">Carrinho</button>
            </div>
        </div>
    </footer>
</body>

</html>
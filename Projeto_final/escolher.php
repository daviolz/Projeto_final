<?php
    include_once 'php/conexao.php';
    session_start();

    if (isset($_POST['tipo_produto'])) {
        $_SESSION['tipo_produto'] = $_POST['tipo_produto'];
    } elseif (isset($_GET['tipo_produto'])) {
        $_SESSION['tipo_produto'] = $_GET['tipo_produto'];
    } else {
        // Redirecionar para a página inicial se o tipo de produto não estiver definido
        header("Location: index.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escolher Salgado</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/escolher-salgado.css">
</head>

<body>
    <nav>
        <form action="escolher.php" method="POST">
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
        <br>
        <div class="div-conteudo">
            <?php
            $sql = "SELECT p.*, MIN(v.Preco) as Preco_min 
            FROM Produto p 
            LEFT JOIN Produto_Variacao v ON v.Cod_produto = p.Cod_produto 
            WHERE p.Tipo_produto = '$_SESSION[tipo_produto]'
            GROUP BY p.Cod_produto";
            $resultado = mysqli_query($conexao, $sql);
            while ($linha = mysqli_fetch_assoc($resultado)) {
                // Consulta para contar as variações desse produto
                $cod_produto = $linha['Cod_produto'];
                $sql_var = "SELECT COUNT(*) as total, MIN(Preco) as preco_min FROM Produto_Variacao WHERE Cod_produto = '$cod_produto'";
                $res_var = mysqli_query($conexao, $sql_var);
                $dados_var = mysqli_fetch_assoc($res_var);
                $total_var = $dados_var['total'];
                $preco_min = $dados_var['preco_min'];
                echo "<form action='adicionar_produto.php' method='POST'>";
                echo "<div class='div-produto' onclick='this.closest(\"form\").submit();'>";
                echo "<img class='imagem-produtos' src='../" . $linha['Imagem_produto'] . "' alt='" . $linha['Nome_produto'] . "'>";
                echo "<p>" . $linha['Nome_produto'] . "</p>";
                if ($total_var > 1) {
                    echo "<p>A partir de R$ " . number_format($preco_min, 2, ',', '.') . "</p>";
                } else {
                    echo "<p>R$ " . number_format($preco_min, 2, ',', '.') . "</p>";
                }
                echo "<input type='hidden' name='cod_produto' value='" . $linha['Cod_produto'] . "'>";
                echo "</div>";
                echo "</form>";
            }
            ?>
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
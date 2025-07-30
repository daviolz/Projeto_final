<?php
include_once 'php/conexao.php';
session_start();

// Se não tiver um código de comanda, redireciona para o index
if (!isset($_SESSION['cod_comanda'])) {
    header("Location: index.php");
    exit();
}

// Inserir produto no carrinho
// Inicializa o carrinho se não existir
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
    $_SESSION['carrinho_total'] = 0.0;
}

// Processa o POST do formulário de adicionar ao carrinho
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['qte'])) {
    $cod_produto = $_POST['cod_produto'];
    $nome_produto = $_POST['nome_produto'];

    // Pega a imagem do produto
    $query_img = "SELECT Imagem_produto FROM Produto WHERE Cod_produto = '$cod_produto'";
    $res_img = mysqli_query($conexao, $query_img);
    $row_img = mysqli_fetch_assoc($res_img);
    $imagem_produto = $row_img ? $row_img['Imagem_produto'] : '';


    // Pega as variações e quantidades
    $variacoes = $_POST['nome_variacao'];
    $quantidades = $_POST['qte'];
    $precos = $_POST['preco_variacao'];
    $adicionados = false;

    // Adiciona cada variação ao carrinho
    foreach ($variacoes as $cod_variacao => $nome_variacao) {
        $qte = intval($quantidades[$cod_variacao]);
        if ($qte > 0) {
            // Verifica se a variação já está no carrinho
            $encontrado = false;
            foreach ($_SESSION['carrinho'] as &$item) {
                if ($item['cod_variacao'] == $cod_variacao) {
                    $item['qte'] += $qte; // Atualiza a quantidade
                    $encontrado = true;
                    break;
                }
            }
            if (!$encontrado) {
                // Adiciona nova variação ao carrinho
                $_SESSION['carrinho'][] = [
                    'cod_produto' => $cod_produto,
                    'nome_produto' => $nome_produto,
                    'cod_variacao' => $cod_variacao,
                    'nome_variacao' => $nome_variacao,
                    'qte' => $qte,
                    'preco' => $precos[$cod_variacao],
                    'imagem_produto' => $imagem_produto
                ];
            }
            $_SESSION['carrinho_total'] += $precos[$cod_variacao] * $qte;
            $adicionados = true;
        }
    }


    // Volta para a página de escolha de produtos
    header("Location: escolher.php?tipo_produto=" . urlencode($_SESSION['tipo_produto']));
    exit();
}

// Recupera dados do produto para exibição
if (isset($_POST['cod_produto'])) {
    $cod_produto = $_POST['cod_produto'];

    // Pega todas as informações do produto 
    $query = "SELECT * FROM Produto WHERE cod_produto = '$cod_produto'";
    $resultado = mysqli_query($conexao, $query);
    if ($resultado) {
        $produto = mysqli_fetch_assoc($resultado);
    }

    // Caso a variavel $cod_produto já esteja definida.
} elseif (isset($cod_produto)) {

    // Já definidos pelo POST anterior
    $query = "SELECT * FROM Produto WHERE cod_produto = '$cod_produto'";
    $resultado = mysqli_query($conexao, $query);
    if ($resultado) {
        $produto = mysqli_fetch_assoc($resultado);
    }

    // Caso contrário, redireciona para a página inicial
} else {
    header("Location: index.php");
    exit();
}
?>

<!-- Código da Página -->
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $produto['Nome_produto']; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/inatividade.js"></script>
</head>

<body>

    <!-- Nav Bar com as opções -->
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

    <!-- Exibição da página -->
    <main>
        <div class="conteudo-produto">

            <div class="produto">
                <img src="<?php echo '../' . $produto['Imagem_produto']; ?>" alt="<?php echo $produto['Nome_produto']; ?>" class="produto-img">
                <h1><?php echo $produto['Nome_produto']; ?></h1>
                <?php

                // Pega o preço do produto
                $query_sabores = "SELECT Preco FROM Produto_Variacao WHERE Cod_produto = '$cod_produto'";
                $resultado_sabores = mysqli_query($conexao, $query_sabores);
                if ($resultado_sabores && mysqli_num_rows($resultado_sabores) == 1) {
                    $row_preco = mysqli_fetch_assoc($resultado_sabores);

                    // Exibe o preço abaixo do nome do produto apenas se houver uma variação
                    echo "<h2 style='margin-top: 0; color: #555;'>R$ " . number_format($row_preco['Preco'], 2, ',', '.') . "</h2>";
                }
                ?>

                <p class="descricao"><?php echo $produto['Descricao_produto']; ?></p>
                <hr>
            </div>

            <?php
            // Pega os sabores disponíveis para o produto
            $query_sabores = "SELECT * FROM Produto_Variacao WHERE Cod_produto = '$cod_produto'";
            $resultado_sabores = mysqli_query($conexao, $query_sabores);


            if ($resultado_sabores) {


                $num_variacoes = mysqli_num_rows($resultado_sabores);

                // Exibição caso o produto tenha apenas uma variação
                if ($num_variacoes == 1) {

                    $linha_sabor = mysqli_fetch_assoc($resultado_sabores);
                    $cod_variacao = $linha_sabor['Cod_variacao'];
                    $nome_variacao = $linha_sabor['Nome_variacao'];
                    $preco_variacao = $linha_sabor['Preco'];
                    echo "<div class='adicionar'>";
                    echo "<form action='' method='POST'>";

                    echo "<input type='hidden' name='cod_produto' value='" . $cod_produto . "'>";
                    echo "<input type='hidden' name='nome_produto' value='" . $produto['Nome_produto'] . "'>";
                    echo "<input type ='hidden' name='imagem_produto' value='" . $produto['Imagem_produto'] . "'>";
                    echo "<input type='hidden' name='nome_variacao[$cod_variacao]' value='" . $nome_variacao . "'>";
                    echo "<input type='hidden' name='preco_variacao[$cod_variacao]' value='" . $preco_variacao . "'>";

                    echo "<div class='opcao-produto-unica'>";

                    echo "<div class='qte'>";
                    echo "<span class='qte-menos'>-</span>";
                    echo "<input type='number' class='qte-input' name='qte[$cod_variacao]' value='01' min='0' required>";
                    echo "<span class='qte-mais'>+</span>";
                    echo "</div>";
                    echo "</div>";
                    echo "</form>";
                    echo "</div>";
                } elseif ($num_variacoes > 1) {
                    // Exibição caso o produto tenha múltiplas variações

                    echo "<div class='linha-separadora'></div>";
                    echo "<div class='adicionar'>";
                    echo "<h1>Sabores Disponíveis</h1>";
                    echo "<form action='' method='POST'>";
                    echo "<input type='hidden' name='cod_produto' value='" . $cod_produto . "'>";
                    echo "<input type='hidden' name='nome_produto' value='" . $produto['Nome_produto'] . "'>";

                    // Variavel para deixar o primeiro sabor com quantidade 1
                    $primeiro = true;

                    // Essa função reseta o ponteiro do resultado para o início,
                    // permitindo que possamos percorrer novamente os resultados
                    mysqli_data_seek($resultado_sabores, 0);


                    while ($linha_sabor = mysqli_fetch_assoc($resultado_sabores)) {
                        $cod_variacao = $linha_sabor['Cod_variacao'];
                        $nome_variacao = $linha_sabor['Nome_variacao'];
                        $preco_variacao = $linha_sabor['Preco'];

                        // Define o valor inicial como 1 para o primeiro sabor, e 0 para os demais
                        // Isso garante que o primeiro sabor tenha quantidade 1, e os demais 0

                        $valor = $primeiro ? 1 : 0;
                        echo "<div class='opcao-produto'>";
                        echo "<div class='nome-opcao'><span>$nome_variacao</span><br><span>R$ " . number_format($preco_variacao, 2, ',', '.') . "</span></div>";
                        echo "<div class='qte'>";
                        echo "<span class='qte-menos'>-</span>";
                        echo "<input type='number' class='qte-input' name='qte[$cod_variacao]' value='" . str_pad($valor, 2, '0', STR_PAD_LEFT) . "' min='0' required>";
                        echo "<span class='qte-mais'>+</span>";
                        echo "</div>";
                        echo "<input type='hidden' name='nome_variacao[$cod_variacao]' value='" . htmlspecialchars($nome_variacao, ENT_QUOTES) . "'>";
                        echo "<input type='hidden' name='preco_variacao[$cod_variacao]' value='" . $preco_variacao . "'>";
                        echo "</div>";

                        // Define que o primeiro sabor já foi exibido
                        $primeiro = false;
                    }

                    echo "</form>";
                    echo "</div>";
                }
            }
            ?>
    </main>
    <footer>
        <img src="../img/Logo.png" alt="logo" class="logo">
        <div class="op">
            <div class="total">
                <h2>Total: R$ <?php echo number_format($_SESSION['carrinho_total'], 2, ',', '.'); ?></h2>
            </div>
            <div class="baixo">
                <button class='op-btn cancelar' onclick="window.location.href='escolher.php?tipo_produto=<?php echo urlencode($_SESSION['tipo_produto']); ?>'">Voltar</button>

                <button class='op-btn fazer' id="adicionar-footer-btn">Adicionar ao Carrinho</button>
            </div>
        </div>
    </footer>

    <script>
        // Ativa o controle de quantidade também para .opcao-produto-unica
        document.querySelectorAll('.opcao-produto, .opcao-produto-unica').forEach(function(opcao) {
            const input = opcao.querySelector('.qte-input');
            const mais = opcao.querySelector('.qte-mais');
            const menos = opcao.querySelector('.qte-menos');

            mais.addEventListener('click', function() {
                let valor = parseInt(input.value, 10);
                valor++;
                input.value = valor < 10 ? '0' + valor : valor;
            });

            menos.addEventListener('click', function() {
                let valor = parseInt(input.value, 10);
                if (valor > 0) {
                    valor--;
                    input.value = valor < 10 ? '0' + valor : valor;
                }
            });
        });

        // script para submeter o formulário ao clicar no botão do footer
        const adicionarFooterBtn = document.getElementById('adicionar-footer-btn');
        if (adicionarFooterBtn) {
            adicionarFooterBtn.addEventListener('click', function() {
                // Procura o formulário de adicionar produto e envia
                const form = document.querySelector('.adicionar form');
                if (form) {
                    form.submit();
                }
            });
        }
    </script>
</body>

</html>
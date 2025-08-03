<?php
session_start();

if (!isset($_SESSION['cod_comanda'])) {
    header("Location: ../index.php");
    exit();
}

$forma_pagamento = isset($_POST['pagamento']) ? $_POST['pagamento'] : null;
if ($forma_pagamento === null) {
    header('Location: ../pagamento.php');
    echo "<script>alert('Selecione uma forma de pagamento.');</script>";
    exit;
} else {
    foreach ($_SESSION['carrinho'] as $item) {
        $cod_variacao = $item['cod_variacao'];
        $qte = $item['qte'];
        $preco = $item['preco'];
        $valor_pedido = $qte * $preco;

        include_once 'conexao.php';

        $sql = "INSERT INTO Pedido (Cod_comanda, Cod_variacao, Qte, Valor_pedido) VALUES (" . $_SESSION['cod_comanda'] . ", $cod_variacao, $qte, $valor_pedido);";
        $result = mysqli_query($conexao, $sql);

        $atualizar_comanda = "UPDATE Comanda SET Valor_total = Valor_total + $valor_pedido WHERE Cod_comanda = $_SESSION[cod_comanda]";
        $result_comanda = mysqli_query($conexao, $atualizar_comanda);
    }

    $atualizar_comanda = "UPDATE Comanda SET Pagamento = 'paga', Forma_pagamento = '$forma_pagamento', Status = 'preparando' WHERE Cod_comanda = $_SESSION[cod_comanda]";
    $result_comanda = mysqli_query($conexao, $atualizar_comanda);
    header('Location: ../processando.php');
}

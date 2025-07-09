<?php
    session_start();
    include_once 'conexao.php';

    // Cria uma nova conta
    $sql = "DELETE FROM Comanda WHERE cod_comanda = " . $_SESSION['cod_comanda'];
    if ($conexao->query($sql) === TRUE) {
        // Limpa a sessão e redireciona para a página inicial
        unset($_SESSION['cod_comanda']);
        unset($_SESSION['carrinho']);
        unset($_SESSION['carrinho_total']);
        header("Location: ../index.php");
        exit();
    } else {
        echo "Erro ao cancelar pedido: " . $conexao->error;
    }
    $conn->close();
?>
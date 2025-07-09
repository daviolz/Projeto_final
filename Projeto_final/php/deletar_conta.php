<?php
    session_start();
    include_once 'conexao.php';

    // Cria uma nova conta
    $sql = "DELETE FROM Conta WHERE cod_conta = " . $_SESSION['cod_conta'];
    if ($conexao->query($sql) === TRUE) {
        // Limpa a sessão e redireciona para a página inicial
        unset($_SESSION['cod_conta']);
        header("Location: ../index.php");
        exit();
    } else {
        echo "Erro ao cancelar pedido: " . $conexao->error;
    }

    $conn->close();
?>
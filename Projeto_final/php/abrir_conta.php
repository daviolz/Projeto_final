<?php
    session_start();
    include_once 'conexao.php';

    // Cria uma nova conta
    $sql = "INSERT INTO Conta (Valor_total) VALUES (0.00)";
    if ($conexao->query($sql) === TRUE) {
        // Recupera o último id inserido
        $cod_conta = $conexao->insert_id;
        $_SESSION['cod_conta'] = $cod_conta;
        header("Location: ../home.php");
        exit();
    } else {
        echo "Erro ao criar conta: " . $conexao->error;
    }

    $conn->close();
?>
<?php
    session_start();
    include_once 'conexao.php';

    // Cria uma nova conta
    $sql = "INSERT INTO Comanda (Valor_total) VALUES (0.00)";
    if ($conexao->query($sql) === TRUE) {
        // Recupera o último id inserido
        $cod_comanda = $conexao->insert_id;
        $_SESSION['cod_comanda'] = $cod_comanda;
        header("Location: ../home.php");
        exit();
    } else {
        echo "Erro ao criar comanda: " . $conexao->error;
    }

    $conn->close();
?>
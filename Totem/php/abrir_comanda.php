<?php
    session_start();
    include_once 'conexao.php';

    // Verifica se a requisição é POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../index.php");
        exit();
    }

    $hoje = date('Y-m-d');
    $sqlsenha = "SELECT MAX(Senha) as ultima_senha FROM Comanda WHERE DATE(Data_hora) = '$hoje'";
    $senharescente = $conexao->query($sqlsenha);
    $rowsenha = $senharescente->fetch_assoc();
    $novasenha = ($rowsenha['ultima_senha'] ?? 0) + 1;
    
    // Cria uma nova conta
    $sql = "INSERT INTO Comanda (Valor_total, Senha) VALUES (0.00, $novasenha)";
    if ($conexao->query($sql) === TRUE) {
        // Recupera os últimos id e senha inseridos
        $cod_comanda = $conexao->insert_id;
        $_SESSION['cod_comanda'] = $cod_comanda;
        $_SESSION['senha'] = $novasenha; 
        header("Location: ../home.php");
        exit();
    } else {
        echo "Erro ao criar comanda: " . $conexao->error;
    }

    $conn->close();
?>
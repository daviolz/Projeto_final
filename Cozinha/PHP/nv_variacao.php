<?php
include_once("protect.php");

if ($_SESSION['nivel'] != 1) {
    echo "<script>alert('Você não tem permissão para acessar essa página!'); window.location.href='../home.php';</script>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once("conexao.php");


  $cod_produto = $_POST['cod_produto'];
  $nome_variacao = $_POST['nome_variacao']; 
    $preco = $_POST['preco'];
    $query = "INSERT INTO Produto_Variacao (Cod_produto, Nome_variacao, Preco) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("isd", $cod_produto, $nome_variacao, $preco);
    if ($stmt->execute()) {
        echo "<script>alert('Variação cadastrada com sucesso!'); window.location.href='../cadastrar_variacao.php';</script>";
        } else {
        echo "<script>alert('Erro ao cadastrar variação.');</script>";
        }
}
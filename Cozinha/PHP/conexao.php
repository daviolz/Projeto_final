<?php

//Dados do banco de dados do projeto
$server = "localhost";
$user = "root";
$password = "";
$database = "Projeto_final";

//conexão usando o mysqli
$conexao = new mysqli($server, $user, $password, $database);
// Verifica se houve erro na conexão principal
if ($conexao->connect_error) {
    die("Falha na conexão com a DB " .   $conexao->connect_error);
}

// Segunda conexão usando o mysqli
$mysqli = new mysqli("localhost", "root", "", "Projeto_final");
// Verifica se houve erro na segunda conexão
if ($mysqli->connect_errno) {
    die("Falha na conexão: " . $mysqli->connect_error);
}
?>

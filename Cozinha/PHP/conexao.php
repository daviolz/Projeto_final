<?php

$server = "localhost";
$user = "root";
$password = "";
$database = "Projeto_final";

$conexao = new mysqli($server, $user, $password, $database);
if ($conexao->connect_error) {
    die("Falha na conexão com a DB " .   $conexao->connect_error);
}

$mysqli = new mysqli("localhost", "root", "", "Projeto_final");
if ($mysqli->connect_errno) {
    die("Falha na conexão: " . $mysqli->connect_error);
}
?>
o
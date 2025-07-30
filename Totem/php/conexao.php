<?php

$host = 'localhost';
$usuario = 'root';
$senha = '';
$nome_banco = 'Projeto_final';

$conexao = new mysqli($host, $usuario, $senha, $nome_banco);
if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}


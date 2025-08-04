<?php

// Defina o tempo de vida da sessão para 8 horas (28800 segundos)
ini_set('session.gc_maxlifetime', 28800);
session_set_cookie_params(28800);

include_once('conexao.php');

if (!isset($_SESSION))
  session_start();

if (!isset($_SESSION['usuario']) || !is_numeric($_SESSION['usuario'])) {
  header("Location: index.php");
}

if (!isset($_SESSION['cod_usuario'])) {
    // Busca o código do usuário logado pelo login salvo na sessão
    if (isset($_SESSION['login'])) {
        require_once("conexao.php");
        $login = $_SESSION['login'];
        $stmt = $mysqli->prepare("SELECT Cod_usuario FROM Usuario WHERE Login = ?");
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $stmt->bind_result($cod_usuario);
        if ($stmt->fetch()) {
            $_SESSION['cod_usuario'] = $cod_usuario;
        }
        $stmt->close();
    }
}

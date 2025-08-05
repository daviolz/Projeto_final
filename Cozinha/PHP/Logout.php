<?php
// Faz a proteção para que logins não autorizados não entrem
include_once("protect.php");
// Encerra a sessão do usuário
session_destroy();
// Redireciona para a página de login
header("Location: ../index.php");

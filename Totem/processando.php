<?php
include_once 'php/conexao.php';
session_start();

if (!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho']) || !isset($_SESSION['cod_comanda'])) {
  header("Location: index.php");
  exit();
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="icon" type="image/png" href="../img/Comes-_1_.ico">
  <title>Processando...</title>
</head>

<body>
  <div class="processando-container">
    <div class="processando">
      <h1>Processando...</h1>
    </div>
    <div class="spinner"></div>
    <div class="check"><img src="../img/check.png" alt="check" /></div>
  </div>

  <script>
    setTimeout(() => {
      const processando = document.querySelector(".processando");
      processando.style.color = "white";
      processando.innerHTML = "<h1>Pagamento realizado com sucesso!</h1>";
      document.querySelector(".spinner").style.display = "none";
      document.body.style.background = "green";
      document.querySelector(".check").style.display = "flex";

      setTimeout(() => {
        window.location.href = "php/finalizar_atendimento.php";
      }, 4000);
    }, 5000);
  </script>
</body>

</html>
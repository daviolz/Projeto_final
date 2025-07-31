<?php
include("PHP/protect.php");
if ($_SESSION['nivel'] != 1) {
  echo "<script>alert('Você não tem permissão para acessar essa página!'); window.location.href='home.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="../img/Comes-_1_.ico">
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="CSS/style.css">
  <title>Cadastrar Produto</title>

  <script>
    function handlePhone(event) {
      let input = event.target;
      let value = input.value.replace(/\D/g, ""); // Remove caracteres não numéricos

      if (value.length > 10) {
        value = value.replace(/^(\d{2})(\d{5})(\d{4}).*/, "($1) $2-$3");
      } else if (value.length > 5) {
        value = value.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, "($1) $2-$3");
      } else if (value.length > 2) {
        value = value.replace(/^(\d{2})(\d{0,5})/, "($1) $2");
      } else {
        value = value.replace(/^(\d*)/, "($1");
      }

      input.value = value;
    }


    function validarEntrada(event) {
      const tecla = event.key;
      if (!/^\d$/.test(tecla) && tecla !== "Backspace" && tecla !== "Delete" && tecla !== "Tab") {
        event.preventDefault(); // Impede a entrada de caracteres não numéricos
      }
    }
  </script>

</head>

<body>
    <header>
    <a href="#" class="btn-menu">&#9776; Gerenciamento</a>
    <i class="bx bxs-user-circle"></i>
  </header>
  <nav id="menu">
    <a href="atendimento.php">Atendimento</a>
    <a href="historico.php">Historico de Pedidos</a>


    <?php
    if ($_SESSION['nivel'] == 1) {
      echo "<a href='cadastrar_produto.php'>Cadastrar Produto</a>
            <a href='cadastrar_variacao.php'>Cadastrar Variação</a>
            <a href='administrando_variacoes.php'>Administrar Variações</a>";
            
    }
    ?>

    <a href="PHP/Logout.php">Sair</a>
  </nav>

  <main id="content">
        <h2>Cadastrar Produto</h2>
    <div class="wrapper-cadastro">
        <div class="container-cadastro">
            <form action="PHP/nv_produto.php" method="POST" enctype="multipart/form-data">
            <div class="input-box-cadastro">
                <label for="nome">Nome</label>
                <input type="text" name="nome" id="nome" required>
            </div>
            <div class="input-box-cadastro">
                <label for="descricao">Descrição</label>
                <textarea name="descricao" id="descricao" required></textarea>
            </div>
            <div class="input-box-cadastro">
                <label for="tipo">Tipo do produto</label>
                <select class="tipinho" name="tipo" id="tipo">('bebida', 'salgado', 'doce','combo')
                  <option value="bebida">Bebida</option>
                  <option value="salgado">Salgado</option>
                  <option value="doce">Doce</option>
                  <option value="combo">Combo</option>
                </select>
            </div>
            
              <label for="descricao">Imagem do Produto</label>
              <input type="file" name="imagem" id="imagem" accept="image/*" required>
          

            <div class="button-box-cadastro">
                <input type="submit" value="Cadastrar" name="enviar">
            </div>
            </form>
        </div>
    </div>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const btnMenu = document.querySelector('.btn-menu');
      const navMenu = document.getElementById('menu');
      const mainContent = document.getElementById('content');

      btnMenu.addEventListener('click', function() {
        navMenu.classList.toggle('aberto');
        if (navMenu.classList.contains('aberto')) {
          navMenu.style.width = '250px';
          mainContent.style.marginLeft = '255px';
        } else {
          navMenu.style.width = '0px';
          mainContent.style.marginLeft = '0px';
        }
      });
    });
  </script>
</body>

</html>
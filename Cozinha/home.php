<?php
include("PHP/protect.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="CSS/style.css">
  <title>Home</title>
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
    <h1>Bem vindo!</h1>
    <div class="wrapper-cadastro">
      <div class="container-cadastro">
        <?php if($_SESSION['nivel'] == 1): ?>
        <div class="home-row">
          <div class="home-option">
            <a href="cadastrar_produto.php">Cadastrar Produto</a>
          </div>

          <div class="home-option">
            <a href="cadastrar_variacao.php">Cadastrar Variação</a>
          </div>

               <div class="home-option">
            <a href="administrando_variacoes.php">Administrar Variações</a>
          </div>

        </div>

        <?php endif; ?>
        <div class="home-row">
          <div class="home-option">
            <a href="atendimento.php">Atendimento</a>
          </div>

          <div class="home-option">
            <a href="historico.php">Histórico Pedidos</a>
          </div>

          
        </div>
        
      </div>
      <div class="home-option">
            <a href="atualizar_livro">Sair</a>
          </div>
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
          mainContent.style.marginLeft = '250px';
        } else {
          navMenu.style.width = '0px';
          mainContent.style.marginLeft = '0px';
        }
      });
    });
  </script>
</body>

</html>
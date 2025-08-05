<?php
// Faz a proteção para que logins não autorizados não entrem
include("PHP/protect.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Importa ícones da biblioteca Boxicons -->
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <!-- CSS do projeto -->
  <link rel="stylesheet" href="CSS/style.css">
  <!-- Icone da Comes & Bebs -->
  <link rel="icon" type="image/png" href="../img/Comes-_1_.ico">
  <!-- Titulo da Pagina -->
  <title>Home</title>
</head>

<body>
  <header>
    <!-- Menu de gerenciamento -->
    <a href="#" class="btn-menu">&#9776; Gerenciamento</a>
    <!-- Icone de Usuario (placeholder) -->
    <i class="bx bxs-user-circle"></i>
  </header>
  <!-- Navegador do menu de gerenciamento -->
  <nav id="menu">
    <a href="home.php">Home</a>
    <a href="atendimento.php">Atendimento</a>
    <a href="historico.php">Historico de Pedidos</a>


    <?php
    // Limita para que somente quem tiver o login de gerente (nivel de acesso 1) possa utilizar essas funcionalidades
    if ($_SESSION['nivel'] == 1) {
      echo "<a href='cadastrar_produto.php'>Cadastrar Produto</a>
            <a href='cadastrar_variacao.php'>Cadastrar Variação</a>
            <a href='administrando_variacoes.php'>Administrar Variações</a>";
            
    }
    ?>
    <!-- Botão que leva pro logout -->
    <a href="PHP/Logout.php">Sair</a>
  </nav>
   
  <!-- Conteudo da pagina -->
  <main id="content">
    <h1>Bem vindo!</h1>
    <!-- Menu principal da home page -->
    <div class="wrapper-menu">
      <div class="container-menu">
        <!-- Limita para que somente quem tiver o login de gerente (nivel de acesso 1) possa utilizar essas funcionalidades -->
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
      
    </div>
    </div>

  </main>
<!-- Script em Javascript para fazer a animação da navbar do menu -->
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
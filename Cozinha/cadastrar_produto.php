<?php
// Faz a proteção para que logins não autorizados não entrem
include("PHP/protect.php");
// Verifica se o usuario tenha o nivel de acesso que permite utilizar esta pagina
if ($_SESSION['nivel'] != 1) {
  echo "<script>alert('Você não tem permissão para acessar essa página!'); window.location.href='home.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Icone da Comes & Bebs -->
  <link rel="icon" type="image/png" href="../img/Comes-_1_.ico">
  <!-- Importa ícones da biblioteca Boxicons -->
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <!-- CSS do projeto -->
  <link rel="stylesheet" href="CSS/style.css">
  <!-- Titulo da Pagina -->
  <title>Cadastrar Produto</title>

</head>

<body>
    <header>
    <!-- Menu de gerenciamento -->
    <a href="#" class="btn-menu">&#9776; Gerenciamento</a>
    <!-- Icone de Usuario (placeholder) -->
    <i class="bx bxs-user-circle"></i>
  </header>
  <nav id="menu">
    <!-- Navegador do menu de gerenciamento -->
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
        <!-- Titulo -->
        <h2>Cadastrar Produto</h2>
    <!-- Cadastro de Produtos -->
    <div class="wrapper-cadastro">
        <!-- Div de estilização do Forms de cadastro -->
        <div class="container-cadastro">
            <!-- Form de cadastro de Produto -->
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
                <!-- Select para escolher para qual o tipo do produto -->
                <label for="tipo">Tipo do produto</label>
                <select class="tipinho" name="tipo" id="tipo">('bebida', 'salgado', 'doce','combo')
                  <option value="bebida">Bebida</option>
                  <option value="salgado">Salgado</option>
                  <option value="doce">Doce</option>
                  <option value="combo">Combo</option>
                </select>
            </div>

                <!-- Campo para o usuario enviar a imagem referente ao produto -->
              <label for="descricao">Imagem do Produto</label>
              <input type="file" name="imagem" id="imagem" accept="image/*" required>
          

            <div class="button-box-cadastro">
                <!-- Botão para executar o cadastro da variação -->
                <input type="submit" value="Cadastrar" name="enviar">
            </div>
            </form>
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
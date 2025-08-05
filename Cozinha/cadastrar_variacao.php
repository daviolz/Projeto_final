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
  <!-- Importa o CSS do Select2, responsável pelo visual moderno do select -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <!-- importa o jQuery, que é necessário para o funcionamento do Select2 -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
  <!-- importa o JavaScript do Select2, que permite transformar o <select> de produtos em um campo de busca avançado -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <!-- CSS do projeto -->
  <link rel="stylesheet" href="CSS/style.css">
  <!-- Titulo da Pagina -->
  <title>Cadastrar Variação</title>

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
        <h2>Cadastro de variação de produto</h2>
    <!-- Cadastro de variações -->
    <div class="wrapper-cadastro">
        <!-- Div de estilização do Forms de cadastro -->
        <div class="container-cadastro">
            <!-- Form de cadastro de Variações -->
            <form action="PHP/nv_variacao.php" method="POST">
            <div class="input-box-cadastro">
                <!-- Select para escolher para qual produto teria está variação -->
                <label for="produto">Produto</label>
                <select name="cod_produto" id="produto" required>
                  <option value="">Selecione um produto</option>
                  <?php
                  require_once("PHP/conexao.php");
                  $result = $mysqli->query("SELECT Cod_produto, Nome_produto FROM Produto");
                  while($row = $result->fetch_assoc()):
                  ?>
                    <option value="<?= $row['Cod_produto'] ?>"><?= htmlspecialchars($row['Nome_produto']) ?></option>
                  <?php endwhile; ?>
                </select>
            </div>
            <div class="input-box-cadastro">
                <!-- Input para o usuario escrever o nome da variação -->
                <label for="nome_variacao">Nome da Variação</label>
                <input type="text" name="nome_variacao" id="nome_variacao" required>
            </div>
            <div class="input-box-cadastro">
                <!-- Input para o usuario escrever o preço da variação -->
                <label for="preco">Preço</label>
                <input type="number" step="0.01" name="preco" id="preco" required>
            </div>
            <div class="button-box-cadastro">
                <!-- Botão para executar o cadastro da variação -->
                <button type="submit">Cadastrar Variação</button>
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

<!-- Inicializa o Select2 no select de produtos e reinicializa após abrir/fechar o menu lateral -->
<script>
  $(document).ready(function() {
    function initSelect2() {
      $('#produto').select2({
        placeholder: "Selecione ou pesquise um produto",
        dropdownParent: $('.container-cadastro'),
        width: 'resolve'
      });
    }

    initSelect2();

    $('.btn-menu').on('click', function() {
      setTimeout(function() {
        $('#produto').select2('destroy');
        initSelect2();
      }, 350); 
    });
  });
</script>
</body>

</html>
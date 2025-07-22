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
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <link rel="stylesheet" href="CSS/style.css">
  <title>Cadastrar aluno</title>

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
        <h2>Cadastro de variação de produto</h2>
    <div class="wrapper-cadastro">
        <div class="container-cadastro">
            <form action="PHP/nv_variacao.php" method="POST">
            <div class="input-box-cadastro">
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
                <label for="nome_variacao">Nome da Variação</label>
                <input type="text" name="nome_variacao" id="nome_variacao" required>
            </div>
            <div class="input-box-cadastro">
                <label for="preco">Preço</label>
                <input type="number" step="0.01" name="preco" id="preco" required>
            </div>
            <div class="button-box-cadastro">
                <button type="submit">Cadastrar Variação</button>
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

  <script>
    // ...existing code...

    function addObservacao() {
      const container = document.getElementById('observacoes-container');
      const group = document.createElement('div');
      group.className = 'observacao-group';
      group.innerHTML = `
        <input type="text" name="observacao[]" placeholder="Digite uma observação">
        <button type="button" onclick="addObservacao()">+</button>
        <button type="button" onclick="removeObservacao(this)">-</button>
      `;
      container.appendChild(group);
    }

    function removeObservacao(btn) {
      const group = btn.parentNode;
      const container = document.getElementById('observacoes-container');
      if (container.querySelectorAll('.observacao-group').length > 1) {
        container.removeChild(group);
      }
    }
  </script>
<script>
  $(document).ready(function() {
    function initSelect2() {
      $('#produto').select2({
        placeholder: "Selecione ou pesquise um produto",
        dropdownParent: $('.container-cadastro'),
        width: '100%'
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
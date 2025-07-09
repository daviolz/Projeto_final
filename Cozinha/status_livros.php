<?php
include("PHP/protect.php");
include_once("PHP/conexao.php");

// Monta a query base
$sql = "SELECT Ul.*, L.Nome_livro, L.Ano_publicacao, L.Editora 
        FROM Unidade_livro as Ul 
        INNER JOIN Livro as L ON Ul.Cod_livro = L.Cod_livro";

// Filtros
$where = [];
if (isset($_POST['selecionar'])) {
  if (!empty($_POST['livro'])) {
    $id = intval($_POST['livro']);
    $where[] = "L.Cod_livro = $id";
  }
  if (!empty($_POST['status'])) {
    $status = mysqli_real_escape_string($conexao, $_POST['status']);
    $where[] = "Ul.Status = '$status'";
  }
}
if (count($where) > 0) {
  $sql .= " WHERE " . implode(' AND ', $where);
}

$resultUnidades = mysqli_query($conexao, $sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="CSS/style.css">
  <title>Status dos Livros</title>
</head>

<body>
  <header>
    <a href="#" class="btn-menu">&#9776; BIBLIOTECA</a>
    <i class="bx bxs-user-circle"></i>
  </header>
  <nav id="menu">
    <a href="cadastrar_cliente.php">Cadastrar Cliente</a>
    <a href="atualizar_cliente.php">Atualizar dados de Cliente</a>
    <a href="fazer_emprestimo.php">Empréstimo</a>
    <a href="status_livros.php">Status de livros</a>
    <?php
    if ($_SESSION['nivel'] == 1) {
      echo "<a href='cadastrar_genero.php'>Cadastrar Gênero</a>
            <a href='cadastrar_livro.php'>Cadastrar Livro</a>
            <a href='atualizar_livro.php'>Atualizar dados de livro</a>
            <a href='gerenciar_estoque.php'>Gerenciar estoque</a>";
    }
    ?>
    <a href="PHP/Logout.php">Sair</a>
  </nav>

  <main id="content">
    <h2>Visualizar Status</h2>
    <div class="wrapper-cadastro">
      <div class="container-cadastro">
        <form action="" method="POST">
          <div class="input-box-cadastro-row">
            <div class="input-box-cadastro">
              <label for="livro">Livro</label>
              <select id="livros" name="livro">
                <option value="">Todos</option>
                <?php
                $sqlLivros = "SELECT Cod_livro, Nome_livro FROM Livro ORDER BY Nome_livro";
                $resultLivros = mysqli_query($conexao, $sqlLivros);
                if ($resultLivros && mysqli_num_rows($resultLivros) > 0) {
                  while ($row = mysqli_fetch_assoc($resultLivros)) {
                    $selected = (isset($_POST['livro']) && $_POST['livro'] == $row['Cod_livro']) ? "selected" : "";
                    echo "<option value='" . htmlspecialchars($row['Cod_livro']) . "' $selected>" . htmlspecialchars($row['Nome_livro']) . "</option>";
                  }
                }
                ?>
              </select>
            </div>

            <div class="input-box-cadastro">
              <label for="status">Status</label>
              <select id="status" name="status">
                <option value="">Todos</option>
                <option value="Disponível" <?php if (isset($_POST['status']) && $_POST['status'] == "Disponível") echo "selected"; ?>>Disponível</option>
                <option value="Emprestado" <?php if (isset($_POST['status']) && $_POST['status'] == "Emprestado") echo "selected"; ?>>Emprestado</option>
              </select>
            </div>

            <div class="button-box-cadastro" style="margin-top: 10px; margin-bottom: 0">
              <input type="submit" value="Selecionar" name="selecionar">
            </div>
          </div>
        </form>
        <div class="table-livro mt-3">
          <table class="table">
            <thead>
              <tr>
                <th>Código da Unidade</th>
                <th>Livro</th>
                <th>Publicação</th>
                <th>Editora</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if ($resultUnidades && mysqli_num_rows($resultUnidades) > 0) {
                while ($row = mysqli_fetch_assoc($resultUnidades)) {
                  echo "<tr>
                    <td>" . $row['Cod_unidade'] . "</td>
                    <td>" . $row['Nome_livro'] . "</td>
                    <td>" . $row['Ano_publicacao'] . "</td>
                    <td>" . $row['Editora'] . "</td>
                    <td>" . $row['Status'] . "</td>
                  </tr>";
                }
              } else {
                echo "<tr><td colspan='5'>Nenhuma Unidade no estoque</td></tr>";
              }
              ?>
            </tbody>
          </table>
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
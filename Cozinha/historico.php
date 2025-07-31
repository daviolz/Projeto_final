<?php
include("PHP/protect.php");
require_once("PHP/conexao.php");

// Filtros
$filtros = [];
$params = [];
$tipos = "";

// Filtro por comanda
if (!empty($_GET['comanda'])) {
    $filtros[] = "Pedido.Cod_comanda = ?";
    $params[] = $_GET['comanda'];
    $tipos .= "i";
}

// Filtro por produto
if (!empty($_GET['produto'])) {
    $filtros[] = "Produto_Variacao.Cod_produto = ?";
    $params[] = $_GET['produto'];
    $tipos .= "i";
}

// Filtro por variação
if (!empty($_GET['variacao'])) {
    $filtros[] = "Pedido.Cod_variacao = ?";
    $params[] = $_GET['variacao'];
    $tipos .= "i";
}

// Filtro por data
if (!empty($_GET['data'])) {
    $filtros[] = "DATE(Pedido.Data_hora) = ?";
    $params[] = $_GET['data'];
    $tipos .= "s";
}

$where = "";
if (count($filtros) > 0) {
    $where = "WHERE " . implode(" AND ", $filtros);
}

// Busca opções para os selects
$comandas = $mysqli->query("SELECT Cod_comanda, Senha FROM Comanda");
$produtos = $mysqli->query("SELECT Cod_produto, Nome_produto FROM Produto");
$variacoes = $mysqli->query("SELECT Cod_variacao, Nome_variacao FROM Produto_Variacao");

// Monta a query principal
$sql = "SELECT Pedido.*, Comanda.Senha, Produto.Nome_produto, Produto_Variacao.Nome_variacao
        FROM Pedido
        LEFT JOIN Comanda ON Pedido.Cod_comanda = Comanda.Cod_comanda
        LEFT JOIN Produto_Variacao ON Pedido.Cod_variacao = Produto_Variacao.Cod_variacao
        LEFT JOIN Produto ON Produto_Variacao.Cod_produto = Produto.Cod_produto
        $where
        ORDER BY Pedido.Data_hora DESC";

$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    die("Erro na preparação da query: " . $mysqli->error);
}
if ($params) {
    $stmt->bind_param($tipos, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <style>
    .selecaofiltro:focus {
  border: 2px solid #dfdfdf;
  outline: none;
  box-shadow: 0 0 5px #B7734433;
}

.select2-container {
  width: 220px ; 
  font-size: 16px;
}
.select2-selection {
  height: 30px !important;
  border-radius: 5px !important;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 10px;
}
.select2-selection__rendered {
  line-height: 30px !important;
}
  </style>
  <link rel="stylesheet" href="CSS/style.css">
  <title>Histórico</title>
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
  <main id="content2">
    <h2>Histórico de Pedidos</h2>
    <form class="form_filtro" method="get" style="margin-bottom:20px;">
        <label class="nome_filtro">Comanda:
            <select class="filtro_comanda" name="comanda">
                <option value="">Todas</option>
                <?php while($c = $comandas->fetch_assoc()): ?>
                    <option value="<?= $c['Cod_comanda'] ?>" <?= (isset($_GET['comanda']) && $_GET['comanda'] == $c['Cod_comanda']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['Senha']) ?> (<?= $c['Cod_comanda'] ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </label>
        <label class="nome_filtro">Produto:
            <select class="filtro_produto" name="produto">
                <option value="">Todos</option>
                <?php while($p = $produtos->fetch_assoc()): ?>
                    <option value="<?= $p['Cod_produto'] ?>" <?= (isset($_GET['produto']) && $_GET['produto'] == $p['Cod_produto']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['Nome_produto']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </label>
        <label class="nome_filtro">Variação:
            <select class="filtro_variação" name="variacao">
                <option value="">Todas</option>
                <?php while($v = $variacoes->fetch_assoc()): ?>
                    <option value="<?= $v['Cod_variacao'] ?>" <?= (isset($_GET['variacao']) && $_GET['variacao'] == $v['Cod_variacao']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($v['Nome_variacao']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </label>
        <label class="nome_filtro">Data:
            <input class="filtro_data" type="date" name="data" value="<?= isset($_GET['data']) ? htmlspecialchars($_GET['data']) : '' ?>">
        </label>
        <button type="submit" class="btn-filtro">Filtrar</button>
        <a href="historico.php" class="btn-limpar">Limpar filtros</a>
    </form>
    <table border="1" cellpadding="6" cellspacing="0" style="width:100%;background:#fff;">
        <tr>
            <th>Código</th>
            <th>Comanda (Senha)</th>
            <th>Produto</th>
            <th>Variação</th>
            <th>Quantidade</th>
            <th>Valor Pedido</th>
            <th>Data/Hora</th>
        </tr>
        <?php if($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['Cod_pedido'] ?></td>
                    <td><?= htmlspecialchars($row['Senha'] ?? '') ?> </td>
                    <td><?= htmlspecialchars($row['Nome_produto'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['Nome_variacao'] ?? '') ?></td>
                    <td><?= $row['Qte'] ?></td>
                    <td>R$ <?= number_format($row['Valor_pedido'], 2, ',', '.') ?></td>
                    <td><?= $row['Data_hora'] ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="7">Nenhum pedido encontrado.</td></tr>
        <?php endif; ?>
    </table>
  </main>
  <script>
  $(document).ready(function() {
    $('select[name="comanda"], select[name="produto"], select[name="variacao"]').select2({
      width: 'resolve',
      dropdownParent: $('.container-cadastro').length ? $('.container-cadastro') : $('body')
    });
  });
</script>

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
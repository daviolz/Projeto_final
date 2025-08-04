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

// Junta os filtros selecionados para a consulta
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
  <!-- Icone da Comes & Bebs -->
  <link rel="icon" type="image/png" href="../img/Comes-_1_.ico">
  <!-- Importa ícones da biblioteca Boxicons -->
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <!-- Bibliotecas de estilos CSS para a tabela e os selects -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <style>

  </style>
  <!-- CSS do projeto -->
  <link rel="stylesheet" href="CSS/style.css">
  <!-- Titulo da Pagina -->
  <title>Histórico de Pedidos</title>
</head>

<body>
  <header>
    <!-- Menu de gerenciamento -->
    <a href="#" class="btn-menu">&#9776; Gerenciamento</a>
    <!-- Icone do menu de gerenciamento -->
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
  <main id="content2">
    <!-- Titulo -->
    <h2>Histórico de Pedidos</h2>
    <!-- Forms pro filtro dos pedidos -->
    <form class="form_filtro" method="get" style="margin-bottom:20px;">
      <!-- Filtro de pedidos pelas comandas -->
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
        <!-- Filtro de pedidos pelos produtos -->
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
        <!-- Filtro de pedidos pelas variações -->
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
        <!-- Filtro de pedidos pela data -->
        <label class="nome_filtro">Data:
            <input class="filtro_data" type="date" name="data" value="<?= isset($_GET['data']) ? htmlspecialchars($_GET['data']) : '' ?>">
        </label>
        <!-- Botão para executar os filtros -->
        <button type="submit" class="btn-filtro">Filtrar</button>
        <!-- Link para voltar pra pagina e limpar os filtros -->
        <a href="historico.php" class="btn-limpar">Limpar filtros</a>
    </form>
    <!-- Tabela dos Pedidos -->
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
        <!-- Dados dos Pedidos -->
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
        <!-- Mensagem para mostrar quando nenhum pedido for encontrado -->
        <?php else: ?>
            <tr><td colspan="7">Nenhum pedido encontrado.</td></tr>
        <?php endif; ?>
    </table>
  </main>
  <!-- Script para aplicar o plugin Select2 nos selects dos filtros  -->
  <script>
  $(document).ready(function() {
    $('select[name="comanda"], select[name="produto"], select[name="variacao"]').select2({
      width: 'resolve',
      dropdownParent: $('body')
    });
  });
</script>

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
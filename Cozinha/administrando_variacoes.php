<?php
include("PHP/protect.php");
require_once("PHP/conexao.php");

// Troca status se solicitado
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
    $id = intval($_GET['toggle']);
    // Busca status atual
    $res = $mysqli->query("SELECT Status FROM Produto_Variacao WHERE Cod_variacao = $id");
    if ($res && $row = $res->fetch_assoc()) {
        $novoStatus = ($row['Status'] === 'disponivel') ? 'indisponivel' : 'disponivel';
        $mysqli->query("UPDATE Produto_Variacao SET Status = '$novoStatus' WHERE Cod_variacao = $id");
    }
    // Redireciona para evitar reenvio do formulário
    $url = strtok($_SERVER["REQUEST_URI"],'?');
    $query = $_GET;
    unset($query['toggle']);
    $redirect = $url . (count($query) ? '?' . http_build_query($query) : '');
    header("Location: $redirect");
    exit;
}

// Busca tipos de produto
$tipos = [];
$resTipos = $mysqli->query("SELECT DISTINCT Tipo_produto FROM Produto");
while($row = $resTipos->fetch_assoc()) {
    $tipos[] = $row['Tipo_produto'];
}

// Filtros
$filtros = [];
$params = [];
$tipos_param = "";

if (!empty($_GET['tipo_produto'])) {
    $filtros[] = "Produto.Tipo_produto = ?";
    $params[] = $_GET['tipo_produto'];
    $tipos_param .= "s";
}
if (!empty($_GET['produto'])) {
    $filtros[] = "Produto.Cod_produto = ?";
    $params[] = $_GET['produto'];
    $tipos_param .= "i";
}
if (isset($_GET['disponibilidade']) && $_GET['disponibilidade'] !== "") {
    $filtros[] = "Produto_Variacao.Status = ?";
    $params[] = $_GET['disponibilidade'];
    $tipos_param .= "s";
}

$where = "";
if ($filtros) {
    $where = "WHERE " . implode(" AND ", $filtros);
}

// Busca produtos para o filtro
$produtos = $mysqli->query("SELECT Cod_produto, Nome_produto FROM Produto");

// Monta a query principal
$sql = "SELECT Produto_Variacao.*, Produto.Nome_produto, Produto.Tipo_produto 
        FROM Produto_Variacao 
        JOIN Produto ON Produto_Variacao.Cod_produto = Produto.Cod_produto
        $where
        ORDER BY Produto.Nome_produto, Produto_Variacao.Nome_variacao";

$stmt = $mysqli->prepare($sql);
if ($params) {
    $stmt->bind_param($tipos_param, ...$params);
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
    <h2>Variações de Produtos</h2>
    <form method="get" style="margin-bottom:20px;">
        <label>Tipo do Produto:
            <select name="tipo_produto" onchange="this.form.submit()">
                <option value="">Todos</option>
                <?php foreach($tipos as $tipo): ?>
                    <option value="<?= htmlspecialchars($tipo) ?>" <?= (isset($_GET['tipo_produto']) && $_GET['tipo_produto'] == $tipo) ? 'selected' : '' ?>>
                        <?= ucfirst($tipo) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Produto:
            <select name="produto" onchange="this.form.submit()">
                <option value="">Todos</option>
                <?php
                // Refaça a consulta para não esgotar o result set
                $produtos = $mysqli->query("SELECT Cod_produto, Nome_produto FROM Produto");
                while($p = $produtos->fetch_assoc()): ?>
                    <option value="<?= $p['Cod_produto'] ?>" <?= (isset($_GET['produto']) && $_GET['produto'] == $p['Cod_produto']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['Nome_produto']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </label>
        <label>Disponibilidade:
            <select name="disponibilidade" onchange="this.form.submit()">
                <option value="">Todas</option>
                <option value="disponivel" <?= (isset($_GET['disponibilidade']) && $_GET['disponibilidade'] === 'disponivel') ? 'selected' : '' ?>>Disponível</option>
                <option value="indisponivel" <?= (isset($_GET['disponibilidade']) && $_GET['disponibilidade'] === 'indisponivel') ? 'selected' : '' ?>>Indisponível</option>
            </select>
        </label>
        <noscript><button type="submit">Filtrar</button></noscript>
    </form>

    <table border="1" cellpadding="6" cellspacing="0" style="width:100%;background:#fff;">
        <tr>
            <th>Produto</th>
            <th>Tipo</th>
            <th>Variação</th>
            <th>Preço</th>
            <th>Status</th>
            <th>Ação</th>
        </tr>
        <?php if($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['Nome_produto']) ?></td>
                    <td><?= htmlspecialchars($row['Tipo_produto']) ?></td>
                    <td><?= htmlspecialchars($row['Nome_variacao']) ?></td>
                    <td>R$ <?= number_format($row['Preco'], 2, ',', '.') ?></td>
                    <td><?= htmlspecialchars($row['Status']) ?></td>
                    <td>
                        <form method="get" style="display:inline;">
                            <?php
                            // Mantém filtros ao alternar status
                            foreach ($_GET as $k => $v) {
                                if ($k !== 'toggle') {
                                    if (is_array($v)) {
                                        foreach ($v as $vv) {
                                            echo '<input type="hidden" name="'.htmlspecialchars($k).'[]" value="'.htmlspecialchars($vv).'">';
                                        }
                                    } else {
                                        echo '<input type="hidden" name="'.htmlspecialchars($k).'" value="'.htmlspecialchars($v).'">';
                                    }
                                }
                            }
                            ?>
                            <input type="hidden" name="toggle" value="<?= $row['Cod_variacao'] ?>">
                            <button type="submit">
                                <?= $row['Status'] === 'disponivel' ? 'Indisponibilizar' : 'Disponibilizar' ?>
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">Nenhuma variação encontrada.</td></tr>
        <?php endif; ?>
    </table>
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
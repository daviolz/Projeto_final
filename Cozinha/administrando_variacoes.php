<?php
// Faz a proteção para que logins não autorizados não entrem
include("PHP/protect.php");
// Faz conexão com o banco de dados do projeto
require_once("PHP/conexao.php");

// Troca o status da variação se solicitado via GET (toggle)
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
    $id = intval($_GET['toggle']);
    // Busca o status atual da variação
    $res = $mysqli->query("SELECT Status FROM Produto_Variacao WHERE Cod_variacao = $id");
    if ($res && $row = $res->fetch_assoc()) {
        // Alterna entre 'disponivel' e 'indisponivel'
        $novoStatus = ($row['Status'] === 'disponivel') ? 'indisponivel' : 'disponivel';
        $mysqli->query("UPDATE Produto_Variacao SET Status = '$novoStatus' WHERE Cod_variacao = $id");
    }
    // Redireciona para evitar reenvio do formulário e mantém os filtros
    $url = strtok($_SERVER["REQUEST_URI"],'?');
    $query = $_GET;
    unset($query['toggle']);
    $redirect = $url . (count($query) ? '?' . http_build_query($query) : '');
    header("Location: $redirect");
    exit;
}

// Busca todos os tipos de produto para o filtro
$tipos = [];
$resTipos = $mysqli->query("SELECT DISTINCT Tipo_produto FROM Produto");
while($row = $resTipos->fetch_assoc()) {
    $tipos[] = $row['Tipo_produto'];
}

// Monta filtros dinâmicos conforme os parâmetros GET
$filtros = [];
$params = [];
$tipos_param = "";

// Filtro por tipo de produto
if (!empty($_GET['tipo_produto'])) {
    $filtros[] = "Produto.Tipo_produto = ?";
    $params[] = $_GET['tipo_produto'];
    $tipos_param .= "s";
}
// Filtro por produto específico
if (!empty($_GET['produto'])) {
    $filtros[] = "Produto.Cod_produto = ?";
    $params[] = $_GET['produto'];
    $tipos_param .= "i";
}
// Filtro por disponibilidade (status)
if (isset($_GET['disponibilidade']) && $_GET['disponibilidade'] !== "") {
    $filtros[] = "Produto_Variacao.Status = ?";
    $params[] = $_GET['disponibilidade'];
    $tipos_param .= "s";
}

// Monta a cláusula WHERE se houver filtros
$where = "";
if ($filtros) {
    $where = "WHERE " . implode(" AND ", $filtros);
}

// Busca todos os produtos para o filtro de produto
$produtos = $mysqli->query("SELECT Cod_produto, Nome_produto FROM Produto");

// Monta a query principal para buscar as variações, já com JOIN no produto
$sql = "SELECT Produto_Variacao.*, Produto.Nome_produto, Produto.Tipo_produto 
        FROM Produto_Variacao 
        JOIN Produto ON Produto_Variacao.Cod_produto = Produto.Cod_produto
        $where
        ORDER BY Produto.Nome_produto, Produto_Variacao.Nome_variacao";

// Prepara e executa a query com os filtros
$stmt = $mysqli->prepare($sql);
if ($params) {
    $stmt->bind_param($tipos_param, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Deletar variação
if (isset($_GET['delete_variacao']) && is_numeric($_GET['delete_variacao'])) {
    $id = intval($_GET['delete_variacao']);
    $mysqli->query("DELETE FROM Produto_Variacao WHERE Cod_variacao = $id");
    header("Location: administando_variacoes.php?" . http_build_query(array_diff_key($_GET, ['delete_variacao'=>1])));
    exit;
}

// Deletar produto (só se não houver variações associadas)
if (isset($_GET['delete_produto']) && is_numeric($_GET['delete_produto'])) {
    $id = intval($_GET['delete_produto']);
    // Verifica se tem variações
    $res = $mysqli->query("SELECT COUNT(*) as total FROM Produto_Variacao WHERE Cod_produto = $id");
    $row = $res->fetch_assoc();
    if ($row['total'] == 0) {
        $mysqli->query("DELETE FROM Produto WHERE Cod_produto = $id");
    }
    header("Location: administando_variacoes.php?" . http_build_query(array_diff_key($_GET, ['delete_produto'=>1])));
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Ícones Boxicons -->
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <!-- CSS principal -->
  <link rel="stylesheet" href="CSS/style.css">
  <!-- CSS e JS do Select2 para selects bonitos -->
  <link rel="icon" type="image/png" href="../img/Comes-_1_.ico">
  <!-- Importa o CSS do Select2, responsável pelo visual moderno do select -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <!-- importa o jQuery, que é necessário para o funcionamento do Select2 -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
  <!-- importa o JavaScript do Select2, que permite transformar o <select> de produtos em um campo de busca avançado -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
    <!-- Titulo -->
    <h2>Variações de Produtos</h2>
    <!-- Formulário de filtros -->
    <form method="get" style="margin-bottom:20px;">
        <!-- Filtro por meio dos tipos dos produtos -->
        <label>Tipo do Produto:
            <select class="filtro_tipo_variacao" name="tipo_produto" onchange="this.form.submit()">
                <option value="">Todos</option>
                <?php foreach($tipos as $tipo): ?>
                    <option value="<?= htmlspecialchars($tipo) ?>" <?= (isset($_GET['tipo_produto']) && $_GET['tipo_produto'] == $tipo) ? 'selected' : '' ?>>
                        <?= ucfirst($tipo) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <!-- Filtro por meio dos produtos -->
        <label>Produto:
            <select name="produto" onchange="this.form.submit()">
                <option value="">Todos</option>
                <?php
                // Reexecuta a consulta para garantir que o result set não foi esgotado
                $produtos = $mysqli->query("SELECT Cod_produto, Nome_produto FROM Produto");
                while($p = $produtos->fetch_assoc()): ?>
                    <option value="<?= $p['Cod_produto'] ?>" <?= (isset($_GET['produto']) && $_GET['produto'] == $p['Cod_produto']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['Nome_produto']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </label>
        <!-- Filtro por meio da diponibilidade -->
        <label>Disponibilidade:
            <select name="disponibilidade" onchange="this.form.submit()">
                <option value="">Todas</option>
                <option value="disponivel" <?= (isset($_GET['disponibilidade']) && $_GET['disponibilidade'] === 'disponivel') ? 'selected' : '' ?>>Disponível</option>
                <option value="indisponivel" <?= (isset($_GET['disponibilidade']) && $_GET['disponibilidade'] === 'indisponivel') ? 'selected' : '' ?>>Indisponível</option>
            </select>
        </label>
        <noscript><button type="submit">Filtrar</button></noscript>
    </form>

    <!-- Tabela de variações -->
    <table border="1" cellpadding="6" cellspacing="0" style="width:100%;background:#fff;">
        <tr>
            <th>Produto</th>
            <th>Tipo</th>
            <th>Variação</th>
            <th>Preço</th>
            <th>Status</th>
            <th>Ações</th>
            
        </tr>
        <!-- Verifica se existem dados para exibir depois da busca e os exibe -->
        <?php if($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['Nome_produto']) ?></td>
                    <td><?= htmlspecialchars($row['Tipo_produto']) ?></td>
                    <td><?= htmlspecialchars($row['Nome_variacao']) ?></td>
                    <td>R$ <?= number_format($row['Preco'], 2, ',', '.') ?></td>
                    <td><?= htmlspecialchars($row['Status']) ?></td>
                    <td class="acao">
                        <!-- Formulário para alternar status da variação -->
                        <form method="get" style="display:inline;">
                            <?php
                            // Mantém os filtros ao alternar status
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
                            <button type="submit" class="btn-disponibilidade">
                                <?= $row['Status'] === 'disponivel' ? 'Indisponibilizar' : 'Disponibilizar' ?>
                            </button>
                        </form>
                    </td>
                    <td class="acao">
                        <!-- Botão para deletar variação -->
                        <form method="get" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja deletar esta variação?');">
                            <?php
                            foreach ($_GET as $k => $v) {
                                if ($k !== 'delete_variacao' && $k !== 'delete_produto') {
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
                            <input type="hidden" name="delete_variacao" value="<?= $row['Cod_variacao'] ?>">
                            <button type="submit" class="btn-disponibilidade">Deletar Variação</button>
                        </form>
                        <!-- Botão para deletar produto (só mostra se não houver outras variações) -->
                         </td>
                        
                        <?php
                        // Verifica se é a única variação do produto
                        $cod_produto = intval($row['Cod_produto']);
                        $resVar = $mysqli->query("SELECT COUNT(*) as total FROM Produto_Variacao WHERE Cod_produto = $cod_produto");
                        $rowVar = $resVar->fetch_assoc();
                        if ($rowVar['total'] == 1) {
                        ?>
                        <td class="acao">
                        <form method="get" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja deletar este produto?');">
                            <?php
                            foreach ($_GET as $k => $v) {
                                if ($k !== 'delete_variacao' && $k !== 'delete_produto') {
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
                            <input type="hidden" name="delete_produto" value="<?= $row['Cod_produto'] ?>">
                            <button type="submit" class="btn-disponibilidade">Deletar Produto</button>
                        </form>
                        </td>
                        <?php }?>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">Nenhuma variação encontrada.</td></tr>
        <?php endif; ?>
    </table>
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
  <!--  Script em Javascript que inicializa o Select2 nos selects de filtro -->
  <script>
    $(document).ready(function() {
      $('select[name="tipo_produto"], select[name="produto"], select[name="disponibilidade"]').select2({
        width: 'resolve',
        dropdownParent: $('body')
      });
    });
  </script>
</body>
</html>
<?php
include("PHP/protect.php");
require_once("PHP/conexao.php");

if ($_SESSION['nivel'] != 1 && $_SESSION['nivel'] != 2) {
  echo "<script>alert('Você não tem permissão para acessar essa página!'); window.location.href='home.php';</script>";
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_comanda'])) {
    $id = intval($_POST['id_comanda']);
    $mysqli->query("UPDATE Comanda SET Status = 'pronto' WHERE Cod_comanda = $id");
    header("Location: atendimento.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_comanda_entrega'])) {
    $id = intval($_POST['id_comanda_entrega']);
    $mysqli->query("UPDATE Comanda SET Status = 'entregue' WHERE Cod_comanda = $id");
    header("Location: atendimento.php");
    exit;
}



?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="refresh" content="10">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="../img/Comes-_1_.ico">
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="CSS/style.css">
  <title>Registrar Atendimento</title>
  
  <script>
    
    document.addEventListener('DOMContentLoaded', function() {
      <?php if ($nivel == 1): ?>
        atualizarFuncionarios();
        document.getElementById('tipo_funcionario').addEventListener('change', atualizarFuncionarios);
      <?php endif; ?>
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

<style>
.pedido-container {
  display: flex;
  padding: 20px;
  gap: 20px;
  flex-wrap: wrap;
  justify-content: center; 
}
 
.pedido{
  padding: 20px;
  color: black;
  background-color:rgb(255, 255, 255);
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.05);
  width: calc(50% - 20px); 
  
}

.pedido-item {
  margin-bottom: 20px;
  padding: 20px;
  background-color: #f9f9f9;
  border-radius: 8px;
}

.pedido h3 {
  text-align: center;
}

.pedido p{
  margin: 20px 0;
  font-size: 18px;
  text-align: center;
  border-bottom: 1px solid #ddd;
  padding-bottom: 5px;
  
}

.pedido-list ul {
  list-style-type: none;
}

.pedido-list ul li {
  margin-bottom: 10px;
  padding: 10px;
  background-color: #f1f1f1;
  border-radius: 4px; 
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 16px;
  color: #333;
  border: 1px solid #ddd;
  text-align: center;
}

.pedido-list strong{
  text-align: center;
  margin-bottom: 10px;
  display: block;
  font-size: 20px;
  margin-top: 30px;
}


  </style>
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
    <h2 style="text-align: center; padding: 20px;">Registrar Atendimento</h2>
    <!-- Exemplo de bloco para exibir as comandas e seus pedidos relacionados em duas colunas -->
<div class="pedido-container">
    <!-- Comandas sendo preparadas (Status = 'preparando') -->
    <div class="pedido">
        <h3>Comandas sendo preparadas</h3>
        <?php
        require_once("PHP/conexao.php");
        $comandasPrep = $mysqli->query("SELECT * FROM Comanda WHERE Status = 'preparando'");
        if ($comandasPrep && $comandasPrep->num_rows > 0) {
            while($comanda = $comandasPrep->fetch_assoc()) {
                echo "<div class='pedido-item'>";
                echo "<p><strong>Código:</strong> {$comanda['Cod_comanda']}</p>";
                echo "<p><strong>Valor Total:</strong> R$ " . number_format($comanda['Valor_total'], 2, ',', '.') . "</p>";
                echo "<p><strong>Data/Hora:</strong> {$comanda['Data_hora']}</p>";
                echo "<p><strong>Status:</strong> {$comanda['Status']}</p>";
                echo "<p><strong>Senha:</strong> {$comanda['Senha']}</p>";
                // Pedidos relacionados
                $pedidos = $mysqli->query("SELECT Pedido.*, Produto.Nome_produto, Produto_Variacao.Nome_variacao 
                    FROM Pedido 
                    LEFT JOIN Produto_Variacao ON Pedido.Cod_variacao = Produto_Variacao.Cod_variacao
                    LEFT JOIN Produto ON Produto_Variacao.Cod_produto = Produto.Cod_produto
                    WHERE Pedido.Cod_comanda = {$comanda['Cod_comanda']}");
                if ($pedidos && $pedidos->num_rows > 0) {
                    echo "<div class='pedido-list' style='margin-top:10px;'><strong>Pedidos:</strong><ul>";
                    while($pedido = $pedidos->fetch_assoc()) {
                        echo "<li>";
                        echo "Produto: " . htmlspecialchars($pedido['Nome_produto'] ?? '') . " | ";
                        echo "Variação: " . htmlspecialchars($pedido['Nome_variacao'] ?? '') . " | ";
                        echo "Quantidade: " . ($pedido['Qte'] ?? '') ;
                        echo "</li>";
                    }
                    echo "</ul></div>";
                    echo "<p><strong>Ação:</strong></p>";
                    echo "<div class= 'div_acao_atendimento'>
                          <form method='post' action=''>
                          <input type='hidden' name='id_comanda' value='{$comanda['Cod_comanda']}'>
                          <button class='acao_atendimento' type='submit'>Marcar como Pronta</button>
                          </form>
                          </div>";
                    
                } else {
                    echo "<div style='margin-top:10px;'>Nenhum pedido para esta comanda.</div>";
                }
                echo "</div>";
            }
        } else {
            echo "Nenhuma comanda sendo preparada.";
        }
        ?>
    </div>
    <!-- comandas prontas (Status = 'pronta') -->
    <div class="pedido">
        <h3>Comandas prontas</h3>
        <?php
        $comandasProntas = $mysqli->query("SELECT * FROM Comanda WHERE Status = 'pronto'");
        if ($comandasProntas && $comandasProntas->num_rows > 0) {
            while($comanda = $comandasProntas->fetch_assoc()) {
                echo "<div class='pedido-item'>";
                echo "<p><strong>Código:</strong> {$comanda['Cod_comanda']}</p>";
                echo "<p><strong>Valor Total:</strong> R$ " . number_format($comanda['Valor_total'], 2, ',', '.') . "</p>";
                echo "<p><strong>Data/Hora:</strong> {$comanda['Data_hora']}</p>";
                echo "<p><strong>Status:</strong> {$comanda['Status']}</p>";
                echo "<p><strong>Senha:</strong> {$comanda['Senha']}</p>";
                // Pedidos relacionados
                $pedidos = $mysqli->query("SELECT Pedido.*, Produto.Nome_produto, Produto_Variacao.Nome_variacao 
                    FROM Pedido 
                    LEFT JOIN Produto_Variacao ON Pedido.Cod_variacao = Produto_Variacao.Cod_variacao
                    LEFT JOIN Produto ON Produto_Variacao.Cod_produto = Produto.Cod_produto
                    WHERE Pedido.Cod_comanda = {$comanda['Cod_comanda']}");
                if ($pedidos && $pedidos->num_rows > 0) {
                    echo "<div class='pedido-list' style='margin-top:10px;'><strong>Pedidos:</strong><ul>";
                    while($pedido = $pedidos->fetch_assoc()) {
                        echo "<li>";
                        echo "Produto: " . htmlspecialchars($pedido['Nome_produto'] ?? '') . " | ";
                        echo "Variação: " . htmlspecialchars($pedido['Nome_variacao'] ?? '') . " | ";
                        echo "Quantidade: " . ($pedido['Qte'] ?? '') . " | ";
                        echo "Data/Hora: " . ($pedido['Data_hora'] ?? '');
                        echo "</li>";
                    }
                    echo "</ul></div>";
                    echo "<p><strong>Ação:</strong></p>";
                    echo "<div class= 'div_acao_atendimento'>
                          <form method='post' action=''>
                          <input type='hidden' name='id_comanda_entrega' value='{$comanda['Cod_comanda']}'>
                          <button class='acao_atendimento' type='submit'>Marcar como Entregue</button>
                          </form>
                          </div>";
                } else {
                    echo "<div style='margin-top:10px;'>Nenhum pedido para esta comanda.</div>";
                }
                echo "</div>";
            }
        } else {
            echo "Nenhuma comanda pronta.";
        }
        ?>
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
</body>

</html>
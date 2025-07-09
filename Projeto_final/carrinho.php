<?php
include_once 'php/conexao.php';
    session_start();

    // Garante que o carrinho existe
    $carrinho = isset($_SESSION['carrinho']) ? $_SESSION['carrinho'] : [];
    $total = 0.0;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/carrinho.css" />
    <title>Carrinho</title>
    <style>
      main{
        margin-left: 0;
      }
    </style>
</head>
<body>
    <main>
      <div class="conteudo-carrinho">
        <div class="div-titulo">
          <img src="../img/carrinho.png" id="carrinho-titulo" alt="carrinho" />
          <h1>Carrinho</h1>
        </div>
        <div class="div-carrinho">
          <?php if (empty($carrinho)): ?>
            <p style='text-align: center;'>Seu carrinho está vazio.</p>
          <?php else: ?>
            <?php foreach ($carrinho as $produto): 
                $subtotal = $produto['preco'] * $produto['qte'];
                $total += $subtotal;
            ?>
            <div class="div-produto-carrinho">
              <img
                class="imagem-produto-carrinho"
                src="<?php echo '../' . htmlspecialchars($produto['imagem_produto']); ?>"
                alt="<?php echo htmlspecialchars($produto['nome_produto']); ?>"
              />
              <div class="info-produto">
                <h3><?php echo htmlspecialchars($produto['nome_produto']); ?></h2>
                <?php if (!empty($produto['nome_variacao'])): ?>
                  <p><?php echo htmlspecialchars($produto['nome_variacao']); ?></p>
                <?php endif; ?>
              </div>
              <div class='preco-produto'><p>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></h3></div>
              <div class="controle-qtd">
                <span class='qte-menos'>-</span>
                <input type='number' class='qte-input' name='qte[<?php echo $produto['cod_variacao']?>]' value='<?php echo $produto['qte']; ?>' min="1" data-preco="<?php echo $produto['preco']; ?>" />
                <span class='qte-mais'>+</span>
              </div>
            </div>
            <?php endforeach; ?>
            <div class="total-carrinho">
              <h2>Total:</h2>
              <h2 class="total-valor">R$ <?php echo number_format($total, 2, ',', '.'); ?></h2>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </main>
    <footer>
      <img src="../img/Logo.png" alt="logo" class="logo" />
      <div class="op">
        <div class="total">
          <h2>Total: R$ <?php echo number_format($total, 2, ',', '.'); ?></h2>
        </div>
        <div class="baixo">
          <button class="op-btn cancelar" onclick="window.location.href='escolher.php?tipo_produto=<?php echo urlencode($_SESSION['tipo_produto']); ?>'">Voltar</button>
          <button class="op-btn fazer">Ir para pagamento</button>
        </div>
      </div>
    </footer>

</body>
</html>
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
    main {
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
          <?php 
            $_SESSION['total'] = $total;
            $_SESSION['carrinho'] = $carrinho;
          ?>
            <div class="div-produto-carrinho">
              <img
                class="imagem-produto-carrinho"
                src="<?php echo '../' . htmlspecialchars($produto['imagem_produto']); ?>"
                alt="<?php echo htmlspecialchars($produto['nome_produto']); ?>" />
              <div class="info-produto">
                <h3><?php echo htmlspecialchars($produto['nome_produto']); ?></h2>
                  <?php if (!empty($produto['nome_variacao'])): ?>
                    <p><?php echo htmlspecialchars($produto['nome_variacao']); ?></p>
                  <?php endif; ?>
              </div>
              <div class='preco-produto'>
                <p>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></h3>
              </div>
              <div class="controle-qtd">
                <span class='qte-menos'>-</span>
                <input type='number' class='qte-input' name='qte[<?php echo $produto['cod_variacao'] ?>]' value='<?php echo $produto['qte']; ?>' min="1" data-preco="<?php echo $produto['preco']; ?>" />
                <span class='qte-mais'>+</span>
              </div>
            </div>
          <?php endforeach; ?>
          <div class="total-carrinho">
            <h2>Total: R$</h2>
            <h2 class="total-valor"><?php echo number_format($total, 2, ',', '.'); ?></h2>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </main>
  <footer>
    <img src="../img/Logo.png" alt="logo" class="logo" />
    <div class="op">
      <div class="total">
        <h2>Total: R$ <span id="footer-total"><?php echo number_format($total, 2, ',', '.'); ?></span></h2>
      </div>
      <div class="baixo">
        <button class="op-btn cancelar" onclick="window.location.href='escolher.php?tipo_produto=<?php echo urlencode($_SESSION['tipo_produto']); ?>'">Voltar</button>
        <button class="op-btn fazer" onclick="window.location.href='pagamento.php?tipo_produto=<?php echo urlencode($_SESSION['tipo_produto']); ?>'">Ir para pagamento</button>
      </div>
    </div>
  </footer>

  <script>
    // Função para formatar número como moeda BRL
    function formatarMoeda(valor) {
      return valor.toLocaleString('pt-BR', { // vai retornar o numero formatado para o padrão brasileiro
        minimumFractionDigits: 2, // Garante que sempre terá duas casas decimais
        maximumFractionDigits: 2 // Garante que nunca terá mais que duas casas decimais
      });
    }

    // Função DOM para atualizar o total e fazer os inputs de quantidade dinâmicos

    // Pega todos os elementos de produto no carrinho
    document.querySelectorAll('.div-produto-carrinho').forEach(function(produtoDiv) {
      const menos = produtoDiv.querySelector('.qte-menos'); //Variável para o botão de menos
      const mais = produtoDiv.querySelector('.qte-mais'); // Variável para o botão de mais
      const input = produtoDiv.querySelector('.qte-input'); // Input de quantidade


      // Função para atualizar o total na div e também no footer
      function atualizarTotalGeral() {
        let total = 0; // Variável para o total
        // Percorre todos os produtos no carrinho
        document.querySelectorAll('.div-produto-carrinho').forEach(function(div) {
          const input = div.querySelector('.qte-input'); // Pega o input de quantidade
          const preco = parseFloat(input.getAttribute('data-preco')); // Pega o preço do produto
          let qte = parseInt(input.value, 10); // Pega a quantidade do input transformada em inteiro e no sistema decimal
          if (isNaN(qte) || qte < 1) qte = 1; // Garante que a quantidade é pelo menos 1
          total += preco * qte; // Atualiza o total com o preço do produto multiplicado pela quantidade
        });
        // Atualiza o texto do total na div e no footer
        document.querySelectorAll('.total-valor, #footer-total').forEach(function(el) {
          el.textContent = formatarMoeda(total); //Chama a função de formatação de moeda
        });
      }

      // Evento que atualiza a quantidade e o total quando os botões de mais é clicado
      mais.addEventListener('click', function() {
        let valor = parseInt(input.value, 10); // Pega o valor atual do input
        valor++; // Aumenta a qte
        input.value = valor; // Atualiza o valor do input
        atualizarTotalGeral(); // Atualiza o total geral
        atualizarCarrinhoNoServidor(input); // Função que atualiza o carrinho no servidor(ainda não implementada)
      });

      // Evento que atualiza a quantidade e o total quando os botões de menos é clicado
      menos.addEventListener('click', function() {
        let valor = parseInt(input.value, 10);
        if (valor > 1) {
          valor--; // Se for maior que 1, diminui a qte
          input.value = valor; //Atualiza o valor do input
          atualizarTotalGeral(); // Atualiza o total geral
          atualizarCarrinhoNoServidor(input); // Função que atualiza o carrinho no servidor(ainda não implementada)
        }
      });
    });
  </script>

</body>

</html>
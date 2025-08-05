<?php
include_once 'php/conexao.php';
session_start();

if (!isset($_SESSION['cod_comanda'])) {
  header("Location: index.php");
  exit();
}


if (!isset($_SESSION['carrinho'])) {
  $_SESSION['carrinho'] = [];
}


$carrinho = $_SESSION['carrinho'] ?? [];
$total = $_SESSION['carrinho_total'] ?? 0.0;


$total = 0.0;
foreach ($carrinho as $produto) {
  $subtotal = $produto['preco'] * $produto['qte'];
  $total += $subtotal;
}
$_SESSION['carrinho_total'] = $total;
?>
<!DOCTYPE html>
<html lang="pt-BR">


<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/carrinho.css" />
  <link rel="icon" type="image/png" href="../img/Comes-_1_.ico">
  <title>Carrinho</title>


</head>


<body>
  <main style="margin-left: 0;">
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
              <div class="acoes-produto">
                <span class="preco-produto">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></span>
                <div class="controle-qtd">
                  <button type='button' class='qte-menos'>-</button>
                  <input type='number' class='qte-input' name='qte[<?php echo $produto['cod_variacao'] ?>]' value='<?php echo $produto['qte']; ?>' min="1" data-preco="<?php echo $produto['preco']; ?>" />
                  <button type='button' class='qte-mais'>+</button>
                </div>
                <button class="btn-remover-produto" data-cod-variacao="<?php echo $produto['cod_variacao']; ?>" title="Remover este produto">🗑️</button>
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
        <button class="op-btn fazer" onclick="window.location.href='pagamento.php?tipo_produto=<?php echo urlencode($_SESSION['tipo_produto']); ?>'">Pagar</button>
      </div>
    </div>
  </footer>

  <div id="modal-confirmar-remocao" class="modal-remocao">
    <div class="modal-conteudo">
      <h2>Remover produto</h2>
      <p>Tem certeza de que deseja remover este produto do carrinho?</p>
      <div class="modal-botoes">
        <button id="btn-cancelar-remocao" class="btn-cancelar">Cancelar</button>
        <button id="btn-confirmar-remocao" class="btn-confirmar">Remover</button>
      </div>
    </div>
</div>

  <!-- Script para utilizar o jquery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>




  <script>
    // Função para formatar número como moeda BRL
    function formatarMoeda(valor) {
      return valor.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });
    }



    // Adiciona eventos de clique para os botões de mais e menos
    document.querySelectorAll('.div-produto-carrinho').forEach(function(produtoDiv) {
      // Variaveis dos botões e input
      const menos = produtoDiv.querySelector('.qte-menos');
      const mais = produtoDiv.querySelector('.qte-mais');
      const input = produtoDiv.querySelector('.qte-input');


      // Evento quando apertar o botão "+"
      mais.addEventListener('click', function() {
        let valor = parseInt(input.value, 10) || 1;
        valor++;
        input.value = valor;
        atualizarCarrinhoNoServidor(input);
      });


      // Evento quando apertar o botão "-"
      menos.addEventListener('click', function() {
        let valor = parseInt(input.value, 10) || 1;
        if (valor > 1) {
          valor--;
          input.value = valor;
          atualizarCarrinhoNoServidor(input);
        }
      });


      // Função AJAX usando jQuery
      function atualizarCarrinhoNoServidor(input) {
        const codVariacao = input.name.match(/\d+/)[0];
        const qte = parseInt(input.value, 10);


        $.ajax({
          url: 'php/atualizar_carrinho.php',
          type: 'POST',
          data: {
            cod_variacao: codVariacao,
            qte: qte
          },
          dataType: 'json',
          success: function(response) {
            if (response && response.success) {
              document.querySelectorAll('.total-valor, #footer-total').forEach(function(el) {
                el.textContent = formatarMoeda(response.total);
              });
            } else {
              alert('Erro ao atualizar o carrinho!');
            }
          },
          error: function() {
            alert('Erro ao comunicar com o servidor!');
          }
        });
      }


    });


    let codVariacaoSelecionado = null;
    let produtoParaRemover = null;

    $(document).on('click', '.btn-remover-produto', function (e) {
      e.preventDefault();
      codVariacaoSelecionado = $(this).data('cod-variacao');
      produtoParaRemover = $(this).closest('.div-produto-carrinho');
      $('#modal-confirmar-remocao').fadeIn();
    });

    // Botão "Cancelar"
    $('#btn-cancelar-remocao').on('click', function () {
      $('#modal-confirmar-remocao').fadeOut();
      codVariacaoSelecionado = null;
      produtoParaRemover = null;
    });

    // Botão "Confirmar"
    $('#btn-confirmar-remocao').on('click', function () {
      if (!codVariacaoSelecionado) return;

      $.ajax({
        url: 'php/apagar_produto.php',
        type: 'POST',
        data: {
          cod_variacao: codVariacaoSelecionado
        },
        dataType: 'json',
        success: function (response) {
          if (response.success) {
            $('.total-valor, #footer-total').text(response.total_formatado);
            produtoParaRemover.fadeOut(300, function () {
              $(this).remove();
              if ($('.div-produto-carrinho').length === 0) {
                $('.div-carrinho').html("<p style='text-align: center;'>Seu carrinho está vazio.</p>");
              }
            });
          } else {
            alert(response.message || 'Erro ao remover o produto');
          }
        },
        error: function () {
          alert('Erro na comunicação com o servidor');
        },
        complete: function () {
          $('#modal-confirmar-remocao').fadeOut();
          codVariacaoSelecionado = null;
          produtoParaRemover = null;
        }
      });
    });

  </script>


</body>


</html>
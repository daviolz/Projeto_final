<?php
session_start();
header('Content-Type: application/json');


// Debug (pode remover depois)
file_put_contents('debug_remove.txt',
   "POST: " . print_r($_POST, true) . "\n" .
   "SESSION antes: " . print_r($_SESSION['carrinho'] ?? [], true),
FILE_APPEND);


if (!isset($_SESSION['carrinho'])) {
   $_SESSION['carrinho'] = [];
}


$response = ['success' => false, 'message' => ''];


if (!isset($_POST['cod_variacao'])) {
   $response['message'] = 'Código não recebido';
   echo json_encode($response);
   exit;
}


$cod = $_POST['cod_variacao'];
$carrinhoAtualizado = [];


// Filtra o carrinho mantendo todos exceto o item a ser removido
foreach ($_SESSION['carrinho'] as $item) {
   if ($item['cod_variacao'] != $cod) {
       $carrinhoAtualizado[] = $item;
   }
}


// Verifica se algum item foi removido
if (count($carrinhoAtualizado) < count($_SESSION['carrinho'])) {
   $_SESSION['carrinho'] = $carrinhoAtualizado;
  
   // Recalcula o total
   $novoTotal = 0.0;
   foreach ($_SESSION['carrinho'] as $item) {
       $novoTotal += $item['preco'] * $item['qte'];
   }
   $_SESSION['carrinho_total'] = $novoTotal;
  
   $response = [
       'success' => true,
       'total' => $novoTotal,
       'total_formatado' => number_format($novoTotal, 2, ',', '.'),
       'message' => 'Item removido com sucesso'
   ];
} else {
   $response['message'] = 'Item não encontrado no carrinho';
}


// Debug (pode remover depois)
file_put_contents('debug_remove.txt',
   "SESSION depois: " . print_r($_SESSION['carrinho'] ?? [], true) . "\n" .
   "Resposta: " . print_r($response, true),
FILE_APPEND | FILE_APPEND);


echo json_encode($response);
exit;

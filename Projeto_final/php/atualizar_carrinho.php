<?php
session_start();
header('Content-Type: application/json');

// Garante que o carrinho existe
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

$cod_variacao = isset($_POST['cod_variacao']) ? $_POST['cod_variacao'] : null;
$qte = isset($_POST['qte']) ? intval($_POST['qte']) : null;

if ($cod_variacao === null || $qte === null || $qte < 1) {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
    exit;
}

// Atualiza a quantidade do item no carrinho
foreach ($_SESSION['carrinho'] as &$item) {
    if ($item['cod_variacao'] == $cod_variacao) {
        $item['qte'] = $qte;
        break;
    }
}
unset($item); // Boa prática ao usar referência em foreach
// Recalcula o total do carrinho
$total = 0.0;
foreach ($_SESSION['carrinho'] as $item) {
    $total += $item['preco'] * $item['qte'];
}
$_SESSION['carrinho_total'] = $total;

// Retorna o novo total em JSON
echo json_encode([
    'success' => true,
    'total' => $total
]);
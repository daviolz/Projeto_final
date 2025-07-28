<?php
// Inclui o script de proteção para garantir que só usuários autorizados acessem
include_once("protect.php");

// Verifica se o usuário tem nível 1 (admin). Se não tiver, exibe alerta e redireciona.
if ($_SESSION['nivel'] != 1) {
    echo "<script>alert('Você não tem permissão para acessar essa página!'); window.location.href='../home.php';</script>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once("conexao.php");


    $cod_produto = $_POST['cod_produto'];           // Código do produto selecionado
    $nome_variacao = $_POST['nome_variacao'];       // Nome da variação informada
    $preco = $_POST['preco'];                       // Preço informado

    // Prepara a query para inserir a nova variação no banco de dados (usando prepared statement para segurança)
    $query = "INSERT INTO Produto_Variacao (Cod_produto, Nome_variacao, Preco) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    // Associa os parâmetros à query: inteiro, string, double
    $stmt->bind_param("isd", $cod_produto, $nome_variacao, $preco);

    // Executa a query e verifica se deu certo
    if ($stmt->execute()) {
        // Se cadastrou com sucesso, mostra alerta e volta para a tela de cadastro
        echo "<script>alert('Variação cadastrada com sucesso!'); window.location.href='../cadastrar_variacao.php';</script>";
    } else {
        // Se deu erro, mostra alerta de erro
        echo "<script>alert('Erro ao cadastrar variação.');</script>";
    }
}
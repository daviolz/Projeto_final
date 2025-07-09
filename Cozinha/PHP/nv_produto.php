<?php
include_once("protect.php");

if ($_SESSION['nivel'] != 1) {
    echo "<script>alert('Você não tem permissão para acessar essa página!'); window.location.href='home.php';</script>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once("conexao.php");

    $nome = $_POST['nome'];
    $tipo = $_POST['tipo'];
    $descricao = $_POST['descricao'];
    $imagem = $_FILES['imagem'];

    // Verifica se a imagem foi enviada
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == UPLOAD_ERR_OK) {
        $imagem = $_FILES['imagem'];
        $nomeTemp = $imagem['tmp_name'];
        $nomeOriginal = $imagem['name'];
        $extensao = pathinfo($nomeOriginal, PATHINFO_EXTENSION);

        // Gera um nome de arquivo seguro baseado no nome do produto
        $nomeArquivo = preg_replace('/[^a-zA-Z0-9-_]/', '_', $nome); // substitui caracteres especiais por "_"
        $novoNome = $nomeArquivo . '.' . $extensao;

        $caminho = '../../img/';
        if (!is_dir($caminho)) {
            mkdir($caminho, 0777, true);
        }
        move_uploaded_file($nomeTemp, $caminho . $novoNome);
        $caminho_imagem = 'img/' . $novoNome;
    } else {
        $caminho_imagem = null;
    }

    // Insere os dados no banco de dados
    $sql = "INSERT INTO Produto (Nome_produto, Tipo_produto,Descricao_produto, Imagem_produto) VALUES (?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ssss", $nome, $tipo, $descricao, $caminho_imagem);

    if ($stmt->execute()) {
        echo "<script>alert('Produto cadastrado com sucesso!'); window.location.href='../home.php';</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar produto.');</script>";
    }
}
?>





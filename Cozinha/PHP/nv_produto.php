<?php
// Inclui o script de proteção para garantir que só usuários autorizados acessem
include_once("protect.php");

// Verifica se o usuário tem nível 1 (admin). Se não tiver, exibe alerta e redireciona.
if ($_SESSION['nivel'] != 1) {
    echo "<script>alert('Você não tem permissão para acessar essa página!'); window.location.href='home.php';</script>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once("conexao.php");

    $nome = $_POST['nome'];             // Nome do produto
    $tipo = $_POST['tipo'];             // Tipo do produto (bebida, salgado, etc)
    $descricao = $_POST['descricao'];   // Descrição do produto
    $imagem = $_FILES['imagem'];        // Dados do arquivo de imagem enviado

    // Verifica se a imagem foi enviada sem erro
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == UPLOAD_ERR_OK) {
        $imagem = $_FILES['imagem'];
        $nomeTemp = $imagem['tmp_name'];
        $nomeOriginal = $imagem['name'];
        $extensao = pathinfo($nomeOriginal, PATHINFO_EXTENSION);

        // Verifica se o arquivo é realmente uma imagem
        $info = getimagesize($nomeTemp);
        if ($info === false) {
            echo "<script>alert('O arquivo enviado não é uma imagem válida.'); window.history.back();</script>";
            exit;
        }

        $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'webp']; // adicione as que quiser permitir

        if (!in_array(strtolower($extensao), $extensoes_permitidas)) {
            echo "<script>alert('Tipo de imagem não permitido. Envie JPG, PNG, GIF, BMP ou WEBP.'); window.history.back();</script>";
            exit;
        }

        // Gera um nome de arquivo seguro baseado no nome do produto, substituindo caracteres especiais por "_"
        $nomeArquivo = preg_replace('/[^a-zA-Z0-9-_]/', '_', $nome);
        $novoNome = $nomeArquivo . '.' . $extensao;

        // Define o caminho para salvar a imagem (fora da pasta PHP)
        $caminho = '../../img/';
        // Cria a pasta se não existir
        if (!is_dir($caminho)) {
            mkdir($caminho, 0777, true);
        }
        // Move o arquivo enviado para o destino final
        move_uploaded_file($nomeTemp, $caminho . $novoNome);
        // Caminho relativo para salvar no banco de dados
        $caminho_imagem = 'img/' . $novoNome;
    } else {
        // Se não enviou imagem, salva como null
        $caminho_imagem = null;
    }

    // Prepara a query para inserir o novo produto no banco de dados
    $sql = "INSERT INTO Produto (Nome_produto, Tipo_produto,Descricao_produto, Imagem_produto) VALUES (?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    // Associa os parâmetros à query (todos string)
    $stmt->bind_param("ssss", $nome, $tipo, $descricao, $caminho_imagem);

    // Executa a query e verifica se deu certo
    if ($stmt->execute()) {
        // Se cadastrou com sucesso, mostra alerta e redireciona para home
        echo "<script>alert('Produto cadastrado com sucesso!'); window.location.href='../home.php';</script>";
    } else {
        // Se deu erro, mostra alerta de erro
        echo "<script>alert('Erro ao cadastrar produto.');</script>";
    }
}
?>





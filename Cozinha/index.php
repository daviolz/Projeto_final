<?php
// Faz conexão com o banco de dados
include("PHP/conexao.php");

// Inicializa um array para armazenar mensagens de erro
$erro = [];

// Verifica se o formulário foi enviado e se o campo 'login' não está vazio
if (isset($_POST['login']) && strlen($_POST['login']) > 0) {

  // Inicia a sessão caso ainda não tenha sido iniciada
  if (!isset($_SESSION)) {
    session_start();

    // Salva o login na sessão, escapando caracteres especiais para evitar SQL Injection
    $_SESSION['login'] = $conexao->escape_string($_POST['login']);
    // Salva a senha na sessão (sem escape, pois será comparada diretamente)
    $_SESSION['senha'] = $_POST['senha'];

    // Monta a query para buscar o usuário pelo login informado
    $sql_code = "SELECT Senha, Cod_usuario, Nivel FROM Usuario WHERE Login = '{$_SESSION['login']}'";
    // Executa a query no banco de dados
    $sql_query = $conexao->query($sql_code) or die($conexao->error);
    // Busca os dados do usuário encontrado
    $dado = $sql_query->fetch_assoc();
    // Conta quantos usuários foram encontrados (deve ser 0 ou 1)
    $total = $sql_query->num_rows;

    // Se não encontrou nenhum usuário com esse login, adiciona mensagem de erro
    if ($total == 0) {
      $erro[] = "Não há usuários com este Login.";
    } else {
      // Se encontrou, verifica se a senha está correta
      if ($dado['Senha'] == $_SESSION['senha']) {
        // Se a senha está correta, salva o código do usuário e o nível de acesso na sessão
        $_SESSION['usuario'] = $dado['Cod_usuario'];
        $_SESSION['nivel'] = $dado['Nivel'];
      } else {
        // Se a senha está incorreta, adiciona mensagem de erro
        $erro[] = "Senha incorreta";
      }
    }

    // Se não houve erros, redireciona para a página home.php
    if (count($erro) == 0 || !isset($erro)) {
      echo "<script>location.href='home.php';</script>";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- Icone da Comes & Bebs -->
  <link rel="icon" type="image/png" href="../img/Comes-_1_.ico">
  <!-- Titulo da Pagina -->
  <title>Login</title>
  <!-- CSS do projeto -->
  <link rel="stylesheet" href="CSS/style.css" />
  <!-- Importa ícones da biblioteca Boxicons -->
  <link
    href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
    rel="stylesheet" />
</head>

<body>
  <?php
  // Se houver mensagens de erro, exibe cada uma em um alert do JavaScript
  if (count($erro) > 0) {
    foreach ($erro as $msg) {
      echo "<script>alert('$msg')</script>";
    }
  }
  ?>
  <!-- Div do formulario de Login -->
  <div class="wrapper">
    <!-- Container do formulario de Login -->
    <div class="container-login">
      <!-- Formulário de login -->
      <form action="" method="POST">
        <h1>Login</h1>
        <div class="input-box">
          <!-- Campo de login, mantém o valor digitado após envio -->
          <input type="text" placeholder="Login" value="<?php if (isset($_POST['login'])) echo $_SESSION['login']; ?>" name="login" required />
          <i class="bx bxs-user"></i>
        </div>
        <div class="input-box">
          <!-- Campo de senha -->
          <input type="password" placeholder="Senha" name="senha" required />
          <i class="bx bxs-lock-alt"></i>
        </div>
        <!-- Botão para enviar o formulário -->
        <button type="submit" class="btn">Entrar</button>
      </form>
    </div>
  </div>
</body>

</html>
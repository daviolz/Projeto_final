<?php
include("PHP/conexao.php");

$erro = [];

if (isset($_POST['login']) && strlen($_POST['login']) > 0) {

  if (!isset($_SESSION)) {
    session_start();

    $_SESSION['login'] = $conexao->escape_string($_POST['login']);
    $_SESSION['senha'] = $_POST['senha'];

    $sql_code = "SELECT Senha, Cod_usuario, Nivel FROM Usuario WHERE Login = '{$_SESSION['login']}'";
    $sql_query = $conexao->query($sql_code) or die($conexao->error);
    $dado = $sql_query->fetch_assoc();
    $total = $sql_query->num_rows;

    if ($total == 0) {
      $erro[] = "Não há usuários com este Login.";
    } else {
      if ($dado['Senha'] == $_SESSION['senha']) {
        $_SESSION['usuario'] = $dado['Cod_usuario'];
        $_SESSION['nivel'] = $dado['Nivel'];
      } else {
        $erro[] = "Senha incorreta";
      }
    }

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
  <title>Login</title>
  <link rel="stylesheet" href="CSS/style.css" />
  <link
    href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
    rel="stylesheet" />
</head>

<body>
  <?php
  if (count($erro) > 0) {
    foreach ($erro as $msg) {
      echo "<script>alert('$msg')</script>";
    }
  }
  ?>

  <div class="wrapper">
    <div class="container-login">
      <form action="" method="POST">
        <h1>Login</h1>
        <div class="input-box">
          <input type="text" placeholder="Login" value="<?php if (isset($_POST['login'])) echo $_SESSION['login']; ?>" name="login" required />
          <i class="bx bxs-user"></i>
        </div>
        <div class="input-box">
          <input type="password" placeholder="Senha" name="senha" required />
          <i class="bx bxs-lock-alt"></i>
        </div>
        <button type="submit" class="btn">Entrar</button>
      </form>
    </div>
  </div>
</body>

</html>
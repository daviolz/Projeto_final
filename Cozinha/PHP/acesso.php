<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../CSS/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <title>Por favor aguarde...</title>

  <script language="javascript">
    function sucessoAdm() {
      setTimeout("window.location='..//cabeçalho.php'", 2000);
    }
  </script>
</head>

<body>
  <?php
  include_once("conexao.php");

  $login = $_POST['login'];
  $senha = $_POST['senha'];

  $consulta = mysqli_query($conexao, "SELECT Cod_usuario, Nivel FROM Usuario WHERE Login = '$login' AND Senha = '$senha'");
  $resultado = mysqli_fetch_assoc($consulta);

  if ($resultado) {
    $cod_usuario = $resultado['Cod_usuario'];
    $nivel = $resultado['Nivel'];


    // Login bem-sucedido, define as variáveis de sessão
    $_SESSION["login"] = $login;
    $_SESSION["senha"] = $senha;
    $_SESSION["cod_usuario"] = $cod_usuario;

    // Redireciona para a página correspondente ao nível
    if ($nivel == 1) {
      echo "<div class='d-flex justify-content-center align-items-center' style='height: 100vh;'>
              <h2 class='text-center text-light pe-4'>Por favor aguarde</h2>
              <div class='spinner-border text-light' role='status'>
                <span class='visually-hidden'>Loading...</span>
              </div>";
      echo "<script>sucesso()</script>";
    } elseif ($nivel == 2) {
      echo "<div class='d-flex justify-content-center align-items-center' style='height: 100vh;'>
              <h2 class='text-center text-light pe-4'>Por favor aguarde</h2>
              <div class='spinner-border text-light' role='status'>
                <span class='visually-hidden'>Loading...</span>
              </div>";
      echo "<script>sucesso2()</script>";
    }
  } else {
    // Login ou senha incorretos
    echo "<script>
            alert('Login ou senha incorretos, tente novamente!');
            window.location = '../login.php';
          </script>";
    exit();
  }
  ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
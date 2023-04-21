<?php

session_start();

require_once("./db/Conection.php");

if (isset($_SESSION["logado"]) &&  $_SESSION["logado"] == true) {
  header("location: ./index.php");
  die;
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = $_POST["email"];
  $password = $_POST["password"];

  if (empty($email)) {
    $_SESSION["error"] = "POR FAVOR DIGITAR EMAIL VALIDO";
    header("location: ./login.php");
    die;
  }

  if (empty($password)) {
    $_SESSION["error"] = "PREENCHER COM A SENHA";
    header("location: ./login.php");
    die;
  }

  try {
    $sql = "SELECT id, email, password, nome FROM pessoas WHERE email = :email";
    $stmt = $oConection->prepare($sql);

    $stmt->bindParam(":email", $email);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
      $user = $stmt->fetch();
      $id = $user->id;
      $email = $user->email;
      $password_hash = $user->password;
      $nome = $user->nome;

      if (password_verify($password, $password_hash)) {
        $_SESSION["logado"] = true;
        $_SESSION["id"] = $id;
        $_SESSION["email"] = $email;
        $_SESSION["nome"] = $nome;
        header("location: ./index.php");
      } else {
        $_SESSION["error"] = "NOME OU SENHA INCORRETO";
      }
    } else {
      $_SESSION["error"] = "EMAIL NAO CADASTRADO";
    }
  } catch (\PDOException $e) {
    $_SESSION["error"] = "ERRO: " . $e->getMessage() . $e->getTraceAsString();
  }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" />
  <!-- CSS  -->
  <link rel="stylesheet" href="public/css/styles.css">
</head>

<body>

  <section class="vh-100">
    <div class="container-fluid">
      <?php if (isset($_SESSION["error"])) : ?>
        <div class="alert alert-danger text-center">
          <?= $_SESSION["error"];
          unset($_SESSION["error"]);
          ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif ?>
      <?php if (isset($_SESSION["cadastrado"])) { ?>
        <div class="alert alert-success text-center">
          <?= $_SESSION["cadastrado"];
          unset($_SESSION["cadastrado"]);
          ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php } ?>
      <div class="row">
        <div class="col-sm-6 text-black">
          <div class="px-5 ms-xl-4">
            <i class="fas fa-crow fa-2x me-3 pt-5 mt-xl-4" style="color: #709085;"></i>
            <span class="h1 fw-bold mb-0">
              <img class="img-fluid w-50 mt-5 mb-0" src="public/img/kairos.png" alt="">
            </span>
          </div>

          <div class="d-flex align-items-center h-custom-2 px-5 ms-xl-4 mt-3 pt-2 pt-xl-0">

            <form action="./login.php" method="POST" style="width: 23rem;">

              <h3 class="fw-normal mb-3 pb-3 text-center" style="letter-spacing: 1px;">Login</h3>

              <div class="form-outline mb-4">
                <input type="email" id="form2Example18" class="form-control form-control-lg" name="email" required />
                <label class="form-label" for="form2Example18">Email</label>
              </div>

              <div class="form-outline mb-4">
                <input type="password" id="form2Example28" class="form-control form-control-lg" name="password" required />
                <label class="form-label" for="form2Example28">Senha</label>
              </div>

              <div class="pt-1 mb-4">
                <button class="btn btn-primary btn-lg btn-block" type="submit">Login</button>
              </div>

              <p class="small mb-5 pb-lg-2"><a class="text-muted" href="#!">Esqueceu senha?</a></p>
              <p>Não tem conta? <a href="./app/Create.php" class="link-info">Registre aqui</a></p>

            </form>

          </div>

        </div>
        <div class="col-sm-6 px-0 d-none d-sm-block">
          <img src="https://mdbootstrap.com/img/new/ecommerce/vertical/004.jpg" alt="Login image" class="w-100 vh-100" style="object-fit: cover; object-position: left;">
        </div>
      </div>

    </div>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

</body>

</html>
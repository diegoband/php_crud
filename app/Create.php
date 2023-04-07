<?php

session_start();

require_once("../db/Conection.php");


try {
  $sSql = "SELECT * from estado";
  $stmt = $oConection->prepare($sSql);
  $stmt->execute();
  $estados = $stmt->fetchAll();
} catch (\PDOException $e) {
  $_SESSION["error"] = "NAO EXISTE DADOS NO GET " . $e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $sName = isset($_POST["name"]) ? $_POST["name"] : "";
  $sEmail = isset($_POST["email"]) ? $_POST["email"] : "";
  $sPassword = isset($_POST["password"]) ? $_POST["password"] : "";
  $sConfirmPassword = isset($_POST["confirmPassword"]) ? $_POST["confirmPassword"] : "";
  $sAddress = isset($_POST["address"]) ? $_POST["address"] : "";
  $sNumberHouse = isset($_POST["numberHouse"]) ? $_POST["numberHouse"] : "";
  $sCity = isset($_POST["city"]) ? $_POST["city"] : "";
  $iState = isset($_POST["state_id"]) ? $_POST["state_id"] : "";
  $sCep = isset($_POST["cep"]) ? $_POST["cep"] : "";

  if (empty($sName) || empty($sEmail) || empty($sPassword)) {

    $_SESSION["error"] = "Existem campos que sao necessarios";
    header("location: ./Create.php");
    die;
  } else if ($sPassword !== $sConfirmPassword) {
    $_SESSION["error"] = "As senha precisam ser iguais";
    header("location: ./Create.php");
    die;
  }

  $sPassword = md5($sPassword);
  $sConfirmPassword = md5($sConfirmPassword);

  try {
    $sSql = "INSERT INTO pessoas (nome, email, password, confirm_password, address, number_house, city, state_id, cep) VALUES (?,?,?,?,?,?,?,?,?)";
    $stmt = $oConection->prepare($sSql);
    $stmt->execute([
      $sName,
      $sEmail,
      $sPassword,
      $sConfirmPassword,
      $sAddress,
      $sNumberHouse,
      $sCity,
      $iState,
      $sCep
    ]);

    if ($stmt->rowCount()) {
      $_SESSION["cadastrado"] = "CADASTRO REALIZADO COM SUCESSO";
      header("location:../index.php");
      die();
    }
  } catch (\PDOException $e) {
    $_SESSION["error"] = "Erro ao inserir os dados " . $e->getMessage();
  }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro de usuario</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" />
  <!-- CSS  -->
  <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
  <div class="container">

    <?php if (isset($_SESSION["cadastrado"])) { ?>
      <div class="alert alert-success text-center">
        <?= $_SESSION["cadastrado"];
        unset($_SESSION["cadastrado"]);
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php } ?>

    <?php if (isset($_SESSION["error"])) : ?>
      <div class="alert alert-danger">
        <?= $_SESSION["error"];
        unset($_SESSION["error"]);
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif ?>

    <div class="d-flex justify-content-between">
      <h2 class="text-center">Cadastro de usuarios</h2>
      <a href="/index.php" class="btn btn-primary btn-md">Voltar</a>
    </div>

    <form action="Create.php" class="row g-3" method="POST">
      <div class="col-md-6">
        <label for="inputName" class="form-label">Nome *</label>
        <input type="text" class="form-control" id="inputName" name="name">
      </div>
      <div class="col-md-6">
        <label for="inputEmail" class="form-label">Email *</label>
        <input type="email" class="form-control" id="inputEmail" name="email">
      </div>
      <div class="col-md-6">
        <label for="inputPassword" class="form-label">Password *</label>
        <input type="password" class="form-control" id="inputPassword" name="password">
      </div>
      <div class="col-md-6">
        <label for="confirmPassword" class="form-label">Confirme sua senha *</label>
        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword">
      </div>
      <div class="col-12">
        <label for="inputAddress" class="form-label">Logradouro</label>
        <input type="text" class="form-control" id="inputAddress" placeholder="Rua XXXXXX" name="address">
      </div>
      <div class="col-4">
        <label for="inputNumber" class="form-label">Numero</label>
        <input type="text" class="form-control" id="inputNumber" placeholder="Numero da casa" name="numberHouse">
      </div>
      <div class="col-md-4">
        <label for="inputCity" class="form-label">Cidade</label>
        <input type="text" class="form-control" id="inputCity" name="city">
      </div>
      <div class="col-md-4">
        <label for="inputState" class="form-label">Estado</label>
        <select id="inputState" class="form-select" name="state_id">
          <?php foreach ($estados as $estado) : ?>
            <option value="<?= $estado->id ?>"><?= $estado->nome . " - " . $estado->uf; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-6">
        <label for="inputZip" class="form-label">Cep</label>
        <input type="text" class="form-control" id="inputZip" name="cep">
      </div>
      <div class="col-6 d-flex align-items-end">
        <button type="submit" class="btn btn-primary">Cadastrar</button>
      </div>
    </form>
  </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

</html>
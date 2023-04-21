<?php

session_start();

require_once("../db/Conection.php");


if (!isset($_SESSION["logado"]) &&  $_SESSION["logado"] !== true) {
  header("location: ./login.php");
  die;
}


try {
  $sSql = "SELECT estado.id, estado.nome, estado.uf FROM estado";
  $stmt = $oConection->prepare($sSql);
  $stmt->execute();
  $estados = $stmt->fetchAll();

  $sSql = "SELECT * from genero";
  $stmt = $oConection->prepare($sSql);
  $stmt->execute();
  $generos = $stmt->fetchAll();
} catch (\PDOException $e) {
  $_SESSION["error"] = "NAO EXISTE DADOS NO GET " . $e->getMessage();
}

$dados = $_POST;

if ($_SERVER["REQUEST_METHOD"] == "GET") {

  if (isset($_GET["id"])) {
    $id = $_GET["id"];
  } else {
    $_SESSION["error"] = "PARAMETRO ID NECESSARIO ";
    header("Location: ../index.php");
  }

  try {
    $sSql = "SELECT * from pessoas WHERE id = :id";
    $stmt = $oConection->prepare($sSql);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $dados = $stmt->fetch();
  } catch (\PDOException $e) {
    $_SESSION["error"] = "NAO EXISTE DADOS NO GET " . $e->getMessage();
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $name = $dados["name"];
  $email = $dados["email"];
  $password = @$dados["password"];
  $confirm_password = @$dados["confirm_password"];
  $address = $dados["address"];
  $number_house = $dados["number_house"];
  $city = $dados["city"];
  $state = $dados["state_id"];
  $genero = $dados["genero_id"] ? $dados["genero_id"] : NULL;
  $cep = $dados["cep"];
  $id = $dados["id"];


  $sSql = "UPDATE pessoas SET nome = :name, email = :email, address = :address, number_house = :number_house, city = :city, state_id = :state, genero_id = :genero_id, cep = :cep WHERE id = :id";

  $stmt = $oConection->prepare($sSql);

  $stmt->bindParam(":name", $name);
  $stmt->bindParam(":email", $email);
  $stmt->bindParam(":address", $address);
  $stmt->bindParam(":number_house", $number_house);
  $stmt->bindParam(":city", $city);
  $stmt->bindParam(":state", $state);
  $stmt->bindParam(":genero_id", $genero);
  $stmt->bindParam(":cep", $cep);
  $stmt->bindParam(":id", $id);

  try {
    $stmt->execute();
    $_SESSION["atualizado"] = "CADASTRO ATUALIZADO COM SUCESSO";
    header("Location: ../index.php");
  } catch (\PDOException $e) {
    $_SESSION["error"] = "NAO ATUALIZOU OS DADOS " . $e->getMessage();
  }
}


// if (empty($dados["name"]) || empty($dados["email"])) {

//   $_SESSION["error"] = "Existem campos que sao necessarios";
//   header("location: ./Edit.php");
//   die;
// } else if (isset($dados["password"]) && $dados["password"] !== $dados["confirm_password"]) {
//   $_SESSION["error"] = "As senha precisam ser iguais";
//   header("location: ./Edit.php");
//   die;
// }


?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editando Contatos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" />
  <!-- CSS  -->
  <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
  <div class="container">
    <div class="d-flex justify-content-between">
      <h2 class="text-center">Editando usuarios</h2>
      <a href="/index.php" class="btn btn-primary btn-md">Voltar</a>
    </div>
    <form action="Edit.php" class="row g-3" method="POST">
      <input type="hidden" name="id" value="<?= $dados->id ?>">
      <div class="col-md-6">
        <label for="inputName" class="form-label">Nome</label>
        <input type="text" class="form-control" id="inputName" name="name" value="<?= @$dados->nome ?>">
      </div>
      <div class="col-md-6">
        <label for="inputEmail" class="form-label">Email</label>
        <input type="email" class="form-control" id="inputEmail" name="email" value="<?= @$dados->email ?>">
      </div>
      <div class="col-12">
        <label for="inputAddress" class="form-label">Endereço</label>
        <input type="text" class="form-control" id="inputAddress" placeholder="Rua X" name="address" value="<?= @$dados->address ?>">
      </div>
      <div class="col-4">
        <label for="inputNumber" class="form-label">Numero</label>
        <input type="text" class="form-control" id="inputNumber" placeholder="Numero da casa" name="number_house" value="<?= @$dados->number_house ?>">
      </div>
      <div class="col-md-4">
        <label for="inputCity" class="form-label">Cidade</label>
        <input type="text" class="form-control" id="inputCity" name="city" value="<?= @$dados->city ?>">
      </div>
      <div class=" col-md-4">
        <label for="inputState" class="form-label">Estado</label>
        <select id="inputState" class="form-select" name="state_id">
          <?php foreach ($estados as $estado) : ?>
            <?php if ($dados->state_id && $dados->state_id == $estado->id) : ?>
              <option selected value="<?= $estado->id ?>"><?= $estado->nome . " - " . $estado->uf; ?></option>
            <?php else : ?>
              <option value="<?= $estado->id ?>"><?= $estado->nome . " - " . $estado->uf; ?></option>
            <?php endif; ?>
          <?php endforeach; ?>
        </select>
      </div>
      <div class=" col-md-4">
        <label for="inputState" class="form-label">Genero</label>
        <select id="inputState" class="form-select" name="genero_id">
          <option value="">Defina seu genero</option>
          <?php foreach ($generos as $genero) : ?>
            <?php if ($dados->genero_id && $dados->genero_id == $genero->id) : ?>
              <option selected value="<?= $genero->id ?>"><?= $genero->descricao; ?></option>
            <?php else : ?>
              <option value="<?= $genero->id ?>"><?= $genero->descricao; ?></option>
            <?php endif; ?>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-6">
        <label for="inputZip" class="form-label">Cep</label>
        <input type="text" class="form-control" id="inputZip" name="cep" value="<?= @$dados->cep ?>">
      </div>
      <div class="col-6 d-flex align-items-end">
        <button type="submit" class="btn btn-primary">Atualizar dados</button>
      </div>
    </form>
  </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

</html>
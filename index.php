<?php
session_start();

require_once("./db/Conection.php");


if ($_SERVER["REQUEST_METHOD"] === "GET") {

  try {
    $sSql = "SELECT p.*, e.nome AS nome_estado, genero.descricao AS genero_descricao
      FROM pessoas p 
      LEFT JOIN estado e ON e.id = p.state_id
      LEFT JOIN genero ON genero.id = p.genero_id;";
    $stmt = $oConection->prepare($sSql);
    $stmt->execute();
    $aUsers = $stmt->fetchAll();
  } catch (\PDOException $e) {
    $_SESSION["error"] = "NAO EXISTE DADOS NO GET " . $e->getMessage();
  }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mostrar Dados</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" />
  <!-- CSS  -->
  <link rel="stylesheet" href="./css/styles.css">
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="/">DADOS PESSOAIS</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="./index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./app/Create.php">Criar</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./app/Edit.php">Editar</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="container">
    <?php if (isset($_SESSION["cadastrado"])) : ?>
      <div class="alert alert-success text-center">
        <?= $_SESSION["cadastrado"];
        unset($_SESSION["cadastrado"]);
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif ?>
    <?php if (isset($_SESSION["atualizado"])) : ?>
      <div class="alert alert-success text-center">
        <?= $_SESSION["atualizado"];
        unset($_SESSION["atualizado"]);
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif ?>
    <?php if (isset($_SESSION["error"])) : ?>
      <div class="alert alert-danger">
        <?= $_SESSION["error"];
        unset($_SESSION["error"]);
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif ?>
    <div class="d-flex justify-content-between">
      <h2 class="text-center">Listagem de usuarios</h2>
      <a href="./app/Create.php" class="btn btn-primary btn-md">Cadastrar</a>
    </div>
    <table class="table table-striped">
      <thead>
        <tr>
          <th scope="col">Id</th>
          <th scope="col">Nome</th>
          <th scope="col">Endereço</th>
          <th scope="col">Numero casa</th>
          <th scope="col">Cidade</th>
          <th scope="col">Estado</th>
          <th scope="col">Genero</th>
          <th scope="col">Cep</th>
          <th scope="col">Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($aUsers as $key => $oUser) : ?>
          <tr>
            <th scope="row">
              <?= $oUser->id; ?>
            </th>
            <td>
              <?= $oUser->nome; ?>
            </td>
            <td>
              <?= $oUser->address; ?>
            </td>
            <td>
              <?= $oUser->number_house; ?>
            </td>
            <td>
              <?= $oUser->city; ?>
            </td>
            <td>
              <?= $oUser->nome_estado; ?>
            </td>
            <td>
              <?= $oUser->genero_descricao; ?>
            </td>
            <td>
              <?= $oUser->cep; ?>
            </td>
            <td>
              <a href="./app/Show.php?id=<?= $oUser->id ?>"><i class="bi bi-eye"></i></a>
              <a href="./app/Edit.php?id=<?= $oUser->id ?>"><i class="bi bi-pencil-square"></i></a>
              <a href="./app/Delete.php?id=<?= $oUser->id ?>"><i class="bi bi-x-circle"></i></a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

</html>
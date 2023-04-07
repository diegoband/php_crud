<?php

session_start();

require_once("../db/Conection.php");


if ($_SERVER["REQUEST_METHOD"] == "GET") {

  if (isset($_GET["id"])) {
    $id = $_GET["id"];
  } else {
    $_SESSION["error"] = "PARAMETRO ID NECESSARIO ";
    header("Location: ../index.php");
  }

  try {
    $sSql = "SELECT pessoas.*, estado.nome nome_estado FROM pessoas LEFT JOIN estado ON estado.id = pessoas.state_id WHERE pessoas.id = :id";
    $stmt = $oConection->prepare($sSql);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $dados = $stmt->fetch();
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
  <title>Mostrar dados: <?= $dados->nome ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" />
  <!-- CSS  -->
  <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
  <div class="container d-flex justify-content-center">
    <div class="row">
      <h1>Dados pessoais</h1>
      <p class="bold">Nome pessoa:</p>
      <p><?= $dados->nome ?></p>
      <p class="bold">Endereço pessoa:</p>
      <p><?= $dados->address ?></p>
      <p class="bold">Cidade pessoa:</p>
      <p><?= $dados->city ?></p>
      <p class="bold">Estado pessoa:</p>
      <p><?= $dados->nome_estado ?></p>
      <p class="bold">Cep pessoa:</p>
      <p><?= $dados->cep ?></p>
      <div class="return">
        <button class="btn btn-info"><a href="/index.php">Voltar</a></button>
      </div>
    </div>

  </div>

</body>

</html>
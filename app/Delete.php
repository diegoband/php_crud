<?php

session_start();

require_once("../db/Conection.php");

$dados = $_GET;

if ($_SERVER["REQUEST_METHOD"] === "GET") {

  $id = $dados["id"];

  $sSql = "DELETE FROM pessoas WHERE id = :id";

  $stmt = $oConection->prepare($sSql);

  $stmt->bindParam(":id", $id);

  try {
    $stmt->execute();
    $_SESSION["atualizado"] = "CADASTRO DELETADO COM SUCESSO";
    header("Location: ../index.php");
  } catch (\PDOException $e) {
    $_SESSION["error"] = "NAO E POSSIVEL DELETAR " . $e->getMessage();
  }
}

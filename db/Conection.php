<?php

$sHost = "127.0.0.1";
$sDbname = "aulas";
$sUser = "root";
$sPassword = "";

try {
  $oConection = new PDO(
    "mysql:host={$sHost};dbname={$sDbname}",
    $sUser,
    $sPassword,
    [
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]
  );
} catch (\PDOException $e) {
  echo "Erro na conexao do banco de dados. {$e->getMessage()}";
}

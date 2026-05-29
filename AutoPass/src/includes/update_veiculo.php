<?php
require "dbconnect.php";

$modelo = $_GET["modelo"] ?? null;
$placa  = $_GET["placa"] ?? null;
$marca  = $_GET["marca"] ?? null;
$ano    = $_GET["ano"] ?? null;

if (!$placa) {
    header("Location: ../../Sessao_adm/veiculos.php");
    exit;
}

try {

    $sql = "UPDATE veiculos 
            SET modelo = :modelo,
                marca = :marca,
                ano = :ano
            WHERE placa = :placa";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
        ":modelo" => $modelo,
        ":marca"  => $marca,
        ":ano"    => $ano,
        ":placa"  => $placa
    ]);


    header("Location: ../../Sessao_adm/veiculos.php");
    exit;

} catch (Exception $e) {

    // se der erro também volta
    header("Location: ../../Sessao_adm/veiculos.php");
    exit;
}
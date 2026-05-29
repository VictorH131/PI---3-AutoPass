<?php

require "dbconnect.php";

$placa  = $_POST["placa"] ?? null;
$status = $_POST["status"] ?? null;

if (!$placa || !$status) {
    header("Location: ../../Sessao_adm/veiculos.php");
    exit;
}

if (!in_array($status, ["ativo", "bloqueado"])) {
    header("Location: ../../Sessao_adm/veiculos.php");
    exit;
}

$sql = "UPDATE veiculos SET status = :status WHERE placa = :placa";
$stmt = $conn->prepare($sql);

$stmt->execute([
    ":status" => $status,
    ":placa" => $placa
]);

/* volta pra tela original */
header("Location: ../../Sessao_adm/veiculos.php");
exit;
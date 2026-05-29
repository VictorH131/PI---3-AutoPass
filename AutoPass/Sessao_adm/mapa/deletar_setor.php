<?php

include '../../src/includes/dbconnect.php';

$id = $_POST['id'] ?? null;

if (!$id) {
    exit;
}

/* =========================================
DELETAR VAGAS
========================================= */

$sql = "
    DELETE FROM vagas
    WHERE id_setor = :id
";

$stmt = $conn->prepare($sql);

$stmt->bindValue(
    ':id',
    $id
);

$stmt->execute();

/* =========================================
DELETAR SETOR
========================================= */

$sql = "
    DELETE FROM setores
    WHERE id_setor = :id
";

$stmt = $conn->prepare($sql);

$stmt->bindValue(
    ':id',
    $id
);

$stmt->execute();

echo 'ok';
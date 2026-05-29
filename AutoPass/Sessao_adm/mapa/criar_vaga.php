<?php

include '../../src/includes/dbconnect.php';

$setor  = $_POST['setor'] ?? '';
$codigo = $_POST['codigo'] ?? '';
$nome   = $_POST['nome'] ?? '';
$status = $_POST['status'] ?? 'livre';
$pcd    = $_POST['pcd'] ?? 0;
$x      = $_POST['x'] ?? 10;
$y      = $_POST['y'] ?? 10;

if (!$setor || !$codigo) {
    exit;
}

/* BUSCA ID SETOR */

$stmt = $conn->prepare("
    SELECT id_setor
    FROM setores
    WHERE nome = ?
");

$stmt->execute([$setor]);

$idSetor = $stmt->fetchColumn();

if (!$idSetor) {
    exit;
}

/* INSERE */

$stmt = $conn->prepare("
    INSERT INTO vagas (
        id_setor,
        codigo,
        nome,
        status,
        pcd,
        pos_x,
        pos_y
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?
    )
");

$stmt->execute([
    $idSetor,
    $codigo,
    $nome,
    $status,
    $pcd,
    $x,
    $y
]);

echo $conn->lastInsertId();
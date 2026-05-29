<?php

include '../../src/includes/dbconnect.php';

$id = $_POST['id'] ?? null;

$nome = trim($_POST['nome'] ?? '');

$codigo = trim($_POST['codigo'] ?? '');

$status = $_POST['status'] ?? 'livre';

$pcd = $_POST['pcd'] ?? 0;

if(!$id){
    exit;
}

$sql = "
UPDATE vagas SET

    nome = :nome,
    codigo = :codigo,
    status = :status,
    pcd = :pcd

WHERE id_vaga = :id
";

$stmt = $conn->prepare($sql);

$stmt->bindValue(':nome', $nome);
$stmt->bindValue(':codigo', $codigo);
$stmt->bindValue(':status', $status);
$stmt->bindValue(':pcd', $pcd);
$stmt->bindValue(':id', $id);

$stmt->execute();
<?php

include 'dbconnect.php';

$id = $_GET['id'] ?? null;

if (!$id) exit;

$stmt = $conn->prepare("SELECT status FROM usuarios WHERE id_usuario = ?");
$stmt->execute([$id]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) exit;

$novoStatus = ($user['status'] === 'ativo') ? 'desativado' : 'ativo';

$stmt = $conn->prepare("UPDATE usuarios SET status = ? WHERE id_usuario = ?");
$stmt->execute([$novoStatus, $id]);

echo $novoStatus;
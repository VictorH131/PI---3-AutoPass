<?php

include '../../src/includes/dbconnect.php';

$id = $_POST['id'] ?? null;
$x = isset($_POST['x']) ? (int) $_POST['x'] : null;
$y = isset($_POST['y']) ? (int) $_POST['y'] : null;

if (!$id) {
    echo "SEM ID";
    exit;
}

try {

    $sql = "UPDATE vagas SET x = :x, y = :y WHERE id_vaga = :id";
    $stmt = $conn->prepare($sql);

    $stmt->bindValue(':x', $x, PDO::PARAM_INT);
    $stmt->bindValue(':y', $y, PDO::PARAM_INT);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    $stmt->execute();

    echo "OK";

} catch (Exception $e) {
    echo "ERRO: " . $e->getMessage();
}
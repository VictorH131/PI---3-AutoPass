<?php

include '../../src/includes/dbconnect.php';

$nome = strtoupper(trim($_POST['nome'] ?? ''));

if(!$nome){
    exit;
}

$sql = "
INSERT INTO setores (
    nome
) VALUES (
    :nome
)
";

$stmt = $conn->prepare($sql);

$stmt->bindValue(':nome', $nome);

$stmt->execute();
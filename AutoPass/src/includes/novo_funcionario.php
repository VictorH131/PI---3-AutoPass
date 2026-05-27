<?php
include 'dbconnect.php';

$email = $_POST['email'] ?? '';

if (!$email) {
    echo "E-mail obrigatório";
    exit;
}

$sql = "SELECT id_usuario, tipo FROM usuarios WHERE email = :email";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':email', $email);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Usuário não encontrado";
    exit;
}

if ($user['tipo'] == 2) {
    echo "Este usuário já é funcionário";
    exit;
}

$update = "UPDATE usuarios SET tipo = 2 WHERE email = :email";
$stmt = $conn->prepare($update);
$stmt->bindValue(':email', $email);
$stmt->execute();

echo "Funcionário cadastrado com sucesso!";
<?php
session_start();
require_once "dbconnect.php";

function erro($msg) {
    header("Location: ../cadastro.php?erro=" . urlencode($msg));
    exit;
}

function validarCPF($cpf){

    $cpf = preg_replace('/\D/', '', $cpf);

    if(strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)){
        return false;
    }

    for($t = 9; $t < 11; $t++){

        $d = 0;

        for($c = 0; $c < $t; $c++){
            $d += $cpf[$c] * (($t + 1) - $c);
        }

        $d = ((10 * $d) % 11) % 10;

        if($cpf[$c] != $d){
            return false;
        }
    }

    return true;
}

/* ===============================
   CSRF
================================ */

if(
    empty($_SESSION['csrf_token']) ||
    empty($_POST['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
){
    erro("Requisição inválida.");
}

/* ===============================
   SANITIZAÇÃO
================================ */

$nome = trim($_POST['nome'] ?? '');
$sobrenome = trim($_POST['sobrenome'] ?? '');

$cpf = preg_replace('/\D/', '', $_POST['cpf'] ?? '');
$telefone = preg_replace('/\D/', '', $_POST['telefone'] ?? '');

$email = strtolower(trim($_POST['email'] ?? ''));
$email = filter_var($email, FILTER_SANITIZE_EMAIL);

$senha = $_POST['senha'] ?? '';
$confirmar = $_POST['confirmar'] ?? '';

/* ===============================
   VALIDAÇÕES
================================ */

if(
    empty($nome) ||
    empty($sobrenome) ||
    empty($cpf) ||
    empty($email) ||
    empty($senha)
){
    erro("Preencha todos os campos obrigatórios.");
}

if(!validarCPF($cpf)){
    erro("CPF inválido.");
}

if($senha !== $confirmar){
    erro("Senhas não conferem.");
}

if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
    erro("Email inválido.");
}

if(
    !preg_match('/[A-Za-z]/',$senha) ||
    !preg_match('/\d/',$senha)
){
    erro("Senha deve conter letras e números.");
}

/* ===============================
   UPLOAD FOTO
=============================== */

// ===============================
// UPLOAD DA FOTO
// ===============================

$foto_caminho = "img/uploads/default.png";

if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {

    $pasta = "../img/uploads/";

    // extensões permitidas
    $permitidas = ['jpg','png'];

    $extensao = strtolower(
        pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION)
    );

    // verifica extensão
    if (!in_array($extensao, $permitidas)) {
        erro("Formato de imagem inválido.");
    }

    // verifica se realmente é imagem
    if (!getimagesize($_FILES['foto']['tmp_name'])) {
        erro("Arquivo enviado não é uma imagem.");
    }

    // gera nome único
    $nome_arquivo = uniqid("foto_", true) . "." . $extensao;

    $destino = $pasta . $nome_arquivo;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {

        $foto_caminho = "img/uploads/" . $nome_arquivo;

    } else {

        erro("Erro ao enviar imagem.");

    }
}

/* ===============================
   VERIFICAR EMAIL
================================ */

$stmt = $conn->prepare(
    "SELECT id FROM usuarios WHERE email = ?"
);

$stmt->execute([$email]);

if($stmt->rowCount() > 0){
    erro("Email já cadastrado.");
}

/* ===============================
   VERIFICAR CPF
================================ */

$stmt = $conn->prepare(
    "SELECT id FROM usuarios WHERE cpf = ?"
);

$stmt->execute([$cpf]);

if($stmt->rowCount() > 0){
    erro("CPF já cadastrado.");
}

/* ===============================
   HASH SENHA
================================ */

$hash = password_hash(
    $senha,
    PASSWORD_DEFAULT
);

/* ===============================
   INSERT
================================ */

$stmt = $conn->prepare("
INSERT INTO usuarios
(nome, sobrenome, cpf, telefone, email, senha, foto)
VALUES (?, ?, ?, ?, ?, ?, ?)
");

$cadastro = $stmt->execute([
    $nome,
    $sobrenome,
    $cpf,
    $telefone,
    $email,
    $hash,
    $foto_caminho
]);
if($cadastro){

    unset($_SESSION['csrf_token']);

    header("Location: ../login.php?sucesso=1");
    exit;

}else{

    erro("Erro ao cadastrar.");
}
?>
<?php

session_start();

require_once "dbconnect.php";


function erroReset($msg, $email){

    $_SESSION['erro_reset'] = $msg;

    header(
        "Location: reset_senha.php?email="
        . urlencode($email)
    );

    exit;

}


/* ===============================
   DADOS DO FORMULÁRIO
================================ */

$email = trim($_POST['email'] ?? '');

$senha = $_POST['senha'] ?? '';

$confirmar = $_POST['confirmar'] ?? '';



/* ===============================
   VALIDAÇÕES
================================ */

if(

    empty($email) ||
    empty($senha) ||
    empty($confirmar)

){

    erroReset(
        "Dados incompletos",
        $email
    );

}


/* Senha mínima */

if(

    strlen($senha) < 8

){

    erroReset(

        "A senha precisa ter no mínimo 8 caracteres",

        $email

    );

}


/* Letras e números */

if(

    !preg_match('/[A-Za-z]/',$senha) ||
    !preg_match('/\d/',$senha)

){

    erroReset(

        "A senha deve conter letras e números",

        $email

    );

}


/* Confirmar senha */

if(

    $senha !== $confirmar

){

    erroReset(

        "As senhas não coincidem",

        $email

    );

}



/* ===============================
   VERIFICA USUÁRIO
================================ */

$stmt = $conn->prepare(

"
SELECT id
FROM usuarios
WHERE email=?
"

);

$stmt->execute([

    $email

]);


if(

    $stmt->rowCount() <= 0

){

    erroReset(

        "Usuário não encontrado",

        $email

    );

}



/* ===============================
   CRIA HASH
================================ */

$senhaHash = password_hash(

    $senha,

    PASSWORD_DEFAULT

);



/* ===============================
   ATUALIZA SENHA
================================ */

$stmt = $conn->prepare(

"
UPDATE usuarios
SET senha=?
WHERE email=?
"

);

$alterado = $stmt->execute([

    $senhaHash,

    $email

]);



/* ===============================
   RESULTADO
================================ */

if(

    $alterado

){

    $_SESSION['sucesso_login'] =
    "Senha alterada com sucesso";

    header(

        "Location: ../../login.php"

    );

    exit;

}


erroReset(

    "Erro ao alterar senha",

    $email

);

?>
<?php

session_start();

include 'dbconnect.php';

if(!isset($_SESSION['usuario'])){

    header('Location: ../../login.php');
    exit;

}

$idUsuario =
$_SESSION['usuario']['id_usuario'];

try{

    /* =========================================
    EXCLUI ENDEREÇO
    ========================================= */

    $sqlEndereco = "

    DELETE FROM enderecos
    WHERE id_usuario = ?

    ";

    $stmtEndereco =
    $conn->prepare($sqlEndereco);

    $stmtEndereco->execute([
        $idUsuario
    ]);

    /* =========================================
    EXCLUI USUÁRIO
    ========================================= */

    $sqlUsuario = "

    DELETE FROM usuarios
    WHERE id_usuario = ?

    ";

    $stmtUsuario =
    $conn->prepare($sqlUsuario);

    $stmtUsuario->execute([
        $idUsuario
    ]);

    /* =========================================
    DESTROI SESSÃO
    ========================================= */

    session_destroy();

    header(
        'Location: ../../login.php?sucesso=Conta excluida'
    );

    exit;

}catch(PDOException $e){

    header(
        'Location: ../../Sessao_adm/perfil.php?erro=Erro ao excluir conta'
    );

    exit;

}
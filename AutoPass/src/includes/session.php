<?php
session_start();

// Verifica login
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {

    header('Location: ../login.php');
    exit;

}

// Dados do usuário
$idUsuario = $_SESSION['usuario']['id_usuario'];
$nomeUsuario = $_SESSION['usuario']['nome'];
$sobrenome = $_SESSION['usuario']['sobrenome'];
$email = $_SESSION['usuario']['email'];
$foto = $_SESSION['usuario']['foto'];
$tipo = $_SESSION['usuario']['tipo'];


// Traduz tipo do usuário
switch ($tipo) {

    case 3:
        $cargo = "Administrador";
        break;

    case 2:
        $cargo = "Funcionário";
        break;

    default:
        $cargo = "Usuário";
        break;

}

$tipo = $_SESSION['usuario']['tipo'];

?>

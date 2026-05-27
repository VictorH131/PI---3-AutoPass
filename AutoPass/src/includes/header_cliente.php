<?php

// valida login
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.html");
    exit;
}

// valida cliente
if ($_SESSION['usuario']['tipo'] != 1) {
    header("Location: ../index.html");
    exit;
}

date_default_timezone_set('America/Sao_Paulo');

$meses = [
    1 => 'janeiro','fevereiro','março','abril','maio','junho',
    'julho','agosto','setembro','outubro','novembro','dezembro'
];

$dia = date('d');
$mes = (int) date('m');
$ano = date('Y');
$hora = date('H:i');
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../src/img/ico/favicon.png">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Inter, sans-serif;
        }

        body {
            background: #f4f6fb;
        }

        .container-app {
            display: flex;
        }

        .sidebar {
            width: 260px;
            height: 100vh;
            background: #001f3f;
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
        }

        .logo {
            padding: 25px;
        }

        .menu {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .menu a {
            color: #cbd5e1;
            text-decoration: none;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: .2s;
            border-radius: 10px;
            margin: 0 10px;
        }

        .menu a:hover,
        .menu a.active {
            background: #0d6efd;
            color: white;
        }

        .main {
            margin-left: 260px;
            width: 100%;
        }

        .topbar {
            height: 70px;
            background: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 25px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, .08);
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .profile img {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            object-fit: cover;
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-weight: 600;
        }

        .user-role {
            font-size: 12px;
            color: #666;
        }

        .dropdown-menu {
            border: none;
            border-radius: 16px;
            padding: 10px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, .15);
        }

        .dropdown-item {
            padding: 10px;
            border-radius: 10px;
        }

        .content {
            padding: 30px;
        }
    </style>
</head>

<body>

<div class="container-app">

    <!-- SIDEBAR CLIENTE -->
    <div class="sidebar">

        <div class="logo">
            <img src="../src/img/logo/fundo_footer.png" style="height:45px;">
        </div>

        <div class="menu">

            <a href="home.php" class="<?= ($paginaAtiva ?? '') == 'home' ? 'active' : '' ?>">
                <i class="bi bi-house"></i> Início
            </a>

            <a href="carteira.php" class="<?= ($paginaAtiva ?? '') == 'carteira' ? 'active' : '' ?>">
                <i class="bi bi-wallet2"></i> Carteira
            </a>

            <a href="veiculos.php" class="<?= ($paginaAtiva ?? '') == 'veiculos' ? 'active' : '' ?>">
                <i class="bi bi-car-front"></i> Veículos
            </a>

            <a href="historico.php" class="<?= ($paginaAtiva ?? '') == 'historico' ? 'active' : '' ?>">
                <i class="bi bi-clock-history"></i> Histórico
            </a>

        </div>

    </div>

    <!-- MAIN -->
    <div class="main">

        <div class="topbar">

            <div>
                <strong>Bem-vindo, <?= $_SESSION['usuario']['nome'] ?></strong><br>
                <?= $dia . " de " . $meses[$mes] . " de " . $ano . " às " . $hora ?>
            </div>

            <div class="d-flex align-items-center gap-3">

                <i class="bi bi-bell fs-5"></i>

                <div class="dropdown">

                    <button data-bs-toggle="dropdown" class="border-0 bg-transparent">

                        <div class="profile">

                            <div class="user-info">

                                <div class="user-name">
                                    <?= $_SESSION['usuario']['nome'] ?>
                                </div>

                                <div class="user-role">
                                    Cliente
                                </div>

                            </div>

                            <img src="../<?= $_SESSION['usuario']['foto'] ?>">

                        </div>

                    </button>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="perfil.php">
                                <i class="bi bi-person"></i> Perfil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item text-danger" href="../src/includes/logout.php">
                                <i class="bi bi-box-arrow-right"></i> Sair
                            </a>
                        </li>
                    </ul>

                </div>

            </div>

        </div>

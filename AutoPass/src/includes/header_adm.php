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

    <!-- css -->
    <link rel="stylesheet" href="../style/estilo.css">
    <style>
        /* LAYOUT */
        .container-app {
            display: flex;
        }

        /* SIDEBAR */
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

        .sidebar .logo {
            padding: 25px;
            font-size: 24px;
            font-weight: 700;
        }

        .sidebar .menu {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .sidebar .menu a {
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

        .sidebar .menu a:hover {
            background: #0d6efd;
            color: white;
        }

        .sidebar .menu a.active {
            background: #0d6efd;
            color: white;
        }

        /* MAIN */
        .main {
            margin-left: 260px;
            width: 100%;
        }

        /* TOPBAR */
        .topbar {
            height: 70px;
            background: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 25px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, .08);
        }


        /* PROFILE */
        .dropdown button {
            border: none;
            background: none;
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

        /* DROPDOWN */
        .dropdown-menu {
            border: none;
            border-radius: 16px;
            padding: 10px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, .15);
            min-width: 220px;
        }

        .dropdown-item {
            padding: 10px 12px;
            border-radius: 10px;
            transition: .2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .dropdown-item:hover {
            background: #f3f4f6;
        }

        .dropdown-item.text-danger:hover {
            background: #ffe5e5;
        }

        /* CONTENT */
        .content {
            padding: 30px;
        }

        .card-box {
            background: white;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .05);
        }
    </style>

</head>

<body>

    <div class="container-app">

        <!-- SIDEBAR -->
        <div class="sidebar">

            <div class="logo">
                <img src="../src/img/logo/fundo_footer.png" alt="AutoPass Logo"
                    style="height: 45px; margin-right: 8px;">
            </div>

            <div class="menu">

                <a href="home.php" class="<?= ($paginaAtiva ?? '') == 'home' ? 'active' : '' ?>">
                    <i class="bi bi-house"></i> Home
                </a>

                <a href="funcionarios.php" class="<?= ($paginaAtiva ?? '') == 'funcionarios' ? 'active' : '' ?>">
                    <i class="bi bi-people"></i> Funcionários
                </a>

                <a href="vagas.php" class="<?= ($paginaAtiva ?? '') == 'vagas' ? 'active' : '' ?>">
                    <i class="bi bi-p-circle"></i> Vagas
                </a>

                <a href="monitoramento.php" class="<?= ($paginaAtiva ?? '') == 'monitoramento' ? 'active' : '' ?>">
                    <i class="bi bi-graph-up"></i> Monitoramento
                </a>

                <a href="veiculos.php" class="<?= ($paginaAtiva ?? '') == 'veiculos' ? 'active' : '' ?>">
                    <i class="bi bi-car-front"></i> Veículos
                </a>

            </div>

        </div>

        <!-- MAIN -->
        <div class="main">

            <!-- TOPBAR -->
            <div class="topbar">

                <!-- ESQUERDA (sempre existe) -->
                <div>
                    <!-- ESQUERDA -->

                    <div>

                        <?php if (!empty($horaAtual)): ?>

                            <strong>
                                Bem-vindo, <?= $_SESSION['usuario']['nome'] ?>
                            </strong>
                            <br>

                            <?php

                            date_default_timezone_set('America/Sao_Paulo');

                            $meses = [
                                1 => 'janeiro',
                                'fevereiro',
                                'março',
                                'abril',
                                'maio',
                                'junho',
                                'julho',
                                'agosto',
                                'setembro',
                                'outubro',
                                'novembro',
                                'dezembro'
                            ];

                            $dia = date('d');
                            $mes = (int) date('m');
                            $ano = date('Y');
                            $hora = date('H:i');

                            ?>

                            <?= $dia . " de " . $meses[$mes] . " de " . $ano . " às " . $hora ?>

                        <?php else: ?>

                            <div class="search-box">

                                <i class="bi bi-search"></i>

                                <input type="text" id="pesquisaGlobal" placeholder="Pesquisar..." class="form-control">

                            </div>

                        <?php endif; ?>

                    </div>

                </div>


                <!-- DIREITA (sempre fixa) -->
                <div class="d-flex align-items-center gap-3">

                    <!-- sino ANTES do usuário -->
                    <i class="bi bi-bell fs-5 position-relative" style="cursor:pointer;">
                        <span class="position-absolute top-0 start-100 translate-middle p-1"></span>
                    </i>

                    <!-- perfil -->
                    <div class="dropdown">

                        <button data-bs-toggle="dropdown">

                            <div class="profile">

                                <div class="user-info">

                                    <div class="user-name">
                                        <?= $_SESSION['usuario']['nome'] ?>
                                    </div>

                                    <div class="user-role">
                                        <?php
                                        if ($_SESSION['usuario']['tipo'] == 3) {
                                            echo "Administrador";
                                        } elseif ($_SESSION['usuario']['tipo'] == 2) {
                                            echo "Funcionário";
                                        } else {
                                            header('Location: ../index.html');
                                        }
                                        ?>
                                    </div>

                                </div>

                                <img src="../<?= $_SESSION['usuario']['foto'] ?>">

                            </div>

                        </button>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="perfil.php">
                                    <i class="bi bi-person"></i> Editar Perfil
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
<?php
session_start();

/* verifica se usuário está logado */
if (isset($_SESSION['logado']) && $_SESSION['logado'] === true) {


    if ($_SESSION['usuario']['tipo'] >= 2) {

        header("Location: Sessao_adm/home_adm.php");
        exit;
    }

    header("Location: Sessao_cliente/home_cliente.php");
    exit;
}


if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (isset($_GET['erro'])) {
    echo "<div class='alert alert-danger text-center'>"
        . htmlspecialchars($_GET['erro']) .
        "</div>";
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <title>Cadastro | AutoPass</title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="src/img/ico/favicon.png">

    <!-- css -->
    <link rel="stylesheet" href="style/estilo.css">


<<<<<<< HEAD
=======
        #maincadastro {

            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
            background: #f2f2f2;

        }

        #maincadastro::before {

            content: "";

            position: fixed;

            top: 0;
            left: 0;

            width: 100%;
            height: 100%;

            background: url("src/img/logo/fundo_login.png");

            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;

            opacity: .3;

            z-index: -1;

        }

        /* CONTAINER */

        .main-wrapper {

            min-height: 100vh;
            display: flex;
            flex-direction: column;

        }

        .back-btn {

            width: 50px;
            height: 50px;

            position: absolute;

            top: 90px;
            left: 45px;

            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            background: white;

            border: 1px solid #ddd;

            font-size: 22px;

            color: black;

            text-decoration: none;

            transition: .3s;

            box-shadow: 0 4px 15px rgba(0, 0, 0, .08);

            z-index: 1000;

        }

        .back-btn:hover {

            transform: translateX(-3px);

            background: #f7f7f7;

        }


        .back-btn:hover {

            transform: translateX(-3px);

            background: #f7f7f7;

        }


        /* CARD */

        .main-card {

            width: 100%;
            max-width: 980px;

            margin: auto;

            padding: 40px;

            border: none;

            border-radius: 20px;

            background: rgba(255, 255, 255, .96);

            backdrop-filter: blur(10px);

            box-shadow:
                0 10px 35px rgba(0, 0, 0, .07);

        }


        /* TITULOS */

        h3 {

            font-size: 33px;
            font-weight: 700;

        }

        .blue {

            color: #0d6efd;

        }

        .sub {

            font-size: 13px;
            color: #777;

        }


        /* PERFIL */

        .profile {

            width: 90px;
            height: 90px;

            margin: auto;

            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 35px;

            color: #0d6efd;

            border: 1px solid #ddd;

            position: relative;

            background: white;

        }

        .camera {

            position: absolute;

            bottom: 3px;
            right: 0;

            width: 22px;
            height: 22px;

            border-radius: 50%;

            background: #0d6efd;

            display: flex;
            align-items: center;
            justify-content: center;

            color: white;
            font-size: 11px;

        }


        /* INPUTS */

        .form-label {

            font-size: 11px;
            font-weight: 600;
            margin-bottom: 4px;

        }

        .input-group-text {

            background: white;
            border-right: none;
            color: #999;

        }

        .input-group .form-control {

            border-left: none;

        }

        .form-control {

            height: 45px;
            font-size: 13px;

        }

        .form-control:focus {

            box-shadow: none;
            border-color: #0d6efd;

        }

        .btn-primary {

            height: 45px;
            font-weight: 600;

        }

        .btn-google {

            height: 45px;
            background: white;
            border: 1px solid #ddd;

        }

        .policy-link {

            color: #0d6efd !important;

            font-weight: 600;

            cursor: pointer;

            display: inline;

            margin: 0 3px;

            text-decoration: none;

        }

        .policy-link:hover {

            text-decoration: underline;

        }

        /* FOOTER */
        .footer {

            margin-top: 25px;
            background: #1f1f22;
            padding: 20px;
            color: white;

        }

        .footer .row {

            position: relative;
            align-items: center;

        }

        .footer .copyright {

            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;

        }

        .footer-links {

            display: flex;
            justify-content: flex-end;
            gap: 25px;

        }

        .footer-links p {

            margin: 0;
            font-size: 13px;
            cursor: pointer;
            color: #d5d5d5;
            transition: .3s;

        }

        .footer-links p:hover {

            color: #2b7cff;

        }

        .btn-cadastro {
            height: 45px;
            font-weight: 600;
            border-radius: 10px;
            transition: .25s ease;
        }

        .btn-cadastro:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(13, 110, 253, .25);
        }

        .btn-google {
            height: 45px;
            border-radius: 10px;
            border: 1px solid #ddd;
            background: #fff;
            font-weight: 600;

            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;

            transition: .25s ease;
        }

        .btn-google:hover {
            background: #f7f7f7;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, .08);
        }

        .valido {
            border: 2px solid #28a745 !important;
        }

        .invalido {
            border: 2px solid #dc3545 !important;
        }

        .vibrar {
            animation: shake 0.3s;
        }

        @keyframes shake {
            0% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            50% {
                transform: translateX(5px);
            }

            75% {
                transform: translateX(-5px);
            }

            100% {
                transform: translateX(0);
            }
        }

        .profile {
            cursor: pointer;

            position: relative;
        }

        #default-icon {
            font-size: 38px;
            color: #0d6efd;
            transition: .3s;
        }

        .profile:hover #default-icon {
            transform: scale(1.1);
        }
    </style>
>>>>>>> ec6110e83f84c2b7ed4daa69db4dcf198823e941

</head>

<body id="maincadastro">

    <div class="main-wrapper">

        <a href="index.html" class="back-btn">
            <i class="bi bi-arrow-left"></i>
        </a>


        <div class="card main-card">

            <div class="text-center">

                <h3>
                    Crie sua
                    <span class="blue">conta</span>
                </h3>

                <div class="sub">
                    Preencha os dados abaixo para começar.
                </div>

            </div>


            <div class="profile mt-4" id="profile-btn">

                <!-- ÍCONE PADRÃO -->
                <i id="default-icon" class="bi bi-person"></i>

                <!-- PREVIEW DA IMAGEM -->
                <img id="preview" src="" alt="perfil"
                    style="display:none; width:100%; height:100%; border-radius:50%; object-fit:cover;">

                <div class="camera">
                    <i class="bi bi-camera-fill"></i>
                </div>

            </div>



            <form action="src/includes/processa_cadastro.php" method="POST" enctype="multipart/form-data">
                <input type="file" id="file-input" name="foto" accept="image/*" hidden>
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <div class="row mt-4">

                    <div class="col-md-6">

                        <label class="form-label">Nome*</label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-person"></i>
                            </span>

                            <input name="nome" class="form-control" placeholder="Seu nome" required>

                        </div>

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">Sobrenome*</label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-person"></i>
                            </span>

                            <input name="sobrenome" class="form-control" placeholder="Seu sobrenome" required>

                        </div>

                    </div>


                    <div class="col-md-6 mt-3">

                        <label class="form-label">CPF*</label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-card-text"></i>
                            </span>

                            <input name="cpf" class="form-control" placeholder="000.000.000-00" required>

                        </div>

                    </div>


                    <div class="col-md-6 mt-3">

                        <label class="form-label">Telefone*</label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-telephone"></i>
                            </span>

                            <input name="telefone" class="form-control" placeholder="(00)00000-0000" required>

                        </div>

                    </div>


                    <div class="col-12 mt-3">

                        <label class="form-label">Email*</label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-envelope"></i>
                            </span>

                            <input name="email" type="email" class="form-control" placeholder="seu@email.com" required>

                        </div>

                    </div>


                    <div class="col-md-6 mt-3">

                        <label class="form-label">Senha*</label>

                        <div class="input-group">

                            <div class="input-group" id="box-senha">

                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>

                                <input type="password" name="senha" class="form-control" placeholder="Crie uma senha">

                                <span class="input-group-text toggle-senha" data-target="senha">
                                    <i class="bi bi-eye"></i>
                                </span>

                            </div>

                        </div>

                    </div>


                    <div class="col-md-6 mt-3">

                        <label class="form-label">Confirmar senha*</label>

                        <div class="input-group">


                            <div class="input-group" id="box-confirmar">

                                <span class="input-group-text">
                                    <i class="bi bi-shield-lock"></i>
                                </span>

                                <input type="password" name="confirmar" class="form-control"
                                    placeholder="Confirme sua senha">

                                <span class="input-group-text toggle-senha" data-target="confirmar">
                                    <i class="bi bi-eye"></i>
                                </span>

                            </div>

                        </div>


                    </div>

                </div>


                <div class="form-check mt-4">

                    <input required class="form-check-input" type="checkbox" id="aceite">

                    <label class="form-check-label small" for="aceite">

                        Li e aceito os

                        <span class="policy-link">
                            Termos de Uso
                        </span>

                        e

                        <span class="policy-link">
                            Política de Privacidade
                        </span>

                    </label>

                </div>

                <button class="btn btn-primary w-100 mt-4 btn-cadastro">
                    Cadastrar
                </button>

                <div class="text-center my-3 small text-muted">
                    ou
                </div>

                <!-- Google -->
                <div class="google-area">
                    <button class="google-btn">
                        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg">
                        Continuar com Google
                    </button>
                </div>

                <p class="text-center mt-4">

                    Já possui conta?

                    <a href="login.php">

                        Entrar

                    </a>

                </p>
            </form>

        </div>


        <footer class="footer">

            <div class="copyright">
                © 2026 AutoPass • Sistema Inteligente de Estacionamento
            </div>

        </footer>

    </div>

    <script src="js/utilirarios.js"></script>
    <script src="js/cadastro.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
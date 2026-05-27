<?php
session_start();

$erro = $_SESSION['erro_reset'] ?? '';

unset(
    $_SESSION['erro_reset']
);

$email = $_GET['email'] ?? '';

if (!$email) {

    die("Link inválido");

}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width,initial-scale=1">

    <title>Nova senha | AutoPass</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../img/ico/favicon.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <style>
        * {

            margin: 0;
            padding: 0;
            box-sizing: border-box;

        }

        .popup-erro {

            position: fixed;

            top: 25px;

            right: 25px;

            z-index: 999999;

            opacity: 0;

            transform: translateX(100px);

            transition: .4s;

        }

        .popup-erro.show {

            opacity: 1;

            transform: translateX(0);

        }

        .popup-box {

            display: flex;

            align-items: center;

            gap: 15px;

            background: #dc3545;

            color: white;

            padding: 18px 22px;

            border-radius: 14px;

            min-width: 320px;

            box-shadow:
                0 10px 30px rgba(0, 0, 0, .25);

        }

        .popup-icon {

            font-size: 28px;

        }

        .popup-text h5 {

            margin: 0;

            font-size: 16px;

            font-weight: bold;

        }

        .popup-text p {

            margin: 3px 0 0;

            font-size: 13px;

        }

        body {

            height: 100vh;

            display: flex;

            justify-content: center;

            align-items: center;

            background:
                linear-gradient(rgba(0, 0, 0, .65),
                    rgba(0, 0, 0, .65)),

                url("../img/logo/fundo_login.png");

            background-size: cover;

            background-position: center;

            font-family: Arial, sans-serif;

        }

        .card-reset {

            width: 500px;

            max-width: 90%;

            border: none;

            border-radius: 25px;

            padding: 35px;

            background: white;

            box-shadow:
                0 15px 40px rgba(0, 0, 0, .3);

        }

        .logo {

            text-align: center;

            margin-bottom: 20px;

        }

        .logo img {

            width: 180px;

        }

        .email-user {

            font-size: 14px;

            color: #6c757d;

            text-align: center;

            margin-bottom: 25px;

        }

        .input-group-text {

            cursor: pointer;

        }

        .btn-salvar {

            width: 100%;

            padding: 12px;

            font-size: 15px;

            font-weight: bold;

            border: none;

            border-radius: 12px;

            background: #0d6efd;

            color: white;

            transition: .3s;

        }

        .btn-salvar:hover {

            transform: translateY(-2px);

            background: #0b5ed7;

        }
    </style>

</head>

<body>

    <?php if ($erro): ?>

        <div class="popup-erro" id="popupErro">

            <div class="popup-box">

                <div class="popup-icon">

                    <i class="bi bi-exclamation-triangle-fill"></i>

                </div>

                <div class="popup-text">

                    <h5>

                        Erro

                    </h5>

                    <p>

                        <?= htmlspecialchars($erro) ?>

                    </p>

                </div>

            </div>

        </div>

    <?php endif; ?>

    <div class="card-reset">

        <div class="logo">

            <img src="../img/logo/logo_head.png">

        </div>

        <h2 class="text-center">

            Nova senha

        </h2>

        <div class="email-user">

            Conta:

            <b>

                <?= htmlspecialchars($email) ?>

            </b>

        </div>

        <form action="salvar_nova_senha.php" method="POST">

            <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">


            <div class="mb-3">

                <label>

                    Nova senha

                </label>

                <div class="input-group">

                    <input type="password" name="senha" id="senha" class="form-control" required>

                    <span class="input-group-text" id="toggleSenha">

                        <i class="bi bi-eye">

                        </i>

                    </span>

                </div>

            </div>


            <div class="mb-4">

                <label>

                    Confirmar senha

                </label>

                <div class="input-group">

                    <input type="password" name="confirmar" id="confirmar" class="form-control" required>

                    <span class="input-group-text" id="toggleConfirmar">

                        <i class="bi bi-eye">

                        </i>

                    </span>

                </div>

            </div>


            <button class="btn-salvar">

                Salvar nova senha

            </button>

        </form>

    </div>


    <script>

        function toggle(
            campo,
            icone
        ) {

            const input =
                document.getElementById(
                    campo
                );

            const icon =
                document.querySelector(
                    `#${icone} i`
                );

            if (
                input.type === "password"
            ) {

                input.type = "text";

                icon.classList.remove(
                    "bi-eye"
                );

                icon.classList.add(
                    "bi-eye-slash"
                );

            } else {

                input.type = "password";

                icon.classList.remove(
                    "bi-eye-slash"
                );

                icon.classList.add(
                    "bi-eye"
                );

            }

        }

        document
            .getElementById(
                "toggleSenha"
            )
            .onclick = () => {

                toggle(
                    "senha",
                    "toggleSenha"
                );

            };

        document
            .getElementById(
                "toggleConfirmar"
            )
            .onclick = () => {

                toggle(
                    "confirmar",
                    "toggleConfirmar"
                );

            };

    </script>
    <script>

        const popupErro =
            document.getElementById(
                "popupErro"
            );

        if (popupErro) {

            setTimeout(() => {

                popupErro.classList.add(
                    "show"
                );

            }, 100);


            setTimeout(() => {

                popupErro.classList.remove(
                    "show"
                );

            }, 4000);

        }

    </script>
</body>

</html>
<?php
session_start();

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


if (isset($_SESSION['logado']) && $_SESSION['logado'] === true) {

    if ($_SESSION['usuario']['tipo'] >= 2) {

        header("Location: Sessao_adm/home.php");
        exit;

    }


    if ($_SESSION['usuario']['tipo'] == 1) {

        header("Location: Sessao_cliente/home.php");
        exit;

    }

}
?>
<?php if(isset($_GET['sucesso'])): ?>

    <div class="alerta sucesso">

        <?= htmlspecialchars($_GET['sucesso']) ?>

    </div>

<?php endif; ?>


<?php if(isset($_GET['erro'])): ?>

    <div class="alerta erro">

        <?= htmlspecialchars($_GET['erro']) ?>

    </div>

<?php endif; ?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login | AutoPass</title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="src/img/ico/favicon.png">

    <!-- css -->
    <link rel="stylesheet" href="style/estilo.css">


</head>

<body id="mainlogin">

    <div class="main-wrapper">

        <a href="index.html" class="back-btn">
            <i class="bi bi-arrow-left"></i>
        </a>

        <div class="card main-card">

            <div class="text-center">

                <img src="src/img/logo/logo_head.png" alt="AutoPass">

                <h3 class="mt-3">

                    Entrar na sua
                    <span class="blue">conta</span>

                </h3>

                <div class="sub">

                    Faça login para acessar o AutoPass

                </div>

            </div>

            <form action="src/includes/processa_login.php" method="POST">

                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <div class="mt-4">

                    <label class="form-label">

                        Email

                    </label>

                    <div class="input-group">

                        <span class="input-group-text">

                            <i class="bi bi-envelope"></i>

                        </span>

                        <input type="email" name="email" class="form-control" placeholder="seu@email.com" required>

                    </div>

                </div>

                <div class="mt-3">

                    <label class="form-label">

                        Senha

                    </label>

                    <div class="input-group">

                        <span class="input-group-text">

                            <i class="bi bi-lock"></i>

                        </span>

                        <input type="password" name="senha" class="form-control" placeholder="Digite sua senha"
                            required>

                        <span class="input-group-text toggle">

                            <i class="bi bi-eye"></i>

                        </span>

                    </div>

                </div>

                <div class="d-flex justify-content-between mt-3">

                    <div class="form-check">

                        <input class="form-check-input" type="checkbox" id="lembrar">

                        <label class="form-check-label small" for="lembrar">

                            Lembrar-me

                        </label>

                    </div>

                    <a href="#" id="abrirPopup">
                        Esqueceu a senha?
                    </a>

                   

                </div>

                <button class="btn btn-primary w-100 mt-4 btn-login">

                    Entrar

                </button>

                <p class="text-center mt-4">

                    Não possui conta?

                    <a href="cadastro.php">

                        Criar conta

                    </a>

                </p>

            </form>

        </div>

        <footer class="footer">

            © 2026 AutoPass • Sistema Inteligente de Estacionamento

        </footer>

    </div>

    <script>

        const toggle = document.querySelector(".toggle");
        const senha = document.querySelector('input[name="senha"]');

        toggle.addEventListener("click", () => {

            const icon = toggle.querySelector("i");

            if (senha.type === "password") {

                senha.type = "text";

                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");

            } else {

                senha.type = "password";

                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");

            }

        });

    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

    <script src="js/popup_senha.js"></script>
</body>

</html>
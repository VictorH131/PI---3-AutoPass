<?php
session_start();

if(isset($_SESSION['logado']) && $_SESSION['logado'] === true){

    if ($_SESSION['usuario']['tipo'] >= 2) {

        header("Location: Sessao_adm/home.php");
        exit;

    }


    if($_SESSION['usuario']['tipo'] == 1){

        header("Location: Sessao_cliente/home.php");
        exit;

    }

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
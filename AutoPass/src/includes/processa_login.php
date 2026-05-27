<?php
session_start();
require_once 'dbconnect.php'; // usa $conn

// GERA TOKEN CSRF SE NÃO EXISTIR
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// VERIFICA ENVIO DO FORMULÁRIO
if (isset($_POST['email'], $_POST['senha'], $_POST['csrf_token'])) {

    // CSRF
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $_SESSION['aviso'] = "Erro de validação CSRF.";
        header('Location: ../../login.php');
        exit;
    }

    // LIMITE DE TENTATIVAS
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
        $_SESSION['last_attempt'] = time();
    }

    if ($_SESSION['login_attempts'] >= 7) {
        if (time() - $_SESSION['last_attempt'] < 8) {
            $_SESSION['aviso'] = "Muitas tentativas. Aguarde 3 minutos.";
            header('Location: ../../login.php');
            exit;
        } else {
            $_SESSION['login_attempts'] = 0;
        }
    }

    // SANITIZAÇÃO
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $senha = $_POST['senha'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['aviso'] = "E-mail inválido!";
        header('Location: ../../login.php');
        exit;
    }

    // VERIFICAÇÃO NO BANCO
    try {
        $sql = "SELECT * FROM usuarios WHERE email = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            if ($usuario['status'] == 'desativo') {
                $_SESSION['aviso'] = "Conta desativada.";
                header('Location: ../../login.php');
                exit;
            }

            if (password_verify($senha, $usuario['senha'])) {

                // LOGIN CORRETO
                $_SESSION['login_attempts'] = 0;

                // ARMAZENA DADOS DO USUÁRIO
                $_SESSION['usuario'] = [
                    'id_usuario' => $usuario['id_usuario'],
                    'nome' => $usuario['nome'],
                    'sobrenome' => $usuario['sobrenome'],
                    'email' => $usuario['email'],
                    'foto' => $usuario['foto'],
                    'tipo' => $usuario['tipo']
                ];

                $_SESSION['logado'] = true;

                // REDIRECIONAMENTO

                if ($usuario['tipo'] < 2) {

                    header('Location: ../../Sessao_cliente/home.php');
                    exit;

                } else {

                    header('Location: ../../Sessao_adm/home.php');
                    exit;

                }


            } else {
                $_SESSION['login_attempts']++;
                $_SESSION['last_attempt'] = time();
                $_SESSION['aviso'] = "Senha incorreta.";
                header('Location: ../../login.php');
                exit;
            }
        } else {
            $_SESSION['login_attempts']++;
            $_SESSION['last_attempt'] = time();
            $_SESSION['aviso'] = "E-mail não cadastrado.";
            header('Location: ../../login.php');
            exit;
        }

    } catch (PDOException $e) {
        $_SESSION['aviso'] = "Erro ao acessar o banco de dados.";
        header('Location: ../../login.php');
        exit;
    }

} else {
    $_SESSION['aviso'] = "Por favor, preencha todos os campos.";
    header('Location: ../../login.php');
    exit;
}
?>
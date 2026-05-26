<?php
session_start();
require_once '../includes/dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


        try {

            $hash1 = password_hash('a123123', PASSWORD_DEFAULT);
            $hash2 = password_hash('Funcionario123', PASSWORD_DEFAULT);

            $sql = "UPDATE usuarios SET senha = :senha, tipo = :tipo WHERE id = :id";
            $stmt = $conn->prepare($sql);

            // Victor - Admin (nível 3)
            $stmt->execute([
                ':senha' => $hash1,
                ':tipo' => 3,
                ':id' => 1
            ]);

            // Usuário nível 2
            $stmt->execute([
                ':senha' => $hash2,
                ':tipo' => 2,
                ':id' => 2
            ]);

            echo "Senhas atualizadas com sucesso!";

        } catch (PDOException $e) {

        echo "Erro: " . htmlspecialchars($e->getMessage());


        if (isset($_POST['reset_adm'])) {

            $hash1 = password_hash('a123123', PASSWORD_DEFAULT);
            $hash2 = password_hash('Catarino@123', PASSWORD_DEFAULT);
            $hash3 = password_hash('feoli0805', PASSWORD_DEFAULT);
            $hash4 = password_hash('25862210', PASSWORD_DEFAULT);

            $sql = "UPDATE adm SET senha = :senha WHERE id_adm = :id";
            $stmt = $conn->prepare($sql);

            $stmt->execute([
                ':senha' => $hash1,
                ':id' => 1
            ]);

            $stmt->execute([
                ':senha' => $hash2,
                ':id' => 2
            ]);

            $stmt->execute([
                ':senha' => $hash3,
                ':id' => 3
            ]);

            $stmt->execute([
                ':senha' => $hash4,
                ':id' => 4
            ]);

            $mensagem = "Senhas dos administradores redefinidas.";
        }

    } catch (PDOException $e) {

        $mensagem = "Erro: " . htmlspecialchars($e->getMessage());

    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Resetar Senhas</title>
</head>

<body>

    <?php if (isset($mensagem)): ?>
        <p><?= $mensagem ?></p>
    <?php endif; ?>

    <form method="POST">

        <button type="submit" name="reset_aluno">
            Resetar Senhas dos clientes
        </button>

        <button type="submit" name="reset_adm">
            Resetar Senhas dos Administradores
        </button>

    </form>

</body>

</html>
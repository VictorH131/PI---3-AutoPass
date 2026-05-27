<?php
session_start();
require_once '../includes/dbconnect.php';

$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {

        if (isset($_POST['reset_clientes'])) {

            $hash1 = password_hash('a123123', PASSWORD_DEFAULT);
            $hash2 = password_hash('oliveira', PASSWORD_DEFAULT);

            $sql = "UPDATE usuarios SET senha = :senha, tipo = :tipo WHERE id_usuario = :id";
            $stmt = $conn->prepare($sql);

            // Cliente 1
            $stmt->execute([
                ':senha' => $hash1,
                ':tipo' => 3,
                ':id' => 1
            ]);

            // Cliente 2
            $stmt->execute([
                ':senha' => $hash2,
                ':tipo' => 1,
                ':id' => 2
            ]);

            $mensagem = "Senhas dos clientes atualizadas com sucesso!";
        }

    } catch (PDOException $e) {
        $mensagem = "Erro: " . $e->getMessage();
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

<?php if (!empty($mensagem)): ?>
    <p><?= $mensagem ?></p>
<?php endif; ?>

<form method="POST">

    <button type="submit" name="reset_clientes">
        Resetar Senhas dos Clientes
    </button>

</form>

</body>
</html>
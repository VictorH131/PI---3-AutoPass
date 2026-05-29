<?php

session_start();

include 'dbconnect.php';

if (!isset($_SESSION['usuario'])) {

    header('Location: ../../login.php');
    exit;

}

$id = $_SESSION['usuario']['id_usuario'];

$nome = trim($_POST['nome']);
$sobrenome = trim($_POST['sobrenome']);
$email = trim($_POST['email']);
$telefone = trim($_POST['telefone']);

$cpf = preg_replace(
    '/[^0-9]/',
    '',
    $_POST['cpf']
);

$cep = preg_replace(
    '/[^0-9]/',
    '',
    $_POST['cep']
);

$rua = trim($_POST['rua']);
$numero = trim($_POST['numero']);
$complemento = trim($_POST['complemento']);
$bairro = trim($_POST['bairro'] ?? '');
$cidade = trim($_POST['cidade']);
$estado = trim($_POST['estado']);

$novaSenha =
$_POST['nova_senha'] ?? '';

$confirmarSenha =
$_POST['confirmar_senha'] ?? '';

/* =========================================
VALIDAÇÃO SENHA
========================================= */

if (!empty($novaSenha)) {

    if ($novaSenha !== $confirmarSenha) {

        header(
            'Location: ../../Sessao_adm/perfil.php?erro=Senhas diferentes'
        );

        exit;

    }

}

/* =========================================
UPLOAD FOTO
========================================= */

$fotoPath = null;

if (
    isset($_FILES['foto']) &&
    $_FILES['foto']['error'] == 0
) {

    $extensao = pathinfo(
        $_FILES['foto']['name'],
        PATHINFO_EXTENSION
    );

    $nomeFoto =
    uniqid() .
    '.' .
    $extensao;

    $diretorio =
    '../../uploads/usuarios/';

    if (!is_dir($diretorio)) {

        mkdir(
            $diretorio,
            0777,
            true
        );

    }

    $caminhoCompleto =
    $diretorio .
    $nomeFoto;

    move_uploaded_file(
        $_FILES['foto']['tmp_name'],
        $caminhoCompleto
    );

    $fotoPath =
    'uploads/usuarios/' .
    $nomeFoto;

}

/* =========================================
UPDATE USUÁRIO
========================================= */

$sql = "

UPDATE usuarios SET

nome = ?,
sobrenome = ?,
email = ?,
telefone = ?,
cpf = ?

";

$params = [

    $nome,
    $sobrenome,
    $email,
    $telefone,
    $cpf

];

/* =========================================
NOVA SENHA
========================================= */

if (!empty($novaSenha)) {

    $senhaHash =
    password_hash(
        $novaSenha,
        PASSWORD_DEFAULT
    );

    $sql .= ",
    senha = ?
    ";

    $params[] =
    $senhaHash;

}

/* =========================================
FOTO
========================================= */

if ($fotoPath) {

    $sql .= ",
    foto = ?
    ";

    $params[] =
    $fotoPath;

}

$sql .= "
WHERE id_usuario = ?
";

$params[] = $id;

$stmt = $conn->prepare($sql);

$stmt->execute($params);

/* =========================================
VERIFICA ENDEREÇO
========================================= */

$sqlEndereco = "

SELECT id_endereco
FROM enderecos
WHERE id_usuario = ?

";

$stmtEndereco =
$conn->prepare(
    $sqlEndereco
);

$stmtEndereco->execute([$id]);

$endereco =
$stmtEndereco->fetch();

/* =========================================
SE EXISTIR -> UPDATE
========================================= */

if ($endereco) {

    $sqlUpdateEndereco = "

    UPDATE enderecos SET

    cep = ?,
    rua = ?,
    numero = ?,
    complemento = ?,
    bairro = ?,
    cidade = ?,
    estado = ?

    WHERE id_usuario = ?

    ";

    $stmtUpdate =
    $conn->prepare(
        $sqlUpdateEndereco
    );

    $stmtUpdate->execute([

        $cep,
        $rua,
        $numero,
        $complemento,
        $bairro,
        $cidade,
        $estado,
        $id

    ]);

} else {

/* =========================================
SE NÃO EXISTIR -> INSERT
========================================= */

    $sqlInsertEndereco = "

    INSERT INTO enderecos (

        id_usuario,
        cep,
        rua,
        numero,
        complemento,
        bairro,
        cidade,
        estado

    ) VALUES (

        ?, ?, ?, ?, ?, ?, ?, ?

    )

    ";

    $stmtInsert =
    $conn->prepare(
        $sqlInsertEndereco
    );

    $stmtInsert->execute([

        $id,
        $cep,
        $rua,
        $numero,
        $complemento,
        $bairro,
        $cidade,
        $estado

    ]);

}

/* =========================================
ATUALIZA SESSÃO
========================================= */

$_SESSION['usuario']['nome'] =
$nome;

$_SESSION['usuario']['sobrenome'] =
$sobrenome;

$_SESSION['usuario']['email'] =
$email;

if ($fotoPath) {

    $_SESSION['usuario']['foto'] =
    $fotoPath;

}

/* =========================================
REDIRECT
========================================= */

header(
    'Location: ../../Sessao_adm/perfil.php?sucesso=Perfil atualizado'
);

exit;
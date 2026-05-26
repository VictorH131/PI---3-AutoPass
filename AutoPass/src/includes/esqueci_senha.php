<?php

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


/* ========= CARREGA DEV.ENV ========= */

$env = parse_ini_file(
    __DIR__ . '/../../dev.env'
);

if (!$env) {

    die("Erro ao carregar dev.env");

}

$_ENV['EMAIL_USER'] = trim($env['EMAIL_USER']);
$_ENV['EMAIL_PASS'] = trim($env['EMAIL_PASS']);
$_ENV['EMAIL_NAME'] = trim($env['EMAIL_NAME']);


/* ========= EMAIL DO FORM ========= */

$email = filter_input(
    INPUT_POST,
    'email',
    FILTER_SANITIZE_EMAIL
);

if (
    !$email ||
    !filter_var($email, FILTER_VALIDATE_EMAIL)
) {

    header(
        "Location: ../../login.php?erro="
        . urlencode("Email inválido")
    );

    exit;

}


/* ========= PHPMailer ========= */

$mail = new PHPMailer(true);

$mail->CharSet = "UTF-8";

$mail->SMTPOptions = [

    'ssl' => [

        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true

    ]

];

try {

    $mail->isSMTP();

    $mail->Host = "smtp.gmail.com";

    $mail->SMTPAuth = true;

    $mail->Username = $_ENV['EMAIL_USER'];

    $mail->Password = $_ENV['EMAIL_PASS'];

    $mail->SMTPSecure =
        PHPMailer::ENCRYPTION_STARTTLS;

    $mail->Port = 587;


    /* ========= REMETENTE ========= */

    $mail->setFrom(

        $_ENV['EMAIL_USER'],
        $_ENV['EMAIL_NAME']

    );

    $mail->addAddress($email);


    /* ========= IMAGEN ========= */

    $mail->addEmbeddedImage(

        __DIR__ . '/../img/logo/logo_head.png',
        'logo_autopass'

    );

    /* ========= EMAIL ========= */

    $mail->isHTML(true);

    $mail->Subject =
        "Recuperação de senha - AutoPass";

    $resetLink = "http://localhost/Delicia/PI---3-AutoPass/AutoPass/src/includes/reset_senha.php?email="
        . urlencode($email);

    $mail->Body = "

<body style='
margin:0;
padding:40px 0;
background:#f4f6f9;
font-family:Arial,sans-serif;
'>

<table
width='100%'
cellpadding='0'
cellspacing='0'
style='background:#f4f6f9;'>

<tr>

<td align='center'>

<table
width='520'
cellpadding='0'
cellspacing='0'
style='
background:#ffffff;
border-radius:16px;
overflow:hidden;
border:1px solid #e5e5e5;
'>

    <!-- LOGO -->

    <tr>

        <td
        align='center'
        style='
        padding:45px 30px 20px 30px;
        background:#ffffff;
        '>

            <img
            src='cid:logo_autopass'
            width='220'
            style='
            display:block;
            max-width:220px;
            '>

        </td>

    </tr>


    <!-- TITULO -->

    <tr>

        <td
        align='center'
        style='
        padding:0 40px 10px 40px;
        '>

            <h1 style='
            margin:0;
            font-size:28px;
            color:#1f2937;
            font-weight:700;
            '>

                Recuperação de Senha

            </h1>

        </td>

    </tr>


    <!-- TEXTO -->

    <tr>

        <td style='
        padding:20px 45px 10px 45px;
        color:#4b5563;
        font-size:15px;
        line-height:1.8;
        text-align:left;
        '>

            Olá,

            <br><br>

            Recebemos uma solicitação para redefinir
            a senha da sua conta AutoPass.

            <br><br>

            Clique no botão abaixo para criar
            uma nova senha com segurança.

        </td>

    </tr>


    <!-- BOTAO -->

    <tr>

        <td
        align='center'
        style='padding:30px 40px;'>

           <a
href='$resetLink'
style='
background:#0057d9;
color:#ffffff;
text-decoration:none;
padding:16px 34px;
border-radius:10px;
display:inline-block;
font-size:15px;
font-weight:bold;
box-shadow:0 5px 15px rgba(0,87,217,.3);
'>

    Redefinir Minha Senha

</a>

        </td>

    </tr>


    <!-- AVISO -->

    <tr>

        <td style='
        padding:0 45px 35px 45px;
        '>

            <div style='
            background:#f5f7fa;
            border-radius:10px;
            padding:18px;
            font-size:13px;
            color:#6b7280;
            line-height:1.7;
            '>

              Se você não solicitou esta alteração, pode ignorar este e-mail com segurança. O link expirará em 24 horas.
            </div>

        </td>

    </tr>


    <!-- RODAPE -->

    <tr>

        <td
        align='center'
        style='
        padding:22px;
        background:#fafafa;
        border-top:1px solid #ececec;
        font-size:12px;
        color:#9ca3af;
        '>

            © 2026 AutoPass Intelligent Systems.
            Todos os direitos reservados.

        </td>

    </tr>

</table>

</td>

</tr>

</table>

</body>

";


    /* ========= ENVIA ========= */

    $mail->send();


    /* ========= SUCESSO ========= */

    header(

        "Location: ../../login.php?sucesso="
        . urlencode("Email enviado com sucesso")

    );

    exit;

} catch (Exception $e) {


    /* ========= ERRO ========= */

    header(

        "Location: ../../login.php?erro="
        . urlencode($mail->ErrorInfo)

    );

    exit;

}
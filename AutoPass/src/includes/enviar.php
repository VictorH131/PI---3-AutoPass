<?php

require '../PHPMailer/Exception.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {

    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;

    // Email que envia
    $mail->Username = "victorhs.almeida131@gmail.com";

    // Senha de aplicativo do Google
    $mail->Password = "qiqqmvcaqjmhbfky";

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom(
        "victorhs.almeida131@gmail.com",
        "AutoPass"
    );

    // Quem recebe
    $mail->addAddress(
        "victornado21a@gmail.com"
    );

    $mail->isHTML(true);

    $mail->Subject = "Nova mensagem";

    $mail->Body = "
        <h2>Mensagem do site</h2>
        <p>Funcionou 🎉</p>
    ";

    $mail->send();

    echo "Email enviado com sucesso";

} catch (Exception $e) {
    echo "Erro: " . $mail->ErrorInfo;
}
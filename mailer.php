<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/autoload.php'; // Se stai usando Composer, altrimenti regola il percorso correttamente

$mail = new PHPMailer(true);

// $mail->SMTPDebug = SMTP::DEBUG_SERVER;

$mail->isSMTP();
$mail->SMTPAuth   = true;

$mail->Host       = 'smtp.mailtrap.io'; // Hostname di Mailtrap
$mail->Username   = 'f530cb68dd7cef'; // Nome utente di Mailtrap
$mail->Password   = '484e6396df6bd3'; // Password di Mailtrap
$mail->SMTPSecure = 'tls';
$mail->Port       = 2525; // Porta SMTP di Mailtrap

$mail->isHTML(true);

return $mail;
<?php

require_once __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

// Désactivé en production
$mail->SMTPDebug = SMTP::DEBUG_OFF;

// Configuration SMTP
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'chtitsoeur@gmail.com';
$mail->Password = 'xkvsalhjpcllusnt';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;

// Configuration générale
$mail->CharSet = 'UTF-8';
$mail->isHTML(true);

// Expéditeur par défaut
$mail->setFrom('chtitsoeur@gmail.com', 'Vite et Gourmand');
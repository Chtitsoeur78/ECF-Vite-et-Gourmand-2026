<?php

require_once __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

$mail = new PHPMailer(true);

try {
    // Affiche les détails techniques pendant le test.
    $mail->SMTPDebug = SMTP::DEBUG_OFF;

    // Utilisation du serveur SMTP de Gmail.
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;

    // Ton adresse Gmail complète.
    $mail->Username = 'chtitsoeur@gmail.com';

    // Le mot de passe d’application Google de 16 caractères.
    $mail->Password = 'yzjiapzqhlxspeah';

    // Connexion sécurisée.
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Expéditeur : utilise la même adresse que Username.
    $mail->setFrom('chtitsoeur@gmail.com', 'Vite et Gourmand');

    // Adresse qui recevra le message de test.
    // Tu peux utiliser ta propre adresse Gmail.
    $mail->addAddress('chtitsoeur78@gmail.com');

    // Contenu du message.
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = 'Test PHPMailer - Vite et Gourmand';

    $mail->Body = '
        <h1>Test réussi</h1>
        <p>PHPMailer fonctionne correctement avec Gmail.</p>
    ';

    $mail->AltBody =
        'Test réussi : PHPMailer fonctionne correctement avec Gmail.';

    $mail->send();

    echo '<p>Le message a bien été envoyé.</p>';

} catch (Exception $e) {
    echo '<p>Le message n’a pas pu être envoyé.</p>';
    echo '<p>Erreur : ' . htmlspecialchars($mail->ErrorInfo) . '</p>';
}
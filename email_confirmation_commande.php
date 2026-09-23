<?php

require_once __DIR__ . '/configuration_email.php';

try {

// Destinataire
/** @var string $email */
$mail->addAddress($email);

// Objet
$mail->Subject = 'Confirmation de votre commande';

// Corps du message
$mail->Body = '
<h2>Confirmation de votre commande</h2>

<p>Bonjour ' . htmlspecialchars($prenom) . ',</p>

<p>Nous avons le plaisir de vous informer que votre commande a  bien été enregistrée.</p>

<p>Nous vous remercions de votre confiance.</p>

<p>A très bientôt ! </p>
<p>L\'équipe Vite et Gourmand</p>
';

$mail->AltBody =
"Confirmation de votre commande

Bonjour $prenom,

Nous avons le plaisir de vous informer que votre commande a  bien été enregistrée.

Nous vous remercions de votre confiance.

A très bientôt ! 
L'équipe Vite et Gourmand";

// Envoi
$mail->send();
    
} catch (Exception $e) {

error_log(
        "Erreur lors de l'envoi du mail de commande : "
        . $mail->ErrorInfo
    );

}
<?php

require_once __DIR__ . '/configuration_email.php';

try {

// Destinataire
/** @var string $email */
/** @var string $prenom */
$mail->addAddress($email);

// Objet
$mail->Subject = 'Confirmation des modifications de votre commande';

// Corps du message
$mail->Body = '
<h2>Confirmation des modifications de votre commande</h2>

<p>Bonjour ' . htmlspecialchars($prenom) . ',</p>

<p>Nous avons le plaisir de vous confirmer que les modifications apportées à votre commande ont bien été enregistrées.</p>
<p>Retrouvez votre commandes mise à jour dès à présent dans votre espace utilisateur Vite et Gourmand.</p>

<p>Nous vous remercions de votre confiance.</p>

<p>A très vite ! </p>
<p>L\'équipe Vite et Gourmand</p>
';

$mail->AltBody =
"Confirmation des modifications de votre commande

Bonjour $prenom,

Nous avons le plaisir de vous confirmer que ue les modifications apportées à votre commande ont bien été enregistrées.</p>
Retrouvez votre commandes mise à jour dès à présent dans votre espace utilisateur Vite et Gourmand.

Nous vous remercions de votre confiance.

A très vite ! 
L'équipe Vite et Gourmand";

// Envoi
$mail->send();
    
} catch (Exception $e) {

error_log(
        "Erreur lors de l'envoi du mail de modification de la commande : "
        . $mail->ErrorInfo
    );
}
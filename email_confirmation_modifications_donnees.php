<?php

require_once __DIR__ . '/configuration_email.php';

try {

// Destinataire
/** @var string $email */
/** @var string $prenom */
$mail->addAddress($email);

// Objet
$mail->Subject = 'Confirmation des modifications de vos données';

// Corps du message
$mail->Body = '
<h2>Confirmation des modifications de vos données</h2>

<p>Bonjour ' . htmlspecialchars($prenom) . ',</p>

<p>Nous avons le plaisir de vous confirmer que vos données ont bien été modifiées.</p>
<p>Vous pouvez les retrouver dès maintenant mises à jour sur votre espace utilisateur.</p>

<p>Nous vous remercions de votre confiance.</p>

<p>A très vite ! </p>
<p>L\'équipe Vite et Gourmand</p>
';

$mail->AltBody =
"Confirmation des modifications de vos données

Bonjour $prenom,

Nous avons le plaisir de vous confirmer que vos données personnelles ont bien été modifiées
Vous pouvez les retrouver dès maintenant mises à jour sur votre espace utilisateur.

Nous vous remercions de votre confiance.

A très vite ! 
L'équipe Vite et Gourmand";

// Envoi
$mail->send();
    
} catch (Exception $e) {

error_log(
        "Erreur lors de l'envoi du mail de modifications des données : "
        . $mail->ErrorInfo
    );

}
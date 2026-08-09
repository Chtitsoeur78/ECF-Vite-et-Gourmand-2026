<?php

require_once __DIR__ . '/configuration_email.php';

try {

// Destinataire
/** @var string $email */
$mail->addAddress($email);

// Objet
$mail->Subject = 'Confirmation de votre inscription';

// Corps du message
$mail->Body = '
<h2>Confirmation de votre inscription</h2>

<p>Bonjour ' . htmlspecialchars($prenom) . ',</p>

<p>Nous avons le plaisir de vous informer que votre inscription a bien été prise en compte.</p>
<p>Nous vous souhaitons la bienvenue chez Vite et Gourmand et vous souhaitons beaucoup de plaisir culinaire.</p>
<p>En vous remerciant de votre confiance.</p>

<p>A très bientôt !</p>
<p>José Toc et l\'équipe Vite et Gourmand</p>
';

$mail->AltBody =
"Confirmation de votre inscription

Bonjour $prenom,

Nous avons le plaisir de vous informer que votre inscription a bien été prise en compte.
Nous vous souhaitons la bienvenue chez Vite et Gourmand et vous souhaitons beaucoup de plaisir culinaire.
En vous remerciant de votre confiance.

A très bientôt ! 
José Toc et l'équipe Vite et Gourmand";

// Envoi
$mail->send();

} catch (Exception $e) {

error_log(
        "Erreur lors de l'envoi du mail d'inscription : "
        . $mail->ErrorInfo
    );
}
<?php

require_once __DIR__ . '/configuration_email.php';

/** @var string $email */
/** @var string $prenom */
/** @var \PHPMailer\PHPMailer\PHPMailer $mail */

try {

// Destinataire
$mail->addAddress($email);

// Objet
$mail->Subject = 'Information sur votre commande';

// Corps du message
$mail->Body = '
<h2>Information sur votre commande</h2>

<p>Bonjour ' . htmlspecialchars($prenom) . ',</p>

<p>Votre commande est désormais terminée et nous vous remercions une nouvelle fois de votre confiance.</p>
<p>Nous vous informons que vous avez dès maintenant la possibilité de nous envoyer votre avis sur cette dernière.</p>
<p>Il vous suffit pour cela de vous rendre dans votre espace utilisateur Vite et Gourmand. </p>

<p>A très bientôt !</p>
<p>José Toc et l\'équipe Vite et Gourmand</p>
';

$mail->AltBody =
"Information sur votre commande

Bonjour $prenom,

Votre commande est désormais terminée et nous vous remercions une nouvelle fois de votre confiance.
Nous vous informons que vous avez dès maintenant la possibilité de nous envoyer votre avis sur cette dernière.
Il vous suffit pour cela de vous rendre dans votre espace utilisateur Vite et Gourmand.

A très bientôt ! 
José Toc et l'équipe Vite et Gourmand";

// Envoi
$mail->send();

} catch (Exception $e) {

error_log(
        "Erreur lors de l'envoi du mail d'information sur la commande : "
        . $mail->ErrorInfo
    );
}
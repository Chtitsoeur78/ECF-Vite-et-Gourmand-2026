<?php

require_once __DIR__ . '/configuration_email.php';

/** @var string $email */
/** @var string $prenom */

try {

// Destinataire
$mail->addAddress($email);

// Objet
$mail->Subject = 'Confirmation de l\'annulation de votre commande';

// Corps du message
$mail->Body = '
<h2>Confirmation de l\'annulation de votre commande</h2>

<p>Bonjour ' . htmlspecialchars($prenom) . ',</p>

<p>Nous avons le plaisir de vous confirmer que l\'annulation de votre commande a bien été enregistrée.</p>
<p>Nous regrettons votre décision et espèrons avoir le plaisir de vous accompagner lors d’une prochaine commande.</p>

<p>A très vite ! </p>
<p>L\'équipe Vite et Gourmand</p>
';

$mail->AltBody =
"Confirmation de l'annulation de votre commande

Bonjour $prenom,

Nous avons le plaisir de vous confirmer que l'annulation de votre commande a bien été enregistrée.
Nous regrettons votre décision et espèrons avoir le plaisir de vous accompagner lors d’une prochaine commande.

A très vite ! 
L'équipe Vite et Gourmand";

// Envoi
$mail->send();
    
} catch (\Exception $e) {

error_log(
        "Erreur lors de l'envoi du mail d'annulation de la commande : "
        . $mail->ErrorInfo
    );
}
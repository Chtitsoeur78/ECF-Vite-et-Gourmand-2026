<?php

require_once __DIR__ . '/configuration_email.php';

/** @var string $email */
/** @var string $prenom */


$email_envoye = false;
try {

// Destinataire
$mail->addAddress($email);

// Objet
$mail->Subject = 'Confirmation de la modification de votre mot de passe';

// Corps du message
$mail->Body = '
<h2>Confirmation de la modification de votre mot de passe</h2>

<p>Bonjour ' . htmlspecialchars($prenom, ENT_QUOTES, 'UTF-8') . ',</p>

<p>Nous avons le plaisir de vous confirmer que la modification de votre mot de passe a bien été prise en compte.</p>
<p>Nous vous remercions de votre confiance.</p>

<p>A très vite ! </p>
<p>L\'équipe Vite et Gourmand</p>
';

$mail->AltBody =
"Confirmation de la modification de votre mot de passe

Bonjour $prenom,

Nous avons le plaisir de vous confirmer que la modification de votre mot de passe a bien été prise en compte.
Nous vous remercions de votre confiance.
A très vite ! 
L'équipe Vite et Gourmand";

// Envoi
$mail->send();
$email_envoye = true;
    
} catch (\Exception $e) {

error_log(
        "Erreur lors de l'envoi du mail de confirmation de changement de mot de passe : "
        . $mail->ErrorInfo
    );
}
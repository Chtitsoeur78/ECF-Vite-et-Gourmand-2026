<?php

require_once __DIR__ . '/configuration_email.php';

/** @var string $email */
/** @var string $prenom */
/** @var \PHPMailer\PHPMailer\PHPMailer $mail */

try {

// Destinataire
/** @var string $email */
$mail->addAddress($email);

// Objet
$mail->Subject = 'Retour de prêt du matériel Vite et Gourmand';

// Corps du message
$mail->Body = '
<h2>Retour de prêt du matériel Vite et Gourmand</h2>

<p>Bonjour ' . htmlspecialchars($prenom) . ',</p>

<p>Votre dernière commande livrée récemment par Vite et Gourmand comportait le prêt de matériel.</p>
<p>Nous vous informons que conformément à l\'article n° 8 (Livraisons) des Conditions Générales de Ventes, vous êtes dans l\'obligation de le rendre dans les dix jours ouvrés sous peine du paiement de 600 euros de frais de non retour.</p>
<p>Pour organiser ce retour, vous prendrez contact avec nous au 05.43.27.41.13 ou par le formulaire de contact rubrique Autres.</p>
<p>En vous en remerciant par avance.</p>

<p>A très vite !<br>
L\'équipe Vite et Gourmand</p>
';

$mail->AltBody =
"Retour de prêt du matériel Vite et Gourmand

Bonjour $prenom,
Votre dernière commande livrée récemment par Vite et Gourmand comportait le prêt de matériel.
Nous vous informons que conformément à l'article n° 8 (Livraisons) des Conditions Générales de Ventes, vous êtes dans l'obligation de le rendre dans les dix jours ouvrés sous peine du paiement de 600 euros de frais de non retour.
Pour organiser ce retour, vous prendrez contact avec nous au 05.43.27.41.13 ou par le formulaire de contact rubrique Autres.
En vous en remerciant par avance.

A très vite !
L'équipe Vite et Gourmand";

// Envoi
$mail->send();

} catch (Exception $e) {

error_log(
        "Erreur lors de l'envoi du mail de rappel du retour du matériel."
        . $mail->ErrorInfo
    );
}
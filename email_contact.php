<?php

require_once __DIR__ . '/configuration_email.php';

/** @var string $pseudo */
/** @var string $prenom */
/** @var string $nom */
/** @var string $email */
/** @var string $sujet */
/** @var string $titre */
/** @var string $message */

try {

// Destinataire
$mail->addAddress('chtitsoeur@gmail.com');

// Adresse à laquelle répondre
$mail->addReplyTo($email, $prenom . ' ' . $nom);

// Objet
$mail->Subject = 'Formulaire Contact';

// Corps du message
$mail->Body = '
<h2>Nouveau message reçu depuis le formulaire de contact</h2>

<p><strong>Pseudonyme :</strong> ' . htmlspecialchars($pseudo) . '</p>
<p><strong>Prénom :</strong> ' . htmlspecialchars($prenom) . '</p>
<p><strong>Nom :</strong> ' . htmlspecialchars($nom) . '</p>
<p><strong>Adresse électronique :</strong> ' . htmlspecialchars($email) . '</p>
<p><strong>Sujet :</strong> ' . htmlspecialchars($sujet) . '</p>
<p><strong>Titre :</strong> ' . htmlspecialchars($titre) . '</p>
<p><strong>Message :</strong><br>' .nl2br(htmlspecialchars($message)) .
'</p>
';

$mail->AltBody =
"Nouveau message reçu depuis le formulaire de contact

Pseudonyme : $pseudo
Prénom : $prenom
Nom : $nom
Adresse électronique : $email
Sujet : $sujet
Titre : $titre
Message : $message";

// Envoi
$mail->send();
    
} catch (\Exception $e) {

error_log(
        "Erreur lors de la réception par Vite et Gourmand du formulaire de contact : "
        . $mail->ErrorInfo
    );
}
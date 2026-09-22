<?php

require_once __DIR__ . '/configuration_email.php';

try {

// Destinataire
/** @var string $email */
/** @var string $prenom */
$mail->addAddress($email);

// Objet
$mail->Subject = 'Bienvenue dans notre entreprise Vite et Gourmand';

// Corps du message
$mail->Body = '
<h2>Bienvenue dans notre entreprise Vite et Gourmand</h2>
<p>Bonjour ' . htmlspecialchars($prenom) . ',</p>
<p>Je suis heureux de vous accueillir chez Vite et Gourmand.</p>
<p>En tant qu\'administrateur du site, et pour que vous puissiez commencer votre mission dans de bonnes conditions, je vous ai créé un compte Employé.</p>
<p>Votre identifiant sera votre boite mail (prenom.nom@viteetgourmand.eu).</p>
<p>Lors de votre premier jour parmi nous, je vous communiquerai personnellement le mot de passe utilisé, que vous pourrez modifier quand vous voudrez.</p>
<p>A très bientôt ! </p>
<p>José TOC </p>
<p>Gérant - Administrateur de Vite et Gourmand</p>
';

$mail->AltBody =
"Bienvenue dans notre entreprise Vite et Gourman

Je suis heureux de vous accueillir chez Vite et Gourmand.
En tant qu'administrateur du site, et pour que vous puissiez commencer votre mission dans de bonnes conditions, je vous ai créé un compte Employé.
Votre identifiant sera votre boite mail (prenom.nom@viteetgourmand.eu).
Lors de votre premier jour parmi nous, je vous communiquerai personnellement le mot de passe utilisé, que vous pourrez modifier quand vous voudrez.
A très bientôt ! 
José TOC 
Gérant - Administrateur de Vite et Gourmand
";

// Envoi
$mail->send();
    
} catch (Exception $e) {

error_log(
        "Erreur lors de l'envoi du mail de commande : "
        . $mail->ErrorInfo
    );

}
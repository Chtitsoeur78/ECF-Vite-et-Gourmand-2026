<?php

session_start();

require_once "connexion.php";

// Vérification de la méthode d'envoi du formulaire
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo "Méthode non autorisée.";
    exit;
}

// Récupération de l'adresse e-mail
$email = trim($_POST["email"] ?? "");

// Vérification du champ obligatoire
if (empty($email)) {
    echo "Merci de renseigner votre adresse e-mail.";
    exit;
}

// Vérification du format de l'adresse e-mail
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "L'adresse e-mail saisie n'est pas valide.";
    exit;
}

try {
    // Recherche de l'utilisateur à partir de son adresse e-mail
    $sql = "SELECT prenom, email
            FROM utilisateurs
            WHERE email = :email";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":email" => $email
    ]);

    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    /*
     * Le même message est affiché, que l'adresse existe ou non.
     * Cela empêche une personne de vérifier quelles adresses possèdent un compte sur le site.
     */

    if (!$utilisateur) {
        echo "Si cette adresse e-mail correspond à un compte, un lien de réinitialisation vous sera envoyé.";
        exit;
    }

    // Création d'un jeton aléatoire sécurisé
    $jeton = bin2hex(random_bytes(32));

    // Création de l'empreinte du jeton enregistrée en base de données
    $empreinte_jeton = hash("sha256", $jeton);

    // Le lien restera valable pendant une heure
    $expiration_jeton = date("Y-m-d H:i:s", time() + 3600);

    // Enregistrement de l'empreinte et de sa date d'expiration
    $sql = "UPDATE utilisateurs
            SET jeton_reinitialisation = :jeton_reinitialisation,
                expiration_jeton = :expiration_jeton
            WHERE email = :email";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":jeton_reinitialisation" => $empreinte_jeton,
        ":expiration_jeton" => $expiration_jeton,
        ":email" => $email
    ]);

} catch (PDOException $e) {
    error_log("Erreur PDO lors de la demande de réinitialisation : " . $e->getMessage());

    echo "Une erreur est survenue. Merci de réessayer ultérieurement.";
    exit;
}

// Adresse de la future page permettant de choisir le nouveau mot de passe
$lien_reinitialisation =
    "http://localhost/ecf/reinitialisation_motdepasse_utilisateur.php?jeton="
    . urlencode($jeton);

// Configuration de PHPMailer
require_once __DIR__ . "/configuration_email.php";

try {
    $prenom = $utilisateur["prenom"];

    $mail->addAddress($email, $prenom);

    $mail->Subject = "Réinitialisation de votre mot de passe";

    $mail->Body = '
        <h2>Réinitialisation de votre mot de passe</h2>

        <p>Bonjour ' . htmlspecialchars($prenom, ENT_QUOTES, "UTF-8") . ',</p>

        <p>Vous avez demandé la réinitialisation de votre mot de passe.</p>

        <p>
            <a href="' . htmlspecialchars($lien_reinitialisation, ENT_QUOTES, "UTF-8") . '">
                Choisir un nouveau mot de passe
            </a>
        </p>

        <p>Ce lien est valable pendant une heure.</p>

        <p>Si vous n\'êtes pas à l\'origine de cette demande, vous pouvez ignorer ce message.</p>

        <p>A très vite !</p>

        <p>José TOC et l\'équipe Vite et Gourmand</p>
    ';

    $mail->AltBody =
        "Bonjour $prenom,\n\n"

        . "Vous avez demandé la réinitialisation de votre mot de passe.\n\n"
        . "Utilisez le lien suivant pour choisir un nouveau mot de passe :\n"
        . $lien_reinitialisation . "\n\n"
        . "Ce lien est valable pendant une heure.\n\n"
        . "Si vous n'êtes pas à l'origine de cette demande, vous pouvez ignorer ce message.\n\n"
        
        . "A très vite !\n"
        . "José TOC et l'équipe Vite et Gourmand";

    $mail->send();

    echo "Votre demande a bien été prise en compte. Si un compte est associé à cette adresse e-mail, vous recevrez un lien de réinitialisation.";

} catch (Throwable $e) {
    error_log("Erreur lors de l'envoi du courriel de réinitialisation : " . $e->getMessage());

    echo "Le courriel de réinitialisation n'a pas pu être envoyé. Merci de réessayer ultérieurement.";
}
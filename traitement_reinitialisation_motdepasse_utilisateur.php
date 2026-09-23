<?php

session_start();

require_once "connexion.php";

// Vérification de la méthode d'envoi du formulaire
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo "Méthode non autorisée.";
    exit;
}

// Récupération des informations envoyées par le formulaire
$jeton = $_POST["jeton"] ?? "";
$mot_de_passe = $_POST["mot_de_passe"] ?? "";
$mot_de_passe_confirme = $_POST["mot_de_passe_confirme"] ?? "";

// Vérification du format du jeton
if (strlen($jeton) !== 64 || !ctype_xdigit($jeton)) {
    echo "Le lien de réinitialisation n'est pas valide.";
    exit;
}

// Vérification des champs de mot de passe
if (empty($mot_de_passe) || empty($mot_de_passe_confirme)) {
    echo "Merci de remplir les deux champs de mot de passe.";
    exit;
}

// Vérification de la longueur du mot de passe
if (strlen($mot_de_passe) < 10) {
    echo "Le mot de passe doit contenir au moins 10 caractères.";
    exit;
}

// Comparaison des deux mots de passe
if ($mot_de_passe !== $mot_de_passe_confirme) {
    echo "Les deux mots de passe sont différents.";
    exit;
}

// Création de l'empreinte du jeton reçu
$empreinte_jeton = hash("sha256", $jeton);

try {
    /*
     * Recherche de l'utilisateur correspondant au jeton.
     * La date d'expiration doit être postérieure à la date actuelle.
     */
    $sql = "SELECT prenom, email
            FROM utilisateurs
            WHERE jeton_reinitialisation = :jeton_reinitialisation
            AND expiration_jeton > NOW()";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":jeton_reinitialisation" => $empreinte_jeton
    ]);

    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$utilisateur) {
        echo "Ce lien de réinitialisation est invalide ou a expiré.";
        exit;
    }

    // Hachage du nouveau mot de passe
    $mot_de_passe_hash = password_hash(
        $mot_de_passe,
        PASSWORD_DEFAULT
    );

    /*
     * Modification du mot de passe.
     * Le jeton et son expiration sont supprimés pour empêcher une seconde utilisation du même lien. */
    $sql = "UPDATE utilisateurs
            SET mot_de_passe = :mot_de_passe,
                jeton_reinitialisation = NULL,
                expiration_jeton = NULL
            WHERE jeton_reinitialisation = :jeton_reinitialisation
            AND expiration_jeton > NOW()";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":mot_de_passe" => $mot_de_passe_hash,
        ":jeton_reinitialisation" => $empreinte_jeton
    ]);

    if ($stmt->rowCount() === 0) {
        echo "Le mot de passe n'a pas pu être modifié.";
        exit;
    }

} catch (PDOException $e) {
    error_log(
        "Erreur PDO lors de la réinitialisation du mot de passe : "
        . $e->getMessage()
    );

    echo "Une erreur est survenue. Merci de réessayer ultérieurement.";
    exit;
}

// Envoi du courriel confirmant la modification
try {
    $prenom = $utilisateur["prenom"];
    $email = $utilisateur["email"];
    $email_envoye = false;
    require_once __DIR__
        . "/email_confirmation_reinitialisation_motdepasse_utilisateur.php";

    if ($email_envoye) {
        echo "Votre mot de passe a bien été modifié. "
            . "Un courriel de confirmation vous a été envoyé.";
    } else {
        echo "Votre mot de passe a bien été modifié, "
            . "mais le courriel de confirmation n'a pas pu être envoyé.";
    }

} catch (Throwable $e) {
    error_log(
        "Erreur lors du chargement du courriel de confirmation : "
        . $e->getMessage()
    );

    echo "Votre mot de passe a bien été modifié, "
        . "mais le courriel de confirmation n'a pas pu être envoyé.";
}
<?php
session_start();

require_once "connexion.php";            // Pour vérifier la commande dans MySQL 
require_once "connexion_mongodb.php";       //Pour enregistrer l'avis dans MongoDB

// Vérification du formulaire
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo "Méthode non autorisée.";
    exit;
}

if (!isset($_SESSION["id_utilisateur"])) {
    header("Location: connexion.php");
    exit;
}
    $id_utilisateur = (int) $_SESSION["id_utilisateur"];


    $id_commande = (int) ($_POST["id_commande"] ?? 0);
    $pseudo = trim($_POST["pseudo"] ?? '');
    $note = filter_input(INPUT_POST, "note", FILTER_VALIDATE_INT);
    $message = trim($_POST["message"] ?? '');
    $date_avis = date('Y-m-d H:i:s');
    $statut_avis = "en_attente";
    
   if ($id_commande <= 0 || $message === '') {
    echo "Les champs avec * sont obligatoires.";
    exit;
}

if ($note === false || $note === null || $note < 0 || $note > 5) {
    echo "La note doit être comprise entre 0 et 5.";
    exit;
}

// Vérification de la commande dans MySQL
$requete = $pdo->prepare("
    SELECT id_commande
    FROM commandes
    WHERE id_commande = :id_commande
      AND id_utilisateur = :id_utilisateur
      AND id_statut_commande = 7
");

$requete->execute([
    ":id_commande" => $id_commande,
    ":id_utilisateur" => $id_utilisateur
]);

$commande = $requete->fetch(PDO::FETCH_ASSOC);

if (!$commande) {
    echo "Cette commande ne permet pas l'envoi d'un avis.";
    exit;
}

// Vérification de l'absence d'un avis pour cette commande
$avis_existant = $collectionAvis->findOne([
    "id_commande" => $id_commande,
    "id_utilisateur" => $id_utilisateur
]);

if ($avis_existant !== null) {
    echo "Vous avez déjà envoyé un avis pour cette commande.";
    exit;
}
// Enregistrement de l'avis dans MongoDB
try {
    $resultat = $collectionAvis->insertOne([
        "id_commande" => $id_commande,
        "id_utilisateur" => $id_utilisateur,
        "pseudo" => $pseudo,
        "note" => $note,
        "commentaire" => $message,
        "date_avis" => $date_avis,
       "statut_avis" => $statut_avis
    ]);

    if ($resultat->getInsertedCount() === 1) {
        header("Location: espace_utilisateur.php?avis=envoye");
        exit;
    }

    echo "L'avis n'a pas pu être enregistré.";

} catch (Throwable $e) {
    error_log(
        "Erreur lors de l'enregistrement de l'avis : "
        . $e->getMessage()
    );

    echo "Une erreur est survenue lors de l'envoi de l'avis.";
}

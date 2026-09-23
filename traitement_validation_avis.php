<?php
use MongoDB\BSON\ObjectId;
session_start();

require_once "connexion_mongodb.php";

// Autoriser uniquement l'envoi du formulaire
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo "Méthode non autorisée.";
    exit;
}

// Vérifier que la personne connectée est administrateur ou employé
$id_role = (int) ($_SESSION["id_role"] ?? 0);

if (!in_array($id_role, [1, 2], true)) {
    header("Location: formulaire_connexion_administration.php");
    exit;
}

// Récupération des données
$id_avis = trim($_POST["id_avis"] ?? "");
$action = $_POST["action"] ?? "";

if ($id_avis === "" || !in_array($action, ["valider", "refuser"], true)) {
    echo "Les informations transmises sont incorrectes.";
    exit;
}

// Conversion de l'identifiant reçu en ObjectId MongoDB
try {
    $objectId = new ObjectId($id_avis);
} catch (Throwable $e) {
    echo "L'identifiant de l'avis est incorrect.";
    exit;
}

try {
    if ($action === "valider") {
        $nouveau_statut = "valide";
    } else {
        $nouveau_statut = "refuse";
    }

    $resultat = $collectionAvis->updateOne(
        [
            "_id" => $objectId,
            "statut_avis" => "en_attente"
        ],
        [
            '$set' => [
                "statut_avis" => $nouveau_statut
            ]
        ]
    );

    if ($resultat->getMatchedCount() !== 1) {
        echo "Cet avis n'existe pas ou a déjà été traité.";
        exit;
    }

    $message_redirection = $nouveau_statut;

    // Retour vers l'espace correspondant au rôle
    if ($id_role === 1) {
        header(
            "Location: espace_administrateur.php?avis="
            . $message_redirection
        );
    } else {
        header(
            "Location: espace_employe.php?avis="
            . $message_redirection
        );
    }

    exit;

} catch (Throwable $e) {
    error_log(
        "Erreur lors du traitement de l'avis : "
        . $e->getMessage()
    );

    echo "Une erreur est survenue lors du traitement de l'avis.";
}
<?php

session_start();
require_once 'connexion.php';

// Vérification du rôle
$id_role = (int) ($_SESSION['id_role'] ?? 0);

if (!in_array($id_role, [1, 2], true)) {
    header('Location: formulaire_connexion_administration.php');
    exit;
}

// Choix de la page de retour
if ($id_role === 1) {
    $page_retour = 'espace_administrateur.php';
} else {
    $page_retour = 'espace_employe.php';
}

// Autoriser uniquement l’envoi du formulaire
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $page_retour);
    exit;
}

// Récupération des données
$id_commande = isset($_POST['id_commande'])
    ? (int) $_POST['id_commande']
    : 0;

$id_statut_commande = isset($_POST['id_statut_commande'])
    ? (int) $_POST['id_statut_commande']
    : 0;

$statuts_autorises = [1, 2, 3, 4, 5, 6, 7, 8];

// Vérification des données
if (
    $id_commande <= 0 ||
    !in_array($id_statut_commande, $statuts_autorises, true)
) {
    header('Location: ' . $page_retour);
    exit;
}

/*
 * Récupération des informations de la commande
 * avant la modification de son statut.
 */

$sql_commande = "
    SELECT
        commandes.id_statut_commande AS ancien_statut,
        commandes.pret_materiel,
        commandes.retour_materiel,
        utilisateurs.email,
        utilisateurs.prenom
    FROM commandes
    INNER JOIN utilisateurs
        ON commandes.id_utilisateur = utilisateurs.id_utilisateur
    WHERE commandes.id_commande = :id_commande
";

$stmt_commande = $pdo->prepare($sql_commande);

$stmt_commande->execute([
    ':id_commande' => $id_commande
]);

$commande = $stmt_commande->fetch(PDO::FETCH_ASSOC);

if (!$commande) {
    header('Location: ' . $page_retour);
    exit;
}

$ancien_statut = (int) $commande['ancien_statut'];
$pret_materiel = (int) $commande['pret_materiel'];
$retour_materiel = (int) $commande['retour_materiel'];

// Ne rien faire si le statut n’a pas changé
if ($ancien_statut === $id_statut_commande) {
    header('Location: ' . $page_retour);
    exit;
}

// Refuser le statut 6 si aucun matériel n’a été prêté
if (
    $id_statut_commande === 6 &&
    $pret_materiel !== 1
) {
    header('Location: ' . $page_retour);
    exit;
}

// Le passage du statut 6 au statut 7 confirme le retour du matériel
if (
    $ancien_statut === 6 &&
    $id_statut_commande === 7 &&
    $pret_materiel === 1
) {
    $retour_materiel = 1;
}

// Modification du statut
$sql = "
    UPDATE commandes
    SET
        id_statut_commande = :nouveau_statut,
        retour_materiel = :retour_materiel,
        date_modification_statut = NOW()
    WHERE id_commande = :id_commande
      AND id_statut_commande = :ancien_statut
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':nouveau_statut' => $id_statut_commande,
    ':retour_materiel' => $retour_materiel,
    ':ancien_statut' => $ancien_statut,
    ':id_commande' => $id_commande
]);

// Envoi des courriels si le statut a réellement été modifié
if ($stmt->rowCount() > 0) {
    $email = $commande['email'];
    $prenom = $commande['prenom'];

    // Demande de restitution du matériel
    if (
        $id_statut_commande === 6 &&
        $pret_materiel === 1
    ) {
        require __DIR__ . '/email_retour_materiel.php';
    }

    // Invitation à donner un avis
    if ($id_statut_commande === 7) {
        require __DIR__ . '/email_info_avis.php';
    }
}

header('Location: ' . $page_retour);
exit;
<?php
require_once 'connexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: espace_employe.php');
    exit;
}

$id_commande = $_POST['id_commande'] ?? null;
$id_commande = isset($_POST['id_commande'])
    ? (int) $_POST['id_commande']
    : 0;

$libelle_statut = $_POST['statut_commande'] ?? '';

$statuts_autorises = [
    "en attente d acceptation",
    "acceptee",
    "en preparation",
    "en cours de livraison",
    "livree",
    "en attente de retour de materiel",
    "terminee",
    "annulee",
];

if (
    !$id_commande ||
    !in_array($libelle_statut, $statuts_autorises, true)
) {
    header('Location: espace_employe.php');
    exit;
}

/* Récupération de l'ID du statut */
$sql_statut = "
    SELECT id_statut_commande
    FROM statut_commande
    WHERE libelle_statut = :libelle_statut
";

$stmt_statut = $pdo->prepare($sql_statut);

$stmt_statut->execute([
    ':libelle_statut' => $libelle_statut
]);

$id_statut = $stmt_statut->fetchColumn();

/* Mise à jour de la commande */
$sql = "
    UPDATE commandes
    SET id_statut_commande = :id_statut
    WHERE id_commande = :id_commande
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':id_statut' => $id_statut,
    ':id_commande' => $id_commande
]);

header('Location: espace_employe.php');
exit;

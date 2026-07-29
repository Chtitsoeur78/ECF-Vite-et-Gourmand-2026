<?php
require_once 'connexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: espace_employe.php');
    exit;
}

$id_menu = $_POST['id_menu'] ?? null;
$nom_menu = $_POST['nom_menu'] ?? '';
$description_menu = $_POST['description_menu'] ?? '';
$prix_menu = $_POST['prix_menu'] ?? null;
$statut_menu = $_POST['statut_menu'] ?? '';

$statuts_autorises = ['Actif', 'Inactif'];

if (
    !$id_menu ||
    trim($nom_menu) === '' ||
    $prix_menu === null ||
    !in_array($statut_menu, $statuts_autorises, true)
) {
    header('Location: espace_employe.php');
    exit;
}

$sql = "
    UPDATE menus
    SET 
        nom_menu = :nom_menu,
        description_menu = :description_menu,
        prix_menu = :prix_menu,
        statut_menu = :statut_menu
    WHERE id_menu = :id_menu
";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':nom_menu' => $nom_menu,
    ':description_menu' => $description_menu,
    ':prix_menu' => $prix_menu,
    ':statut_menu' => $statut_menu,
    ':id_menu' => $id_menu
]);

header('Location: espace_employe.php');
exit;
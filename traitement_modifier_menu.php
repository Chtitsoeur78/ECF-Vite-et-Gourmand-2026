<?php

session_start();
require_once "connexion.php";

// Vérification du rôle
$id_role = (int) ($_SESSION["id_role"] ?? 0);

if (!in_array($id_role, [1, 2], true)) {
    header("Location: formulaire_connexion_administration.php");
    exit;
}

// Autoriser uniquement l’envoi du formulaire
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: espace_employe.php");
    exit;
}

// Récupération des informations générales
$id_menu = (int) ($_POST["id_menu"] ?? 0);

$nom_menu = trim($_POST["nom_menu"] ?? "");

$date_mise_en_place = trim(
    $_POST["date_mise_en_place"] ?? ""
);

$nb_personnes_minimum = (int) (
    $_POST["nb_personnes_minimum"] ?? 0
);

$prix_par_personne_euros = str_replace(
    ",",
    ".",
    trim($_POST["prix_par_personne_euros"] ?? "")
);

$id_regime = (int) ($_POST["id_regime"] ?? 0);
$id_theme = (int) ($_POST["id_theme"] ?? 0);

$description_menu = trim(
    $_POST["description_menu"] ?? ""
);

// Récupération des cinq plats dans leur ordre
$plats = [
    1 => (int) ($_POST["entree_1"] ?? 0),
    2 => (int) ($_POST["entree_2"] ?? 0),
    3 => (int) ($_POST["plat_principal"] ?? 0),
    4 => (int) ($_POST["dessert_1"] ?? 0),
    5 => (int) ($_POST["dessert_2"] ?? 0)
];

// Vérification des données obligatoires
if (
    $id_menu <= 0 ||
    $nom_menu === "" ||
    $nb_personnes_minimum <= 0 ||
    !is_numeric($prix_par_personne_euros) ||
    (float) $prix_par_personne_euros <= 0 ||
    $id_regime <= 0 ||
    $id_theme <= 0 ||
    in_array(0, $plats, true)
) {
    header(
        "Location: modifier_menu.php?id_menu="
        . $id_menu
        . "&erreur=donnees"
    );
    exit;
}

// Vérification de la date si elle est renseignée
if ($date_mise_en_place !== "") {
    $date_verifiee = DateTime::createFromFormat(
        "Y-m-d",
        $date_mise_en_place
    );

    if (
        !$date_verifiee ||
        $date_verifiee->format("Y-m-d")
            !== $date_mise_en_place
    ) {
        header(
            "Location: modifier_menu.php?id_menu="
            . $id_menu
            . "&erreur=date"
        );
        exit;
    }
} else {
    $date_mise_en_place = null;
}

// Un même plat ne doit pas occuper deux positions
if (count(array_unique($plats)) !== 5) {
    header(
        "Location: modifier_menu.php?id_menu="
        . $id_menu
        . "&erreur=plats_identiques"
    );
    exit;
}

try {
    // Toutes les modifications seront validées ensemble
    $pdo->beginTransaction();

    // Modification des informations générales
    $sql_menu = "
        UPDATE menus
        SET
            nom_menu = :nom_menu,
            id_regime = :id_regime,
            id_theme = :id_theme,
            nb_personnes_minimum =
                :nb_personnes_minimum,
            prix_par_personne_euros =
                :prix_par_personne_euros,
            description_menu =
                :description_menu,
            date_mise_en_place =
                :date_mise_en_place
        WHERE id_menu = :id_menu
    ";

    $stmt = $pdo->prepare($sql_menu);

    $stmt->execute([
        ":nom_menu" => $nom_menu,
        ":id_regime" => $id_regime,
        ":id_theme" => $id_theme,
        ":nb_personnes_minimum" =>
            $nb_personnes_minimum,
        ":prix_par_personne_euros" =>
            $prix_par_personne_euros,
        ":description_menu" =>
            $description_menu,
        ":date_mise_en_place" =>
            $date_mise_en_place,
        ":id_menu" => $id_menu
    ]);

    // Suppression des anciennes associations
    $sql_suppression = "
        DELETE FROM menus_plats
        WHERE id_menu = :id_menu
    ";

    $stmt = $pdo->prepare($sql_suppression);
    $stmt->execute([
        ":id_menu" => $id_menu
    ]);

    // Enregistrement des cinq nouvelles associations
    $sql_ajout_plat = "
        INSERT INTO menus_plats (
            id_menu,
            id_plat,
            ordre_plat
        )
        VALUES (
            :id_menu,
            :id_plat,
            :ordre_plat
        )
    ";

    $stmt = $pdo->prepare($sql_ajout_plat);

    foreach ($plats as $ordre_plat => $id_plat) {
        $stmt->execute([
            ":id_menu" => $id_menu,
            ":id_plat" => $id_plat,
            ":ordre_plat" => $ordre_plat
        ]);
    }

    // Validation définitive de toutes les opérations
    $pdo->commit();

} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log($e->getMessage());

    die(
        "Une erreur est survenue pendant "
        . "la modification du menu."
    );
}

header("Location: espace_employe.php?menu_modifie=1");
exit;
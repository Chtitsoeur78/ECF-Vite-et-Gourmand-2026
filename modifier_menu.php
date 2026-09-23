<?php

session_start();
require_once "connexion.php";

// Autoriser uniquement l’administrateur et l’employé
$id_role = (int) ($_SESSION["id_role"] ?? 0);

if (!in_array($id_role, [1, 2], true)) {
    header("Location: formulaire_connexion_administration.php");
    exit;
}

// Récupération de l’identifiant transmis par le lien MODIFIER
$id_menu = isset($_GET["id_menu"])
    ? (int) $_GET["id_menu"]
    : 0;

if ($id_menu <= 0) {
    header("Location: espace_employe.php");
    exit;
}

// Récupération du menu
$sql_menu = "
    SELECT
        id_menu,
        nom_menu,
        id_regime,
        id_theme,
        nb_personnes_minimum,
        prix_par_personne_euros,
        description_menu,
        date_mise_en_place
    FROM menus
    WHERE id_menu = :id_menu
";

$stmt = $pdo->prepare($sql_menu);
$stmt->execute([
    ":id_menu" => $id_menu
]);

$menu = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$menu) {
    header("Location: espace_employe.php");
    exit;
}

// Récupération des cinq plats actuels du menu
$sql_plats_menu = "
    SELECT
        plat.id_plat,
        plat.nom_plat
    FROM menus_plats

    INNER JOIN plat
        ON menus_plats.id_plat = plat.id_plat

    WHERE menus_plats.id_menu = :id_menu
    ORDER BY menus_plats.id_plat
";

$stmt = $pdo->prepare($sql_plats_menu);
$stmt->execute([
    ":id_menu" => $id_menu
]);

$plats_menu = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupération de tous les plats disponibles
$sql_plats = "
    SELECT
        id_plat,
        nom_plat
    FROM plat
    ORDER BY nom_plat
";

$stmt = $pdo->prepare($sql_plats);
$stmt->execute();
$plats_disponibles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupération des régimes
$sql_regimes = "
    SELECT
        id_regime,
        libelle_regime
    FROM regime
    ORDER BY libelle_regime
";

$stmt = $pdo->prepare($sql_regimes);
$stmt->execute();
$regimes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupération des thèmes
$sql_themes = "
    SELECT
        id_theme,
        libelle_theme
    FROM theme
    ORDER BY libelle_theme
";

$stmt = $pdo->prepare($sql_themes);
$stmt->execute();
$themes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">
    <title>Modifier un menu - Vite et Gourmand</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/modifier_menu.css?v=2">
</head>
<body>
    <main>
        <section class="modification_menu">
            <h1>Modifier le <?= htmlspecialchars($menu["nom_menu"], ENT_QUOTES, "UTF-8") ?></h1>
            <form action="traitement_modifier_menu.php method="post">
            <input type="hidden" name="id_menu" value="<?= (int) $menu["id_menu"] ?>">
            <div>
                <label for="nom_menu">Titre du menu</label>

                <input
                    type="text"
                    id="nom_menu"
                    name="nom_menu"
                    value="<?= htmlspecialchars(
                        $menu["nom_menu"],
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>"
                    required
                >
            </div>
            <div>
                <label for="nb_personnes_minimum">
                    Nombre minimum de personnes
                </label>

                <input
                    type="number"
                    id="nb_personnes_minimum"
                    name="nb_personnes_minimum"
                    min="1"
                    value="<?= (int) $menu["nb_personnes_minimum"] ?>"
                    required
                >
            </div>

            <div>
                <label for="prix_par_personne_euros">
                    Prix par personne
                </label>

                <input
                    type="number"
                    id="prix_par_personne_euros"
                    name="prix_par_personne_euros"
                    min="0"
                    step="0.01"
                    value="<?= htmlspecialchars(
                        $menu["prix_par_personne_euros"],
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>"
                    required
                >
            </div>

            <?php
            $champs_plats = [
                "entree_1" => "Entrée 1",
                "entree_2" => "Entrée 2",
                "plat_principal" => "Plat principal",
                "dessert_1" => "Dessert 1",
                "dessert_2" => "Dessert 2"
            ];
            ?>

            <?php
            foreach (
                $champs_plats as $position => $libelle
            ):
                $index = array_search(
                    $position,
                    array_keys($champs_plats),
                    true
                );

                $id_plat_actuel = (int) (
                    $plats_menu[$index]["id_plat"] ?? 0
                );
            ?>
                <div>
                    <label for="<?= $position ?>">
                        <?= htmlspecialchars(
                            $libelle,
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>
                    </label>

                    <select
                        id="<?= $position ?>"
                        name="<?= $position ?>"
                        required
                    >
                        <?php foreach (
                            $plats_disponibles as $plat
                        ): ?>
                            <option
                                value="<?= (int) $plat["id_plat"] ?>"
                                <?php if (
                                    (int) $plat["id_plat"]
                                    === $id_plat_actuel
                                ): ?>
                                    selected
                                <?php endif; ?>
                            >
                                <?= htmlspecialchars(
                                    $plat["nom_plat"],
                                    ENT_QUOTES,
                                    "UTF-8"
                                ) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endforeach; ?>

            <div>
                <label for="id_regime">
                    Régime
                </label>

                <select
                    id="id_regime"
                    name="id_regime"
                    required
                >
                    <?php foreach ($regimes as $regime): ?>
                        <option
                            value="<?= (int) $regime["id_regime"] ?>"
                            <?php if (
                                (int) $regime["id_regime"]
                                === (int) $menu["id_regime"]
                            ): ?>
                                selected
                            <?php endif; ?>
                        >
                            <?= htmlspecialchars(
                                $regime["libelle_regime"],
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="id_theme">
                    Thème
                </label>

                <select
                    id="id_theme"
                    name="id_theme"
                    required
                >
                    <?php foreach ($themes as $theme): ?>
                        <option
                            value="<?= (int) $theme["id_theme"] ?>"
                            <?php if (
                                (int) $theme["id_theme"]
                                === (int) $menu["id_theme"]
                            ): ?>
                                selected
                            <?php endif; ?>
                        >
                            <?= htmlspecialchars(
                                $theme["libelle_theme"],
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="description_menu">
                    Description
                </label>

                <textarea
                    id="description_menu"
                    name="description_menu"
                    rows="5"
                ><?= htmlspecialchars(
                    $menu["description_menu"] ?? "",
                    ENT_QUOTES,
                    "UTF-8"
                ) ?></textarea>
            </div>

            <button type="submit">
                Enregistrer les modifications
            </button>

            <a href="espace_employe.php">
                Annuler
            </a>
        </form>
    </section>
</main>

</body>
</html>
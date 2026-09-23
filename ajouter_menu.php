<?php

session_start();

require_once "connexion.php";

// Vérification du rôle
$id_role = (int) ($_SESSION["id_role"] ?? 0);

if (!in_array($id_role, [1, 2], true)) {
    header("Location: formulaire_connexion_administration.php");
    exit;
}

// Choix de la page de retour
if ($id_role === 1) {
    $page_retour = "espace_administrateur.php";
} else {
    $page_retour = "espace_employe.php";
}

$message = "";

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Récupération des données du menu
    $nom_menu = trim($_POST["nom_menu"] ?? "");
    $nb_personnes_minimum = (int) ($_POST["nb_personnes_minimum"] ?? 0);
    $prix_par_personne_euros = (float) ($_POST["prix_par_personne_euros"] ?? 0);
    $id_regime = (int) ($_POST["id_regime"] ?? 0);
    $id_theme = (int) ($_POST["id_theme"] ?? 0);
    $description_menu = trim($_POST["description_menu"] ?? "");

    // Récupération des 5 plats
    $entree1 = (int) ($_POST["entree1"] ?? 0);
    $entree2 = (int) ($_POST["entree2"] ?? 0);
    $plat_principal = (int) ($_POST["plat_principal"] ?? 0);
    $dessert1 = (int) ($_POST["dessert1"] ?? 0);
    $dessert2 = (int) ($_POST["dessert2"] ?? 0);

    // Vérification des champs obligatoires
    if (
        $nom_menu === "" ||
        $nb_personnes_minimum <= 0 ||
        $prix_par_personne_euros <= 0 ||
        $id_regime <= 0 ||
        $id_theme <= 0 ||
        $description_menu === "" ||
        $entree1 <= 0 ||
        $entree2 <= 0 ||
        $plat_principal <= 0 ||
        $dessert1 <= 0 ||
        $dessert2 <= 0
    ) {
        $message = "Merci de remplir tous les champs obligatoires.";
    } else {

        try {

            $pdo->beginTransaction();

            // Création du menu
            $sql = "INSERT INTO menus
                    (
                        nom_menu,
                        id_regime,
                        id_theme,
                        nb_personnes_minimum,
                        prix_par_personne_euros,
                        description_menu
                    )
                    VALUES
                    (
                        :nom_menu,
                        :id_regime,
                        :id_theme,
                        :nb_personnes_minimum,
                        :prix_par_personne_euros,
                        :description_menu
                    )";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ":nom_menu" => $nom_menu,
                ":id_regime" => $id_regime,
                ":id_theme" => $id_theme,
                ":nb_personnes_minimum" => $nb_personnes_minimum,
                ":prix_par_personne_euros" => $prix_par_personne_euros,
                ":description_menu" => $description_menu
            ]);

            // Récupération de l'identifiant du nouveau menu
            $id_menu = (int) $pdo->lastInsertId();

            // Ajout des 5 plats au menu
            $sql_plat = "INSERT INTO menus_plats
                         (id_menu, id_plat, ordre_plat)
                         VALUES
                         (:id_menu, :id_plat, :ordre_plat)";

            $stmt_plat = $pdo->prepare($sql_plat);

            $plats_menu = [
                1 => $entree1,
                2 => $entree2,
                3 => $plat_principal,
                4 => $dessert1,
                5 => $dessert2
            ];

            foreach ($plats_menu as $ordre_plat => $id_plat) {

                $stmt_plat->execute([
                    ":id_menu" => $id_menu,
                    ":id_plat" => $id_plat,
                    ":ordre_plat" => $ordre_plat
                ]);
            }

            $pdo->commit();

            $message = "Le menu a bien été ajouté.";

        } catch (PDOException $e) {

            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $message = "Une erreur est survenue lors de l'ajout du menu.";
        }
    }
}

// Récupération des plats
$sql_plats = "
    SELECT id_plat, nom_plat
    FROM plat
    ORDER BY nom_plat
";

$stmt = $pdo->prepare($sql_plats);
$stmt->execute();
$plats = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupération des régimes
$sql_regimes = "
    SELECT id_regime, libelle_regime
    FROM regime
    ORDER BY libelle_regime
";

$stmt = $pdo->prepare($sql_regimes);
$stmt->execute();
$regimes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupération des thèmes
$sql_themes = "
    SELECT id_theme, libelle_theme
    FROM theme
    ORDER BY libelle_theme
";

$stmt = $pdo->prepare($sql_themes);
$stmt->execute();
$themes = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vite et Gourmand - Ajouter un menu</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/ajouter_menu.css">
</head>
<body>
<main>
    <section class="ajout_menu">
    <h1>Ajouter un nouveau menu</h1>
    <?php if ($message !== ""): ?>
    <p><?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?></p>
    <?php endif; ?>

    <form action="ajouter_menu.php" method="post">
        <div>
            <label for="nom_menu">Nom du menu :</label>
            <input type="text" id="nom_menu" name="nom_menu" required>
        </div>
        <div>
            <label for="nb_personnes_minimum">Nombre minimum de personnes :</label>
            <input type="number" id="nb_personnes_minimum" name="nb_personnes_minimum" min="1" required>
        </div>
        <div>
            <label for="prix_par_personne_euros">Prix par personne :</label>
            <input type="number" id="prix_par_personne_euros" name="prix_par_personne_euros" min="0" step="0.01" required>
        </div>
        <div>
            <label for="id_regime">Régime :</label>
            <select id="id_regime" name="id_regime" required>
                <option value="">Sélectionnez un régime</option>
                <?php foreach ($regimes as $regime): ?>
                    <option value="<?= (int) $regime["id_regime"] ?>">
                        <?= htmlspecialchars($regime["libelle_regime"], ENT_QUOTES, "UTF-8") ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="id_theme">Thème :</label>
            <select id="id_theme" name="id_theme" required>
                <option value="">Sélectionnez un thème</option>
                <?php foreach ($themes as $theme): ?>
                    <option value="<?= (int) $theme["id_theme"] ?>">
                        <?= htmlspecialchars($theme["libelle_theme"], ENT_QUOTES, "UTF-8") ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="entree1">Entrée 1 :</label>
            <select id="entree1" name="entree1" required>
                <option value="">Sélectionnez une entrée</option>
                <?php foreach ($plats as $plat): ?>
                    <option value="<?= (int) $plat["id_plat"] ?>">
                        <?= htmlspecialchars($plat["nom_plat"], ENT_QUOTES, "UTF-8") ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="entree2">Entrée 2 :</label>
            <select id="entree2" name="entree2" required>
                <option value="">Sélectionnez une entrée</option>
                <?php foreach ($plats as $plat): ?>
                    <option value="<?= (int) $plat["id_plat"] ?>">
                        <?= htmlspecialchars($plat["nom_plat"], ENT_QUOTES, "UTF-8") ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="plat_principal">Plat principal :</label>
            <select id="plat_principal" name="plat_principal" required>
                <option value="">Sélectionnez un plat</option>
                <?php foreach ($plats as $plat): ?>
                    <option value="<?= (int) $plat["id_plat"] ?>">
                        <?= htmlspecialchars($plat["nom_plat"], ENT_QUOTES, "UTF-8") ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="dessert1">Dessert 1 :</label>
            <select id="dessert1" name="dessert1" required>
                <option value="">Sélectionnez un dessert</option>
                <?php foreach ($plats as $plat): ?>
                    <option value="<?= (int) $plat["id_plat"] ?>">
                        <?= htmlspecialchars($plat["nom_plat"], ENT_QUOTES, "UTF-8") ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="dessert2">Dessert 2 :</label>
            <select id="dessert2" name="dessert2" required>
                <option value="">Sélectionnez un dessert</option>
                <?php foreach ($plats as $plat): ?>
                    <option value="<?= (int) $plat["id_plat"] ?>">
                        <?= htmlspecialchars($plat["nom_plat"], ENT_QUOTES, "UTF-8") ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="description_menu">Description du menu :</label>
            <textarea id="description_menu" name="description_menu" required></textarea>
        </div>
        <button type="submit">Ajouter le menu</button>
    </form>
    <p>
        <a href="<?= htmlspecialchars($page_retour, ENT_QUOTES, "UTF-8") ?>">
            Retour à l'espace de gestion
        </a>
    </p>
</main>
</body>
</html>
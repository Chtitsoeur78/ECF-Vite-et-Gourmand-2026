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

    // Récupération des données
    $nom_plat = trim($_POST["nom_plat"] ?? "");
    $photo = trim($_POST["photo"] ?? "");
    $pret_materiel = (int) ($_POST["pret_materiel"] ?? 0);

    // Vérification des champs obligatoires
    if ($nom_plat === "" || $photo === "") {
        $message = "Merci de remplir tous les champs obligatoires.";
    } elseif (!in_array($pret_materiel, [0, 1], true)) {
        $message = "La valeur du prêt de matériel n'est pas valide.";
    } else {

        // Ajout du plat dans la base de données
        $sql = "INSERT INTO plat
                (nom_plat, photo, pret_materiel)
                VALUES
                (:nom_plat, :photo, :pret_materiel)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ":nom_plat" => $nom_plat,
            ":photo" => $photo,
            ":pret_materiel" => $pret_materiel
        ]);

        $message = "Le plat a bien été ajouté.";
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vite et Gourmand - Ajouter un plat</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/ajouter_plat.css">
</head>
<body>
<main>
    <section class="ajout_plat">
        <h1>Ajouter un nouveau plat</h1>

<?php if ($message !== ""): ?>
    <p><?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?></p>
<?php endif; ?>

    <form action="ajouter_plat.php" method="post">
        <div>
            <label for="nom_plat">Nom du plat :</label>
            <input type="text" id="nom_plat" name="nom_plat" maxlength="100" required>
        </div>

        <div>
            <label for="photo">Chemin de la photo :</label>
            <input type="text" id="photo" name="photo" maxlength="255" placeholder="images/nom_dossier/nom_image.png">
        </div>
        <fieldset>
            <legend>Prêt de matériel :</legend>
            <label><input type="radio" name="pret_materiel" value="0" checked> Non</label>
            <label><input type="radio" name="pret_materiel" value="1">Oui</label>
        </fieldset>
        <button type="submit">Ajouter le plat</button>
    </form>
    <p>
        <a href="<?= htmlspecialchars($page_retour, ENT_QUOTES, "UTF-8") ?>">Retour à l'espace de gestion</a>
    </p>
</main>
</body>
</html>
<?php
session_start();

require_once "connexion.php";

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (!isset($_SESSION['id_utilisateur'])) {
    die("Utilisateur non connecté.");
}

$id_utilisateur = (int) $_SESSION['id_utilisateur'];

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    die("Méthode non autorisée.");
}

//Vérification du formulaire 
    $id_statut_commande = 1;
    $id_commune = trim($_POST["id_commune"] ?? '');
    $livraison_adresse = trim($_POST["livraison_adresse"] ?? "");
    $livraison_adresse_complement = trim($_POST["livraison_adresse_complement"] ?? "");
    $livraison_code_postal = trim($_POST["livraison_code_postal"] ?? "");
    $id_horaire_livraison = (int) ($_POST["id_horaire_livraison"] ?? 0);
    $prenom = trim($_POST["prenom"] ?? '');
    $nom = trim($_POST["nom"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $telephone = trim($_POST["telephone"] ?? '');
    $date_livraison = trim($_POST['date_livraison'] ?? '');
    $livraison_adresse_complement = trim($_POST['livraison_adresse_complement'] ?? '');
    $livraison_adresse = trim($_POST['livraison_adresse'] ?? '');
    $livraison_code_postal = trim($_POST['livraison_code_postal'] ?? '');
    $nom_menu = trim($_POST['nom_menu'] ?? '');
    $date_commande = date('Y-m-d H:i:s');
    $nb_personnes = (int) ($_POST['nb_personnes'] ?? 0);
    $nb_entree1 = (int) ($_POST['nb_entree1'] ?? 0);
    $nb_entree2 = (int) ($_POST['nb_entree2'] ?? 0);
    $nb_dessert1 = (int) ($_POST['nb_dessert1'] ?? 0);
    $nb_dessert2 = (int) ($_POST['nb_dessert2'] ?? 0);
    
    
        if (
        empty($prenom) || 
        empty($nom) || 
        empty($email) || 
        empty($telephone) || 
        empty($date_livraison) || 
        empty($livraison_adresse) || 
        empty($id_commune) || 
        empty($nom_menu)||
        empty($nb_personnes)) 
    {
        echo "Les champs avec * sont obligatoires.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Adresse email invalide.";
        exit;
    }

    if (!preg_match('/^[0-9]{10}$/', $_POST['telephone'])) {
    die("Numéro de téléphone invalide.");
}

    if ($date_livraison < date('Y-m-d')) {
    die("La date de livraison ne peut pas être passée.");
}

    if ($nom_menu === "menu_mariage" && $nb_personnes < 12) {
    die("Le menu Mariage nécessite un minimum de 12 convives.");
}

    if ($nom_menu !== "menu_mariage" && $nb_personnes < 4) {
    die("Le minimum de commande est de 4 convives.");
}

$total_entrees = $nb_entree1 + $nb_entree2;
$total_desserts = $nb_dessert1 + $nb_dessert2;

if ($total_entrees !== $nb_personnes) {
    die(
        "Erreur : le nombre total d'entrées doit être égal au nombre de personnes."
    );
}

if ($total_desserts !== $nb_personnes) {
    die(
        "Erreur : le nombre total de desserts doit être égal au nombre de personnes."
    );
}

$nb_personnes = (int) $_POST['nb_personnes'];
$id_menu = (int) $_POST['id_menu'];
$id_commune = (int) $_POST['id_commune'];

$requete = $pdo->prepare("
    SELECT
        prix_par_personne_euros,
        nb_personnes_minimum
    FROM menus
    WHERE id_menu = ?
");

$requete->execute([$id_menu]);
$menu = $requete->fetch(PDO::FETCH_ASSOC);

if (!$menu) {
    die("Menu introuvable.");
}

$requetePlats = $pdo->prepare("
    SELECT plat.pret_materiel
    FROM plat
    INNER JOIN menus_plats
        ON plat.id_plat = menus_plats.id_plat
    WHERE menus_plats.id_menu = ?
    ORDER BY plat.id_plat ASC
");

$requetePlats->execute([$id_menu]);
$platsDuMenu = $requetePlats->fetchAll(PDO::FETCH_COLUMN);

if (count($platsDuMenu) !== 5) {
    die("Les plats de ce menu sont incomplets.");
}

$pret_materiel = (
    (int) $platsDuMenu[2] === 1
    || ($nb_entree1 > 0 && (int) $platsDuMenu[0] === 1)
    || ($nb_entree2 > 0 && (int) $platsDuMenu[1] === 1)
    || ($nb_dessert1 > 0 && (int) $platsDuMenu[3] === 1)
    || ($nb_dessert2 > 0 && (int) $platsDuMenu[4] === 1)
) ? 1 : 0;

$requete = $pdo->prepare("
    SELECT frais_livraison_euros
    FROM commune_gironde
    WHERE id_commune = ?
");
$requete->execute([$id_commune]);
$commune = $requete->fetch(PDO::FETCH_ASSOC);

if (!$commune) {
    die("Commune introuvable.");
}

$prix_menu_initial =
    $nb_personnes * (float) $menu['prix_par_personne_euros'];

$seuil_reduction =
    (int) $menu['nb_personnes_minimum'] + 5;

if ($nb_personnes >= $seuil_reduction) {
    $reduction = $prix_menu_initial * 0.10;
} else {
    $reduction = 0;
}

$prix_menu = round(
    $prix_menu_initial - $reduction,
    2
);

$frais_livraison_euros =
    (float) $commune['frais_livraison_euros'];

$prix_total = round(
    $prix_menu + $frais_livraison_euros,
    2
);

//Insertion en base de données
    $sql = "INSERT INTO commandes (
            id_statut_commande,
            id_commune,
            livraison_adresse,
            livraison_adresse_complement,
            livraison_code_postal,
            id_utilisateur,
            date_livraison, 
            id_horaire_livraison,
            nom_menu, 
            nombre_personnes,
            prix_menu,
            frais_livraison_euros,
            prix_total, 
            date_commande,
            nombre_entree1,
            nombre_entree2,
            nombre_dessert1, 
            nombre_dessert2,
            pret_materiel  
            )

            VALUES (
            :id_statut_commande,
            :id_commune,
            :livraison_adresse,
            :livraison_adresse_complement,
            :livraison_code_postal,
            :id_utilisateur,
            :date_livraison,
            :id_horaire_livraison,
            :nom_menu, 
            :nb_personnes, 
            :prix_menu, 
            :frais_livraison_euros,
            :prix_total,
            :date_commande,
            :nb_entree1,
            :nb_entree2,
            :nb_dessert1,  
            :nb_dessert2,
            :pret_materiel
    )";
            
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id_statut_commande' => $id_statut_commande,
            ':id_commune' => $id_commune,
            ':livraison_adresse' => $livraison_adresse,
            ':livraison_adresse_complement' => $livraison_adresse_complement,
            ':livraison_code_postal' => $livraison_code_postal,
            ':id_utilisateur' => $id_utilisateur,
            ':date_livraison' => $date_livraison,
            ':id_horaire_livraison' => $id_horaire_livraison,
            ':nom_menu' => $nom_menu,
            ':nb_personnes' => $nb_personnes,
            ':prix_menu' => $prix_menu,
            ':frais_livraison_euros' => $frais_livraison_euros,
            ':prix_total' => $prix_total,
            ':date_commande' => $date_commande,
            ':nb_entree1' => $nb_entree1,
            ':nb_entree2' => $nb_entree2,
            ':nb_dessert1' => $nb_dessert1,
            ':nb_dessert2' => $nb_dessert2,
            ':pret_materiel' => $pret_materiel
        ]);

        // Envoi du mail de confirmation
        require_once __DIR__ . '/email_confirmation_commande.php';
        
        header("Location: espace_utilisateur.php?commande=success");
exit;
}

catch (PDOException $e) {
    error_log(
        "Erreur d'enregistrement de la commande : " . $e->getMessage()
    );

    die("Une erreur est survenue lors de l'enregistrement de la commande.");
}
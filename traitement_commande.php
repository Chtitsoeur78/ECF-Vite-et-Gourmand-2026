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
    $id_statut_commande = trim($_POST["id_statut_commande"] ?? 0);
    $id_commune = trim($_POST["id_commune"] ?? '');
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

$nb_personnes = (int) $_POST['nb_personnes'];
$id_menu = (int) $_POST['id_menu'];
$id_commune = (int) $_POST['id_commune'];

$requete = $pdo->prepare("
    SELECT prix_par_personne_euros
    FROM menus
    WHERE id_menus = ?
");

$requete->execute([$id_menu]);
$menu = $requete->fetch(PDO::FETCH_ASSOC);

if (!$menu) {
    die("Menu introuvable.");
}

$requete = $pdo->prepare("
    SELECT frais_livraison_euros
    FROM communes_gironde
    WHERE id_commune = ?
");
$requete->execute([$id_commune]);
$commune = $requete->fetch(PDO::FETCH_ASSOC);

if (!$commune) {
    die("Commune introuvable.");
}

$prix_menu = $nb_personnes * $menu['prix_par_personne_euros'];
$frais_livraison_euros = $commune['frais_livraison_euros'];
$prix_total = $prix_menu + $frais_livraison_euros;


//Insertion en base de données
    $sql = "INSERT INTO commandes (
            id_statut_commande,
            id_utilisateur,
            date_livraison, 
            id_horaire_livraison,
            id_commune,
            nom_menu, 
            nb_personnes,
            prix_menu,
            frais_livraison_euros,
            prix_total, 
            date_commande,
            nb_entree1,
            nb_entree2,
            nb_dessert1, 
            nb_dessert2
    )

            VALUES (
            :id_statut_commande,
            :id_utilisateur,
            :date_livraison,
            :id_horaire_livraison,
            :id_commune,
            :nom_menu, 
            :nb_personnes, 
            :prix_menu, 
            :frais_livraison_euros,
            :prix_total,
            :date_commande,
            :nb_entree1,
            :nb_entree2,
            :nb_dessert1,  
            :nb_dessert2
    )";
            
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id_statut_commande' => $id_statut_commande,
            ':id_utilisateur' => $id_utilisateur,
            ':date_livraison' => $date_livraison,
            ':id_horaire_livraison' => $id_horaire_livraison,
            ':id_commune' => $id_commune,
            ':nom_menu' => $nom_menu,
            ':nb_personnes' => $nb_personnes,
            ':prix_menu' => $prix_menu,
            ':frais_livraison_euros' => $frais_livraison_euros,
            ':prix_total' => $prix_total,
            ':date_commande' => $date_commande,
            ':nb_entree1' => $nb_entree1,
            ':nb_entree2' => $nb_entree2,
            ':nb_dessert1' => $nb_dessert1,
            ':nb_dessert2' => $nb_dessert2
        ]);

        header("Location: espace_utilisateur.php?commande=success");
exit;

} catch (PDOException $e) {
    die("Erreur lors de l'enregistrement de la commande.");
}
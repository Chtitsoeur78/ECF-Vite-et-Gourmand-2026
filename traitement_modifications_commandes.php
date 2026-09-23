<?php

session_start();

require_once __DIR__ . "/connexion.php";

// Vérification de la méthode d'envoi
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Méthode non autorisée.");
}

// Vérification de la connexion de l'utilisateur
if (!isset($_SESSION["id_utilisateur"])) {
    header("Location: formulaire_connexion_utilisateur.php");
    exit;
}

$id_utilisateur = (int) $_SESSION["id_utilisateur"];

$id_commande = filter_input(
    INPUT_POST,
    "id_commande",
    FILTER_VALIDATE_INT
);

if (!$id_commande) {
    header("Location: espace_utilisateur.php");
    exit;
}

if ($_POST["action"] === "annuler") {
    $sql = "
        UPDATE commandes
        SET id_statut_commande = 8
        WHERE id_commande = :id_commande
          AND id_utilisateur = :id_utilisateur
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        "id_commande" => $id_commande,
        "id_utilisateur" => $id_utilisateur
    ]);
    
    // Récupération du prénom et de l'adresse électronique
    $sql_utilisateur = "
        SELECT prenom, email
        FROM utilisateurs
        WHERE id_utilisateur = :id_utilisateur
    ";

    $stmt_utilisateur = $pdo->prepare($sql_utilisateur);

    $stmt_utilisateur->execute([
        ":id_utilisateur" => $id_utilisateur
    ]);

    $utilisateur = $stmt_utilisateur->fetch(PDO::FETCH_ASSOC);

    $prenom = $utilisateur["prenom"];
    $email = $utilisateur["email"];

    // Envoi du courriel de confirmation de l'annulation 
    require_once __DIR__
        . "/email_confirmation_annulation_commandes.php";

        header("Location: espace_utilisateur.php?annulation=reussie");
        exit();
    }

// Récupération des données du formulaire
$date_livraison = trim($_POST["date_livraison"] ?? "");
$id_horaire_livraison = filter_input(INPUT_POST, "id_horaire_livraison", FILTER_VALIDATE_INT);
$livraison_adresse_complement = trim($_POST["livraison_adresse_complement"] ?? "");
$livraison_adresse = trim($_POST["livraison_adresse"] ?? "");
$livraison_code_postal = trim($_POST["livraison_code_postal"] ?? "");
$livraison_commune = trim($_POST["livraison_commune"] ?? "");
$nombre_personnes = filter_input(INPUT_POST, "nombre_personnes", FILTER_VALIDATE_INT);

$nombre_entree1 = filter_input(
    INPUT_POST,
    "nombre_entree1",
    FILTER_VALIDATE_INT
);

$nombre_entree2 = filter_input(
    INPUT_POST,
    "nombre_entree2",
    FILTER_VALIDATE_INT
);

$nombre_dessert1 = filter_input(
    INPUT_POST,
    "nombre_dessert1",
    FILTER_VALIDATE_INT
);

$nombre_dessert2 = filter_input(
    INPUT_POST,
    "nombre_dessert2",
    FILTER_VALIDATE_INT
);

// Recherche de l'identifiant de la commune et des frais de livraison correspondants.
$ville_recherchee = str_replace(
    "-",
    " ",
    $livraison_commune
);

$requete_commune = $pdo->prepare("
    SELECT
        id_commune,
        frais_livraison_euros
    FROM commune_gironde
    WHERE UPPER(
        REPLACE(
            REPLACE(
                REPLACE(commune_gironde, '-', ' '),
                'é',
                'e'
            ),
            'É',
            'E'
        )
    ) = ?
");

$requete_commune->execute([
    strtoupper($ville_recherchee)
]);

$commune = $requete_commune->fetch(PDO::FETCH_ASSOC);

if (!$commune) {
    exit(
        "Commune inconnue : ["
        . htmlspecialchars(
            $livraison_commune,
            ENT_QUOTES,
            "UTF-8"
        )
        . "]"
    );
}

$livraison_id_commune =
    (int) $commune["id_commune"];

$frais_livraison_euros =
    (float) $commune["frais_livraison_euros"];


// Récupération du prix et du nombre minimum de personnes du menu
$requete_menu = $pdo->prepare("
    SELECT
        menus.prix_par_personne_euros,
        menus.nb_personnes_minimum
    FROM commandes
    INNER JOIN menus
        ON commandes.nom_menu = menus.nom_menu
    WHERE commandes.id_commande = :id_commande
      AND commandes.id_utilisateur = :id_utilisateur
      AND commandes.id_statut_commande = 1
");

$requete_menu->execute([
    ":id_commande" => $id_commande,
    ":id_utilisateur" => $id_utilisateur
]);

$menu = $requete_menu->fetch(PDO::FETCH_ASSOC);

if (!$menu) {
    header(
        "Location: espace_utilisateur.php?"
        . "modification=impossible"
    );
    exit;
}

$prix_par_personne =
    (float) $menu["prix_par_personne_euros"];

$nombre_personnes_minimum =
    (int) $menu["nb_personnes_minimum"];

// Vérification du nombre minimum de personnes
if (
    !$nombre_personnes
    || $nombre_personnes < $nombre_personnes_minimum
) {
    exit(
        "Le nombre de personnes doit être au minimum de "
        . $nombre_personnes_minimum
        . "."
    );
}

// Calcul du prix et de la réduction
$seuil_reduction =
    $nombre_personnes_minimum + 5;

$taux_reduction = 0.10;

$prix_initial =
    $nombre_personnes * $prix_par_personne;

$reduction = 0;

if ($nombre_personnes >= $seuil_reduction) {
    $reduction =
        $prix_initial * $taux_reduction;
}

$prix_menu = round(
    $prix_initial - $reduction,
    2
);

$prix_total = round(
    $prix_menu + $frais_livraison_euros,
    2
);

try {
    // Modification de la commande
    $sql = "
        UPDATE commandes
        SET
            date_livraison = :date_livraison,
            id_horaire_livraison = :id_horaire_livraison,
            livraison_adresse_complement =
                :livraison_adresse_complement,
            livraison_adresse = :livraison_adresse,
            livraison_code_postal = :livraison_code_postal,
            id_commune = :id_commune,
            nombre_personnes = :nombre_personnes,
            prix_menu = :prix_menu,
            frais_livraison_euros = :frais_livraison_euros,
            prix_total = :prix_total,
            nombre_entree1 = :nombre_entree1,
            nombre_entree2 = :nombre_entree2,
            nombre_dessert1 = :nombre_dessert1,
            nombre_dessert2 = :nombre_dessert2
        WHERE id_commande = :id_commande
          AND id_utilisateur = :id_utilisateur
          AND id_statut_commande = 1
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ":date_livraison" => $date_livraison,
        ":id_horaire_livraison" => $id_horaire_livraison,
        ":livraison_adresse_complement" =>
            $livraison_adresse_complement,
        ":livraison_adresse" => $livraison_adresse,
        ":livraison_code_postal" => $livraison_code_postal,
        ":id_commune" => $livraison_id_commune,
        ":nombre_personnes" => $nombre_personnes,
        ":prix_menu" => $prix_menu,
        ":frais_livraison_euros" => $frais_livraison_euros,
        ":prix_total" => $prix_total,       
        ":nombre_entree1" => $nombre_entree1,     
        ":nombre_entree2" => $nombre_entree2,
        ":nombre_dessert1" => $nombre_dessert1,
        ":nombre_dessert2" => $nombre_dessert2,
        ":id_commande" => $id_commande,
        ":id_utilisateur" => $id_utilisateur
    ]);

    // La commande n'existe pas, n'appartient pas à l'utilisateur
    // ou n'est plus en attente d'acceptation
    if ($stmt->rowCount() === 0) {
        header(
            "Location: espace_utilisateur.php?"
            . "modification=impossible"
        );
        exit;
    }

    // Récupération du prénom et de l'email
    $sql_utilisateur = "
        SELECT prenom, email
        FROM utilisateurs
        WHERE id_utilisateur = :id_utilisateur
    ";

    $stmt_utilisateur = $pdo->prepare($sql_utilisateur);

    $stmt_utilisateur->execute([
        ":id_utilisateur" => $id_utilisateur
    ]);

    $utilisateur = $stmt_utilisateur->fetch(
        PDO::FETCH_ASSOC
    );

    if (!$utilisateur) {
        exit("Utilisateur introuvable.");
    }

    $prenom = $utilisateur["prenom"];
    $email = $utilisateur["email"];

    // Envoi du mail après la modification réussie
    require_once __DIR__
        . "/email_confirmation_modifications_commandes.php";

    header(
        "Location: espace_utilisateur.php?"
        . "modification=success"
    );
    exit;

} catch (PDOException $e) {
    error_log(
        "Erreur lors de la modification de la commande : "
        . $e->getMessage()
    );

    exit(
        "Une erreur est survenue pendant la modification de la commande."
    );
}




<?php
session_start();

require_once "connexion.php";

//Vérification du formulaire 
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo "Méthode non autorisée.";
    exit;
}

    $raison_sociale = trim($_POST["raison_sociale"] ?? '');
    $pseudo = trim($_POST["pseudo"] ?? '');
    $civilite = trim($_POST["civilite"] ?? '');
    $prenom = trim($_POST["prenom"] ?? '');
    $nom = trim($_POST["nom"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $mot_de_passe = $_POST["mot_de_passe"] ?? '';
    $mot_de_passe_confirme = $_POST["mot_de_passe_confirme"] ?? '';
    $telephone = trim($_POST["telephone"] ?? '');
    $livraison_adresse_complement = trim($_POST['livraison_adresse_complement'] ?? '');
    $livraison_adresse = trim($_POST['livraison_adresse'] ?? '');
    $livraison_code_postal = trim($_POST['livraison_code_postal'] ?? '');
    $livraison_ville = trim($_POST['livraison_ville'] ?? '');

// Recherche de l'id_commune à partir du nom de la ville
$requete = $pdo->prepare("
    SELECT id_commune
    FROM commune_gironde
    WHERE UPPER(
        REPLACE(
            REPLACE(
                REPLACE(commune_gironde, '-', ' '),
            'é', 'e'),
        'É', 'E')
    ) = ?
");

$requete->execute([$livraison_ville]);
$livraison_id_commune = $requete->fetchColumn();

if (!$livraison_id_commune) {
    echo "Ville reçue : [" . $livraison_ville . "]";
    exit;
}
    $facturation = $_POST['facturation'] ?? '';
    $adresse_facturation_identique = ($facturation === 'oui') ? 1 : 0;
    $facturation_adresse_complement = trim($_POST['facturation_adresse_complement'] ?? '');
    $facturation_adresse = trim($_POST['facturation_adresse'] ?? '');
    $facturation_code_postal = trim($_POST['facturation_code_postal'] ?? '');
    $facturation_ville = trim($_POST['facturation_ville'] ?? '');

if ($adresse_facturation_identique == 1) {
    $facturation_id_commune = $livraison_id_commune;

} else {
   $requete = $pdo->prepare("
    SELECT id_commune
    FROM commune_gironde
    WHERE UPPER(
        REPLACE(
            REPLACE(
                REPLACE(commune_gironde, '-', ' '),
            'é', 'e'),
        'É', 'E')
    ) = ?
");

    $requete->execute([$facturation_ville]);
    $facturation_id_commune = $requete->fetchColumn();

    if (!$facturation_id_commune) {
        exit("Cette commune de facturation n'existe pas dans la base de données.");
    }
}

    $date_inscription = date('Y-m-d H:i:s');
    
    if (empty($mot_de_passe)|| empty($mot_de_passe_confirme))
    {
        echo "Merci de remplir les deux champs de mot de passe.";
        exit;
    }  
        
    if (strlen($mot_de_passe) < 10) {
        echo "Le mot de passe doit contenir au moins 10 caractères.";
        exit;
    }   

    if ($mot_de_passe !== $mot_de_passe_confirme)
        {echo "mot de passe erronné car différent";
        exit;
    }

//Hash du mot de passe 
    $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

    if (
        empty($pseudo) || 
        empty($prenom) || 
        empty($nom) || 
        empty($email) || 
        empty($telephone) || 
        empty($livraison_adresse) || 
        empty($livraison_ville)) 
    {
        echo "Les champs avec * sont obligatoires.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Adresse email invalide.";
        exit;
    }

    if (!preg_match('/^[0-9+\s()-]{8,20}$/', $telephone)) {
        echo "Numéro de téléphone invalide.";
        exit;
    }

//Insertion en base de données
            $id_role = 3;

    $sql = "INSERT INTO utilisateurs (
            pseudo,
            raison_sociale,
            civilite, 
            nom, 
            prenom, 
            id_role,
            email, 
            mot_de_passe,
            telephone, 
            livraison_adresse_complement,
            livraison_adresse,
            livraison_code_postal,
            livraison_id_commune,
            adresse_facturation_identique,
            facturation_adresse_complement, 
            facturation_adresse,
            facturation_code_postal,
            facturation_id_commune,
            date_inscription
        )
             
            VALUES (
            :pseudo,
            :raison_sociale,
            :civilite,
            :nom, 
            :prenom, 
            :id_role,
            :email, 
            :mot_de_passe,
            :telephone,
            :livraison_adresse_complement,
            :livraison_adresse,
            :livraison_code_postal,
            :livraison_id_commune,
            :adresse_facturation_identique,
            :facturation_adresse_complement,
            :facturation_adresse,
            :facturation_code_postal,
            :facturation_id_commune,
            :date_inscription
        )";
            
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':pseudo' => $pseudo,
            ':raison_sociale' => $raison_sociale, 
            ':civilite' => $civilite,
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':id_role' =>$id_role,
            ':email' => $email,
            ':mot_de_passe' => $mot_de_passe_hash,
            ':telephone' => $telephone,
            ':livraison_adresse_complement' => $livraison_adresse_complement,
            ':livraison_adresse' => $livraison_adresse,
            ':livraison_code_postal' => $livraison_code_postal,
            ':livraison_id_commune' => $livraison_id_commune,
            ':adresse_facturation_identique' => $adresse_facturation_identique,
            ':facturation_adresse_complement' => $facturation_adresse_complement, 
            ':facturation_adresse' => $facturation_adresse,
            ':facturation_code_postal' => $facturation_code_postal,
            ':facturation_id_commune' => $facturation_id_commune,
            ':date_inscription' => $date_inscription
        ]);
        
// récupération de l'id utilisateur créé
        $id_utilisateur = $pdo->lastInsertId();

// stockage en session
        $_SESSION['id_utilisateur'] = $id_utilisateur;

//Envoi du mail de bienvenue
        $prenom_html = htmlspecialchars($prenom, ENT_QUOTES, 'UTF-8');
        $nom_html = htmlspecialchars($nom, ENT_QUOTES, 'UTF-8');

        $to = $email;
        $sujet = "Bienvenue sur notre site Vite et Gourmand";
        $message = "
        <html>
        <head>
        <title>Bienvenue sur notre site Vite et Gourmand</title>
        </head>
        <body>
        <h1>Bienvenue chez Vite et Gourmand $prenom_html $nom_html ! </h1>
        <p>Merci pour votre inscription.</p>
        <p>Nous sommes ravis de vous compter parmi nous et ferons tout pour vous satisfaire</p>
        <p>A très vite ! </p>
        <p>José TOC et l'équipe Vite et Gourmand</p>
        </body>
        </html>
        ";
    
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: Vite et Gourmand <contact@viteetgourmand.com>\r\n";

    mail($to, $sujet, $message, $headers);
    header("Location: espace_utilisateur.php");
    exit;

      } catch (PDOException $e) {

    // Enregistre l'erreur complète dans les logs
    error_log("Erreur PDO : " . $e->getMessage());

    // Gestion des doublons
    if ($e->getCode() == 23000) {

        if (str_contains($e->getMessage(), 'pseudo')) {
            echo "Ce pseudo est déjà utilisé.";
        }

        elseif (str_contains($e->getMessage(), 'email')) {
            echo "Cet email est déjà utilisé.";
        }

        else {
            echo "Une donnée existe déjà en base.";
        }

    } else {

        echo "Une erreur est survenue lors de l'inscription.";
    }
}
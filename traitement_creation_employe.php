<?php
session_start();

require_once "connexion.php";

//Vérification du formulaire 


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Méthode non autorisée.");
}

$id_role = 2;

    $civilite = trim($_POST["civilite"] ?? '');
    $prenom = trim($_POST["prenom"] ?? '');
    $nom = trim($_POST["nom"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $mot_de_passe = $_POST["mot_de_passe"] ?? '';
    $mot_de_passe_confirme = $_POST["mot_de_passe_confirme"] ?? '';
    $telephone = trim($_POST["telephone"] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');
    $code_postal = trim($_POST['code_postal'] ?? '');
    $ville = trim($_POST['ville'] ?? '');

// Recherche de l'id_commune à partir du nom de la ville
$requete = $pdo->prepare("
    SELECT id_commune
    FROM commune_gironde
    WHERE commune_gironde = ?
");

$requete->execute([$ville]);

$id_commune = $requete->fetchColumn();

if (!$id_commune) {
    exit("Cette commune n'existe pas dans la base de données.");
}

    $date_embauche = trim($_POST['date_embauche'] ?? '');
    $type_contrat = trim($_POST['type_contrat'] ?? '');
    $fonction = trim($_POST['fonction'] ?? '');
    $date_prise_fonction = trim($_POST['date_prise_fonction'] ?? '');
    $date_fin_contrat = !empty($_POST['date_fin_contrat']) ? $_POST['date_fin_contrat'] : null;
    $date_creation_compte = date('Y-m-d H:i:s');
    
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

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Adresse email invalide.";
        exit;
    }

    if (!preg_match('/^[0-9+\s()-]{8,20}$/', $telephone)) {
        echo "Numéro de téléphone invalide.";
        exit;
    }

//Insertion en base de données
           
    $sql = "INSERT INTO salaries (
            civilite, 
            prenom, 
            nom,
            id_role,
            email, 
            mot_de_passe,
            telephone, 
            adresse,
            code_postal,
            id_commune,
            date_embauche,
            type_contrat, 
            fonction, 
            date_prise_fonction,
            date_fin_contrat,
            date_creation_compte
        )
             
            VALUES (
            :civilite,
            :prenom, 
            :nom, 
            :id_role, 
            :email, 
            :mot_de_passe,
            :telephone,
            :adresse,
            :code_postal,
            :id_commune,
            :date_embauche,
            :type_contrat,
            :fonction,
            :date_prise_fonction, 
            :date_fin_contrat,
            :date_creation_compte
        )";
            
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':civilite' => $civilite,
            ':prenom' => $prenom,
            ':nom' => $nom,
            ':id_role' => $id_role,
            ':email' => $email,
            ':mot_de_passe' => $mot_de_passe_hash,
            ':telephone' => $telephone,
            ':adresse' => $adresse,
            ':code_postal' => $code_postal,
            ':id_commune' => $id_commune,
            ':date_embauche' => $date_embauche,
            ':type_contrat' => $type_contrat,
            ':fonction' => $fonction,
            ':date_prise_fonction' => $date_prise_fonction,
            ':date_fin_contrat' => $date_fin_contrat,
            ':date_creation_compte' => $date_creation_compte
        ]);
    
    // récupération de l'id employé créé    
    $id_salarie = $pdo->lastInsertId();
   
//Envoi du mail de bienvenue
        $prenom_html = htmlspecialchars($prenom, ENT_QUOTES, 'UTF-8');
        $nom_html = htmlspecialchars($nom, ENT_QUOTES, 'UTF-8');

        $to = $email;
        $sujet = "Bienvenue dans notre entreprise Vite et Gourmand";
        $message = "
        <html>
        <head>
        <title>Bienvenue dans notre entreprise Vite et Gourmand</title>
        </head>
        <body>
        <h1>Bienvenue chez Vite et Gourmand $prenom_html $nom_html ! </h1>
        <p>Je suis heureux de vous accueillir chez Vite et Gourmand.</p>
        <p>En tant qu'administrateur du site, et pour que vous puissiez commencer votre mission dans de bonnes conditions, je vous ai créé un compte Employé.</p>
        <p>Votre identifiant sera votre boite mail (prenom.nom@viteetgourmand.com) et je vous communiquerai votre mot de passe verbalement lors de notre prochaine rencontre. </p>
        <p>Bienvenue !  </p>
        <p>José TOC </p>
        <p>Gérant - Administrateur de Vite et Gourmand</p>
        </body>
        </html>
        ";
    
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: José Toc <jose.toc@viteetgourmand.com>\r\n";

    if (mail($to, $sujet, $message, $headers)) {
    $_SESSION['message_succes'] = "Compte employé créé avec succès. Un email de bienvenue a été envoyé.";
} else {
    $_SESSION['message_succes'] = "Compte employé créé avec succès, mais l'email de bienvenue n'a pas pu être envoyé.";
}

header("Location: espace_administrateur.php");
exit;

} catch (PDOException $e) {
    error_log("Erreur PDO : " . $e->getMessage());
    echo "Une erreur est survenue lors de la création du compte employé.";
}
?>

<?php
session_start();

require_once __DIR__ . "/connexion.php";

if (!isset($_SESSION["id_utilisateur"])) {
    header("Location: formulaire_connexion_utilisateur.php");
    exit;
}

$id_utilisateur = (int) $_SESSION["id_utilisateur"];

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
    $telephone = trim($_POST["telephone"] ?? '');
    $livraison_adresse_complement = trim($_POST['livraison_adresse_complement'] ?? '');
    $livraison_adresse = trim($_POST['livraison_adresse'] ?? '');
    $livraison_code_postal = trim($_POST['livraison_code_postal'] ?? '');
    $livraison_ville = trim($_POST['livraison_ville'] ?? '');

// Recherche de l'id_commune à partir du nom de la ville
$ville_recherchee = str_replace("-", " ", $livraison_ville);

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

$requete->execute([strtoupper($ville_recherchee)]);
$livraison_id_commune = $requete->fetchColumn();

if (!$livraison_id_commune) {
    echo "Ville reçue : [" . $livraison_ville . "]";
    exit;
}
    
    //Modifications en base de données
           
    $sql = "UPDATE  utilisateurs 
        SET
            raison_sociale = :raison_sociale,
            pseudo = :pseudo,
            civilite = :civilite,
            nom = :nom, 
            prenom = :prenom, 
            email = :email, 
            telephone = :telephone,
            livraison_adresse_complement = :livraison_adresse_complement,
            livraison_adresse = :livraison_adresse,
            livraison_code_postal = :livraison_code_postal,
            livraison_id_commune = :livraison_id_commune
        WHERE id_utilisateur = :id_utilisateur";
          
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':pseudo' => $pseudo,
            ':raison_sociale' => $raison_sociale, 
            ':civilite' => $civilite,
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':telephone' => $telephone,
            ':livraison_adresse_complement' => $livraison_adresse_complement,
            ':livraison_adresse' => $livraison_adresse,
            ':livraison_code_postal' => $livraison_code_postal,
            ':livraison_id_commune' => $livraison_id_commune,
            ':id_utilisateur' => $id_utilisateur
        ]);
        
// Envoi du mail de confirmation
        require_once __DIR__ . '/email_confirmation_modifications_donnees.php';
        
        header("Location: espace_utilisateur.php?modification=success");
exit;

} catch (PDOException $e) {
    error_log("Erreur PDO : " . $e->getMessage());

    if ((int) $e->getCode() === 23000) {
        echo "Ce pseudo ou cette adresse e-mail est déjà utilisé.";
    } else {
        echo "Une erreur est survenue pendant la modification.";
    }

    exit;
}
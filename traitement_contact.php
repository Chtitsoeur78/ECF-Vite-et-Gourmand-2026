<?php
require_once "connexion.php";

//Vérification du formulaire 
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo "Méthode non autorisée.";
    exit;
}
    $pseudo = trim($_POST["pseudo"] ?? '');
    $prenom = trim($_POST["prenom"] ?? '');
    $nom = trim($_POST["nom"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $sujet = trim($_POST["message_sujet"] ?? '');
    $titre = trim($_POST["message_titre"] ?? '');
    $message = trim($_POST["message"] ??  '');
    $date_envoi = date('Y-m-d H:i:s');
    
   if (
        empty($prenom) || 
        empty($nom) || 
        empty($email) || 
        empty($sujet) || 
        empty($titre) || 
        empty($message)) 
    {
        echo "Les champs avec * sont obligatoires.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Adresse email invalide.";
        exit;
    }

 //Insertion en base de données
    $sql = "INSERT INTO contacts (
            pseudo,
            prenom,
            nom, 
            email, 
            message_sujet, 
            message_titre,
            message, 
            date_envoi
        )
             
            VALUES (
            :pseudo,
            :prenom,
            :nom, 
            :email, 
            :message_sujet,
            :message_titre,
            :message,
            :date_envoi
        )";
            
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':pseudo' => $pseudo,
            ':prenom' => $prenom,
            ':nom' => $nom,
            ':email' => $email,
            ':message_sujet' => $sujet,
            ':message_titre' => $titre,
            ':message' => $message,
            ':date_envoi' => $date_envoi
        ]);

// Envoi du mail de contact
        require_once __DIR__ . '/email_contact.php';
        
        header("Location: index.php?envoi=success");
exit;

    } catch (PDOException $e) {
    echo "Erreur PDO : " . htmlspecialchars($e->getMessage());
}


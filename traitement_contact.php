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
    $nom = trim($_POST["nom_famille"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $sujet = trim($_POST["sujet"] ?? '');
    $titre = trim($_POST["titre"] ?? '');
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
    $sql = "INSERT INTO messages_contact (
            pseudo,
            prenom,
            nom, 
            email, 
            sujet_message, 
            titre_message,
            message, 
            date_envoi
        )
             
            VALUES (
            :pseudo,
            :prenom,
            :nom, 
            :email, 
            :sujet_message,
            :titre_message,
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
            ':sujet_message' => $sujet,
            ':titre_message' => $titre,
            ':message' => $message,
            ':date_envoi' => $date_envoi
        ]);

        echo "Message envoyé avec succès.";

    } catch (PDOException $e) {
        error_log("Erreur PDO : " . $e->getMessage());
 
        echo "Une erreur est survenue lors de l'envoi du message.";
}




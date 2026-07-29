<?php
session_start();

require_once "connexion.php";

//Vérification du formulaire 
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo "Méthode non autorisée.";
    exit;
}

    $email = trim($_POST["email"] ?? '');
    $mot_de_passe = $_POST["mot_de_passe"] ?? '';
    $mot_de_passe_confirme = $_POST["mot_de_passe_confirme"] ?? '';
        
    if (empty($mot_de_passe)|| empty($mot_de_passe_confirme))
    {
        echo "Merci de remplir les deux champs de mot de passe.";
        exit;
    }  
        
    if (strlen($mot_de_passe) < 10) {
        echo "Le mot de passe doit contenir au moins 10 caractères.";
        exit;
    }   

    if ($mot_de_passe !== $mot_de_passe_confirme){
        echo "mot de passe erronné car différent";
        exit;
    }

//Hash du mot de passe 
    $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

    if (
        empty($email))
    {   echo "Le champs Email est obligatoire.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Adresse email invalide.";
        exit;
    }
   
//Modification en base de données
try {         
    $sql = "UPDATE employes
            SET mot_de_passe = :mot_de_passe
            WHERE email = :email";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':mot_de_passe' => $mot_de_passe_hash,
        ':email' => $email,
    ]);

if ($stmt->rowCount() === 0) {
    echo "Aucune modification effectuée.";
    exit;
}

} catch (PDOException $e) {
    error_log("Erreur PDO : " . $e->getMessage());
    echo "Erreur SQL : " . $e->getMessage();
}

//Envoi du mail de réinitialisation
        $to = $email;
        $sujet = "Réinitialisation de votre mot de passe";
        $message = "
        <html>
        <head>
        <title>Nouveau mot de passe </title>
        </head>
        <body>
        <h1>Nous vous confirmons que votre mot de passe Vite et Gourmand a bien été modifié.</h1>
        <p>A très vite ! </p>
        <p>José TOC et l'équipe Vite et Gourmand</p>
        </body>
        </html>
        ";
    
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: Vite et Gourmand <contact@viteetgourmand.com>\r\n";

    if (mail($to, $sujet, $message, $headers)) {
    echo "Mot de passe modifié ! Un email de confirmation vous a été envoyé.";

    } else {
    echo "Mot de passe modifié, mais l'email n'a pas pu être envoyé.";
    }

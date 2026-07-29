<?php
session_start();
require_once "connexion.php";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"] ?? "";
    $mot_de_passe = $_POST["mot_de_passe"] ?? "";
$requete = $pdo ->prepare("
                            SELECT * 
                            FROM utilisateurs 
                            WHERE email = ?");
$requete->execute([$email]);
$utilisateur = $requete->fetch(); 

 if ($utilisateur && password_verify($mot_de_passe, $utilisateur["mot_de_passe"])) {
        $_SESSION["id_utilisateur"] = $utilisateur["id_utilisateur"];
        $_SESSION["email"] = $utilisateur["email"];
        $_SESSION["prenom"] = $utilisateur["prenom"];

        header("Location: espace_utilisateur.php");
        exit;
    } else {
        echo "Email ou mot de passe incorrect.";
    }
}
?>














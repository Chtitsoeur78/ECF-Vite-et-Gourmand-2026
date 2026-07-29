<?php
session_start();
require_once "connexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"] ?? "";
    $mot_de_passe = $_POST["mot_de_passe"] ?? "";
$requete = $pdo ->prepare("
                            SELECT * 
                            FROM salaries 
                            WHERE email = ?");
$requete->execute([$email]);
$employe = $requete->fetch(PDO::FETCH_ASSOC);

    if ($employe && password_verify($mot_de_passe, $employe['mot_de_passe'])) {

        $_SESSION['id_employe'] = $employe['id_salarie'];
        $_SESSION['id_role'] = $employe['id_role'];

if ($employe['id_role'] == 1) {
    header("Location: espace_administrateur.php");
    exit;
   }
  
if ($employe['id_role'] == 2) {
    header("Location: espace_employe.php");
    exit;
   }
    }

    echo "Email ou mot de passe incorrect.";
}
?>














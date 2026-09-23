
<?php

session_start();

// Supprime toutes les données de session
session_unset();

// Détruit la session
session_destroy();

// Redirection vers l'accueil
header("Location: index.php");
exit();

?>














<?php
session_start();

$est_en_local = ($_SERVER["SERVER_NAME"] === "localhost");

$fichier_traitement = $est_en_local
    ? "traitement_motdepasse_oublie_utilisateur.php"
    : "traitement_motdepasse_oublie_utilisateur2.php";

if (isset($_SESSION['id_utilisateur'])) {
    header('Location: espace_utilisateur.php');
    exit();
}
?>
<!doctype html>
<html lang="fr">
  <head>
    <!-- balises meta indispensables -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- liaison avec la page externe de css -->
    <link rel="stylesheet" href="css/styles.css"/>
    <link rel="stylesheet" href="css/menu_burger.css"/>
    <link rel="stylesheet" href="css/motdepasse_oublie.css"/>
    <title>
      Vite & Gourmand - Formulaire de réinitialisation du mot de passe</title>
  </head>
  <!-- body : Contenu de la page -->
  <body>
  <header class="header_conteneur">
      <section>
          <?php include "menu_burger.php"; ?>
      </section>
      <section>    
          <h1>Réinitialiser son mot de passe</h1>
      </section> 
      <section class="logo">
        <img src="images/logo1.png" alt="Logo de Vite et Gourmand, la plate-forme pour manger vite et bien"/>
      </section>
    </header> 
    
    <main>
        <form action="<?= htmlspecialchars($fichier_traitement, ENT_QUOTES, 'UTF-8') ?>" method="post">
        <fieldset>
            <legend class="legend">Demande de réinitialisation du mot de passe</legend>
          <p>
            <label for="email">E-MAIL * : </label>
            <input type="email" required id="email" name="email" placeholder="xxm@yy.zz" autocomplete="email">
          </p>
           <p>
            <button type="submit" name="OK" value="Envoyer"> Envoyer le lien de réinitialisation</button>    
        </p>
          </fieldset>
        </form>
        </main>
<!-- liaison avec la page externe de Javascript -->
<script src="javascript/menu_burger.js"></script> 
</body>
</html>
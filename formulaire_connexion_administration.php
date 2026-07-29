<?php
session_start();

if (isset($_SESSION['id_role'])) {
    if ($_SESSION['id_role'] == 1) {
        header('Location: espace_administrateur.php');
        exit();
    } elseif ($_SESSION['id_role'] == 2) {
        header('Location: espace_employe.php');
        exit();
    }
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
    <link rel="stylesheet" href="css/formulaire_connexion.css"/>
    <title>
      Vite & Gourmand - Formulaire de connexion - Administration </title>
  </head>
  <!-- body : Contenu de la page -->
  <body>
  <header class="header_conteneur">
      <section>
          <?php include "menu_burger.php"; ?>
      </section>
      <section>    
          <h1>Connexion - Administration</h1>
      </section> 
      <section class="logo">
        <img src="images/logo1.png" alt="Logo de Vite et Gourmand, la plate-forme pour manger vite et bien"/>
      </section>
    </header> 
    <main>
        <form action="traitement_connexion_administration.php" method="post">
        <fieldset>
            <legend class="legend">Direction votre Espace - Administration</legend>
        <p>
            <label for="email">E-MAIL * : </label>
            <input type="email" required id="email" name="email" placeholder="xx@yy.zz">
        </p>
        <p>
            <label for="mot_de_passe">MOT DE PASSE * :</label>
            <input type="password" required id="mot_de_passe" name="mot_de_passe" minlength="10" maxlength="80" placeholder="xxxxxxxxxxxxx">
        </p>
        <p>
            <button type="submit" name="OK" value="Envoyer"> Connectez vous</button>    
        </p>
       <p>
            <button type="button" onclick="window.location.href='motdepasse_oublie_administration.php'"> Mot de passe oublié ? </button>
        </p>
       <fieldset>
        </form>
        </main>
<!-- liaison avec la page externe de Javascript -->
<script src="javascript/menu_burger.js"></script> 
</body>
</html>
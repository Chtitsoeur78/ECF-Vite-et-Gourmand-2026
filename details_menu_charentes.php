<?php
session_start();
?>
<!doctype html>
<html lang="fr">
  <head>
    <!-- balises meta indispensables -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta
      name="description"
      content="Vite et Gourmand est une plate-forme de commande de repas utilisable dans la région de Bordeaux."
    />
    <!-- liaison avec la feuille de style externe de CSS -->
    <link rel="stylesheet" href="css/styles.css"/>
    <link rel="stylesheet" href="css/menu_burger.css"/>
    <link rel="stylesheet" href="css/details_menus.css"/>
    <link rel="stylesheet" href="css/footer.css"/>
    
         <!-- Titre de la Page -->
    <title>Vite et Gourmand - Détails du menu charentais</title>
  </head>
  <body>
   <!-- header : l'en-tête de la page d'accueil --> 
    <header class="header_conteneur">
        <section>
            <?php include "menu_burger.php"; ?>
        </section>
        <section class="centre">
            <h1>Détails du menu Sud-Ouest</h1>
        </section>
        <section class="logo"> 
            <img src="images/logo1.png" alt="Logo de Vite et Gourmand, la plate-forme pour manger vite et bien" >
        </section>
    </header>    
    <main>
        <section class="details_menus">
              <h2>Ce mois-ci :  Menu charentais  </h2>
              <h3>Galerie photos</h3>
    <p>Cliquez sur chaque photo pour lire son commentaire.</p>
    <div class="galerie">
    <figure>
        <img src="images/charentes/menu_charentais.png" alt="Présentation du menu charentais de Vite & Gourmand comprenant au choix des huîtres de Marennes-Oléron ou des grattons charentais en entrée, une mouclade charentaise en plat principal, puis une galette charentaise ou un millas charentais en dessert">
        <figcaption>Découvrez les spécialités des Charentes réunies dans un menu complet proposé par Vite & Gourmand : Régalez-vous avec les Charentes dans votre assiette.</figcaption>
    </figure>
    <figure>
        <img src="images/charentes/huitres_marennes_oleron.png" alt="Plateau d'une douzaine d'huîtres de Marennes-Oléron présenté avec des quartiers de citron">
        <figcaption>Une douzaine d'huîtres de Marennes-Oléron : les huîtres charentaises par excellence ! </figcaption>
    </figure>
    <figure>
        <img src="images/charentes/gratton_charentais.png" alt="Assiette de grattons charentais présentés pour la dégustation">
        <figcaption>Les grattons charentais, une spécialité régionale à base de porc proposée en entrée.</figcaption>
    </figure>
    <figure>
        <img src="images/charentes/mouclade.png" alt="La Mouclade charentaise, préparée avec des moules dans une sauce crémeuse safranée">
        <figcaption>Pour savourer les moules des côtes charentaises, laissez-vous tenter par la mouclade, une recette traditionnelle.</figcaption>
    </figure>
    <figure>
        <img src="images/charentes/galette_charentaise.png" alt="Une galette charentaise avec une part découpée">
        <figcaption>Pour terminer votre voyage gourmand dans les Charentes, vous vous laisserez tenter par la galette charentaise, un dessert traditionnel de la région.</figcaption>
    </figure>
    <figure>
        <img src="images/charentes/millas_charentais.png" alt="Part de millas charentais découpée dans un gâteau rond">
        <figcaption>Une autre découverte pour terminer le repas ? Le millas charentais, gâteau à base de farine de maïs à la texture fondante.  </figcaption>
    </figure>
    </div> 
          <p>Thème : Repas régional du Sud - Ouest</p>
          <h3>Liste des plats du menu : </h3>
              <h4>Entrées </h4>
                <ul>
                    <li>Huîtres de Marennes - Oléron</li>
                    <li>Grattons charentais</li>
                </ul>
              <h4>Plat du Jour</h4>
                <ul>
                    <li>Mouclade charentaise</li>
                </ul>
              <h4>Desserts</h4>
                <ul>
                    <li>Galette charentaise</li>
                    <li>Millas charentais</li>
                </ul>
          <p>Nombre minimal de personnes : 4</p>
          <p>Prix pour le nombre minimal : 200 euros</p>
          <p>Les allergènes :</p>
                <ul>
                    <li>mollusques : Huîtres de Marenne-Oléron, Mouclade (moules)</li>
                    <li>gluten (farine de blé) : Galette charentaise, millas charentais</li>
                    <li>oeufs : Galette charentaise, millas charentaisGalette charentaise, millas charentais</li></li>
                    <li>lait : Galette charentaise, millas charentais</li>
                </ul>
            <p>Malgré toute notre vigilance, des traces d'autres allergènes peuvent être présentes en raison des conditions de préparation. Pour toute allergie alimentaire, n'hésitez pas à nous contacter avant de passer votre commande.</p>
            <p>Conditions particulières : Le faitout dans lequel est livrée la mouclade doit être rendu</p>
            <p>Régime : Classique</p>
            <p>Stock disponible : dans la mesure du possible, les plats sont cuisinés à la demande</p>        
              <?php if (isset($_SESSION['id_utilisateur'])): ?>
              <a href="formulaire_commande.php?menu=menu_charentes" class="bouton_commande">COMMANDER</a>
              <?php else: ?>
              <a href="formulaire_connexion_utilisateur.php" class="bouton_commande">SE CONNECTER POUR COMMANDER</a>
              <?php endif; ?>
        </section>
      </main>
       <?php include "footer.php"; ?>  
    <!-- liaison avec la page externe de Javascript -->
    <script src="javascript/menu_burger.js"></script> 
    <script src="javascript/galerie.photos.js"></script> 
  </body>
</html>

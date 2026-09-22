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
    <link rel="stylesheet" href="css/menu_burger.css?v=5"/>
    <link rel="stylesheet" href="css/details_menus.css?v=2"/>
    <link rel="stylesheet" href="css/footer.css"/>
            <!-- Titre de la Page -->
    <title>Vite et Gourmand - Détails des menus classiques</title>
  </head>
  <body>
    <!-- header : l'en-tête de la page d'accueil --> 
    <header class="header_conteneur">
        <section>
                <?php include "menu_burger.php"; ?>
        </section>
        <section class="centre">
            <h1>Détails des menus classiques</h1>
        </section>
        <section class="logo"> 
            <img src="images/logo1.png" alt="Logo de Vite et Gourmand, la plate-forme pour manger vite et bien" >
        </section>
    </header>    
    <main>
        <section class="details_menus">
                  <h2> le menu "Le Jardin de José" </h2>
            <h3>Galerie - photos</h3>
    <p>Cliquez sur chaque photo pour lire son commentaire.</p>
    <div class="galerie">
    <figure>
        <img src="images/jardin_de_jose/menu_jardin_de_josé.png" alt="Présentation du menu Le Jardin de José de Vite & Gourmand comprenant une salade de fenouil à la menthe et à l'orange ou un gaspacho en entrée, des poivrons farcis au quinoa en plat principal, puis une tarte au citron végane ou des muffins aux fruits végans en dessert">
        <figcaption>Entrez dans Le Jardin de José, un menu entièrement végan proposé par Vite & Gourmand : des recettes fraîches, colorées et riches en saveurs.</figcaption>
    </figure>
    <figure>
        <img src="images/jardin_de_jose/salade_fenouil_mentthe_orange.png" alt="Salade composée de fenouil émincé, de morceaux d'orange et de feuilles de menthe">
        <figcaption>Eveillez vos papilles avec une salade fraîche et parfumée qui marie le fenouil, l’orange et la menthe.</figcaption>
    </figure>
    <figure>
        <img src="images/jardin_de_jose/gaspacho.png" alt="Gaspacho rouge servi dans un bol et accompagné de légumes frais">
        <figcaption>Pour une entrée pleine de fraîcheur, savourez un gaspacho coloré aux délicieuses saveurs estivales</figcaption>
    </figure>
    <figure>
        <img src="images/jardin_de_jose/poivrons_farcis_au_quinoa.png" alt="Poivrons colorés garnis de quinoa et présentés dans une assiette">
        <figcaption>Laissez-vous tenter par des poivrons généreusement farcis au quinoa, un plat végan aussi coloré que savoureux.</figcaption>
    </figure>
    <figure>
        <img src="images/jardin_de_jose//tarte_citron_végane.png" alt="Tarte au citron végane présentée entière avec une part découpée" >
        <figcaption>Terminez votre repas avec la fraîcheur acidulée d’une tarte au citron entièrement végane.</figcaption>
    </figure>
    <figure>
        <img src="images/jardin_de_jose/muffins_aux_fruits_vegans.png" alt="Muffins végans aux fruits disposés dans une assiette" >
        <figcaption>Vous préférez une douceur fruitée ? Découvrez nos muffins végans, moelleux et généreusement garnis de fruits</figcaption>
    </figure>
    </div>
        <p>Thème : Repas classique</p>
                <h3>Liste des plats du menu : </h3>
                    <h4>Entrées </h4>
                    <ul>
                        <li>Salade de fenouil, menthe et orange</li>
                        <li>Gaspacho</li>
                    </ul>
                    <h4>Plat du Jour</h4>
                    <ul>
                        <li>Poivrons farcis au Quinoa</li>
                    </ul>
                    <h4>Desserts (mode végane)</h4>
                    <ul>
                        <li>Tarte au citron</li>
                        <li>Muffins aux fruits</li>
                    </ul>  
              <p>Nombre minimal de personnes : 4</p>
              <p>Prix pour le nombre minimal : 180 euros</p>
              <p>Les allergènes :  </p>
                <ul>
                    <li>Gluten (farine de blé) : Tarte au citron, muffins aux fruits </li>
                    <li>Soja : Poivrons farcis au quinoa (sauce fromagère), muffins aux fruits</li>
                    <li>Fruits à coque : Poivrons farcis au quinoa (noix de cajou), tarte au citron (noisettes et pistaches)</li>
                    <li>Moutarde : Poivrons farcis au quinoa</li>  
                </ul>
              <p>Malgré toute notre vigilance, des traces d'autres allergènes peuvent être présentes en raison des conditions de préparation. Pour toute allergie alimentaire, n'hésitez pas à nous contacter avant de passer votre commande.</p>
              <p>Conditions particulières : Aucune </p>
              <p>Régime : Vegan</p>
              <p>Stock disponible : dans la mesure du possible, les plats sont cuisinés à la demande</p>
          <?php if (isset($_SESSION['id_utilisateur'])): ?>
              <a href="formulaire_commande.php?menu=menu_jardin_de_jose" class="bouton_commande">COMMANDER</a>
          <?php else: ?>
              <a href="formulaire_connexion_utilisateur.php" class="bouton_commande">SE CONNECTER POUR COMMANDER</a>
          <?php endif; ?> 
        </section>
    </main>
     <?php include "footer_avec.php"; ?>
    <!-- liaison avec la page externe de Javascript -->
    <script src="javascript/menu_burger.js"></script> 
  </body>
</html>

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
    <link rel="stylesheet" href="css/details_menus.css"/>
    <link rel="stylesheet" href="css/footer.css"/>
    <!-- Titre de la Page -->
    <title>Vite et Gourmand - Détails - Menus classiques</title>
  </head>
  <body>
    <!-- header : l'en-tête de la page d'accueil --> 
    <header class="header_conteneur">
        <section>
                <?php include "menu_burger.php"; ?>
        </section>
        <section class="centre">
            <h1>Détails Menus classiques</h1>
        </section>
        <section class="logo"> 
            <img src="images/logo1.png" alt="Logo de Vite et Gourmand, la plate-forme pour manger vite et bien" >
        </section>
    </header>    
    <main>
        <section class="details_menus">
              <h2> le menu "Délices" </h2>
              <h3>Galerie - photos</h3>
    <p>Cliquez sur chaque photo pour lire son commentaire.</p>
    <div class="galerie">
    <figure>
        <img src="images/delices/menu_delices.png" alt="Présentation du menu Délices, comprenant une salade niçoise ou une quiche lorraine en entrée, un bœuf bourguignon accompagné de légumes en plat principal, puis une île flottante ou un clafoutis aux cerises en dessert">
        <figcaption>Découvrez les grands classiques de la cuisine française réunis dans un menu complet : laissez-vous séduire par un repas généreux et gourmand.</figcaption>
    </figure>
    <figure>
        <img src="images/delices/salade_nicoise.png" alt="Salade niçoise composée de tomates, de thon, d'œufs, d'olives et de basilic, assaisonnée d'huile d'olive et de vinaigre">
        <figcaption>Pour commencer avec fraîcheur, découvrez notre autenthique salade niçoise colorée et généreusement garnie.</figcaption>
    </figure>
    <figure>
        <img src="images/delices/quiche_lorraine.png" alt="Quiche lorraine dorée garnie de lardons et présentée dans un plat rond">
        <figcaption>Vous préférez une entrée chaleureuse ? Laissez-vous tenter par la quiche lorraine, un grand classique de la cuisine française.</figcaption>
    </figure>
    <figure>
        <img src="images/delices/boeuf_bourguignon.png" alt="Bœuf bourguignon mijoté accompagné de carottes, de pommes de terre et de petits oignons"> 
        <figcaption>Poursuivez votre repas avec un bœuf bourguignon longuement mijoté et accompagné de ses légumes</figcaption>
    </figure>
    <figure>
        <img src="images/delices/Ile_flottante.png" alt="Île flottante généreuse servie sur une crème anglaise et nappée de caramel">
        <figcaption>Pour terminer le repas avec légèreté, savourez une île flottante accompagnée de sa crème anglaise et de son caramel</figcaption>
    </figure>
    <figure>
        <img src="images/delices/clafoutis_aux_cerises.png" alt="Clafoutis entier aux cerises présenté dans une assiette avec une part découpée">
        <figcaption>Une autre douceur pour terminer votre repas ? Découvrez le clafoutis aux cerises, un dessert fruité et fondant.</figcaption>
    </figure>
    </div>
        <p>Thème : Repas classique</p>
              <h3>Liste des plats du menu : </h3>
              <h4>Entrées </h4>
                <ul>
                <li>Salade niçoise</li>
                <li>Quiche lorraine</li>
                </ul>
              <h4>Plat du Jour</h4>
                <ul>
                <li>Boeuf bourguignon</li>
                </ul>
              <h4>Desserts</h4>
                <ul>
                <li>Ile flottante</li>
                <li>Clafoutis aux cerises</li>
                </ul>
        <p>Nombre minimal de personnes : 4</p>
        <p>Prix pour le nombre minimal : 160 euros</p>
        <p>Les allergènes :</p>
                <ul>
                    <li>Gluten (farine de blé) : Clafoutis aux cerises</li>
                    <li>Poisson : Salade niçoise (anchois)</li>
                    <li>Lait : Quiche lorraine, île flottante, clafoutis aux cerises</li>
                    <li>Oeufs : Quiche lorraine, île flottante, clafoutis aux cerises</li>
                </ul> 
                    <p>Malgré toute notre vigilance, des traces d'autres allergènes peuvent être présentes en raison des conditions de préparation. Pour toute allergie alimentaire, n'hésitez pas à nous contacter avant de passer votre commande.</p>   
        <p>Conditions particulières : Les ramequins dans lesquels sont livrées les îles flottantes doivent être rendus.</p>
        <p>Régime : Classique</p>
        <p>Stock disponible : dans la mesure du possible, les plats sont cuisinés à la demande</p>
          <?php if (isset($_SESSION['id_utilisateur'])): ?>
              <a href="formulaire_commande.php?menu=menu_delices" class="bouton_commande">COMMANDER</a>
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

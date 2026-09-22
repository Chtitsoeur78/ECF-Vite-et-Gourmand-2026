<?php
session_start();
?>

<!doctype html>
<html lang="fr">
  <head>
    <!-- balises meta indispensables -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description"content="Vite et Gourmand est une plate-forme de commande de repas utilisable dans la région de Bordeaux."
    />
    <!-- liaison avec la feuille de style externe de CSS -->
    <link rel="stylesheet" href="css/styles.css"/>
    <link rel="stylesheet" href="css/menu_burger.css?v=5"/>
    <link rel="stylesheet" href="css/details_menus.css?v=3"/>
    <link rel="stylesheet" href="css/footer.css"/>
             <!-- Titre de la Page -->
    <title>Vite et Gourmand - Détails des menus Evenement : Menu Fiesta</title>
  </head>
  <body>
    <!-- header : l'en-tête de la page d'accueil --> 
    <header class="header_conteneur">
        <section>
                <?php include "menu_burger.php"; ?>
        </section>
        <section class="centre">
            <h1>Détails des menus "Evénement"</h1>
        </section>
        <section class="logo"> 
            <img src="images/logo1.png" alt="Logo de Vite et Gourmand, la plate-forme pour manger vite et bien" >
        </section>
    </header>    
    <main>
      <section class="details_menus">
        <h2> le menu "Fiesta" </h2>
            <h3>Galerie - photos</h3>
    <p>Cliquez sur chaque photo pour lire son commentaire.</p>
    <div class="galerie">
    <figure>
        <img src="images/fiesta/menu_fiesta.png" alt="Présentation du menu Fiesta de Vite & Gourmand comprenant des toasts au chèvre et à la poire ou des roulés au jambon, au fromage et aux herbes en entrée, du saumon au miel en plat principal, puis du riz soufflé aux bonbons chocolatés ou un dôme au chocolat et au praliné en dessert">
        <figcaption>Place à la fête avec le menu Fiesta de Vite & Gourmand : des recettes conviviales, colorées et gourmandes à partager pour toutes vos grandes occasions.</figcaption>
    </figure>
    <figure>
        <img src="images/fiesta/toasts_fromage_chevre_poires.png" alt="Toasts garnis de fromage de chèvre et de morceaux de poire disposés sur une assiette">
        <figcaption>Commencez la fête avec des toasts croustillants associant le caractère du chèvre à la douceur de la poire</figcaption>
    </figure>
    <figure>
        <img src="images/fiesta/roules_jambon_fromage_herbes.png" alt="Roulés garnis de jambon, de fromage et d'herbes présentés sur un plat">
        <figcaption>Pour une entrée conviviale, dégustez nos roulés au jambon et au fromage délicatement parfumés aux herbes.</figcaption>
    </figure>
    <figure>
        <img src="images/fiesta/saumon_sauce_miel_garniture.png" alt="Pavé de saumon nappé d'une sauce au miel et présenté dans une assiette">
        <figcaption>Poursuivez les réjouissances avec un saumon fondant, délicatement relevé par la douceur du miel.</figcaption>
    </figure>
    <figure>
        <img src="images/fiesta/riz_souffle_smarties.png" alt="Dessert au riz soufflé décoré de petits bonbons chocolatés multicolores" >
        <figcaption>Retrouvez votre âme d’enfant avec un dessert croustillant au riz soufflé et aux bonbons chocolatés multicolores.</figcaption>
    </figure>
    <figure>
        <img src="images/fiesta/dome_chocolat_sauce_praline.png" alt="Dôme individuel au chocolat posé dans une assiette et décoré de praliné">
        <figcaption>Terminez la fête avec un élégant dôme au chocolat qui révèle toute la gourmandise du praliné.</figcaption>
    </figure>
    </div>       
        <p>Thème : Repas Evénement</p>
            <h3>Liste des plats du menu : </h3>
              <h4>Entrées </h4>
                <ul>
                <li>Toast au fromage de chèvre et à la poire</li>
                <li>Roulés de jambon au fromage et aux herbes</li>
                </ul>
              <h4>Plat du Jour</h4>
                <ul>
                <li>Saumon à la sauce au miel</li>
                </ul>
              <h4>Desserts</h4>
                <ul>
                <li>Riz soufflé aux smarties</li>
                <li>Dôme au chocolat praliné</li>
                </ul>  
        <p>Nombre minimal de personnes : 4</p>
        <p>Prix pour le nombre minimal : 240 euros</p>
        <p>Les allergènes :  </p>
                <ul>
                    <li>Gluten (farine de blé) : Toasts au fromage de chèvre et à la poire, dôme au chocolat praliné.</li>
                    <li>Fruits à coque : Dôme au chocolat praliné (Praliné fait avec des noisettes et/ou des amandes).</li>
                    <li>Lait : Toasts au fromage de chèvre et à la poire, roulés de jambon au fromage et aux herbes, riz soufflé aux Smarties, dôme au chocolat praliné.</li>
                    <li>Oeufs : Dôme au chocolat praliné.</li> 
                    <li>Poisson : Saumon à la sauce au miel </li>
                </ul>
        <p>Conditions particulières : Aucune</p>
        <p>Régime : Classique</p>
        <p>Stock disponible : dans la mesure du possible, les plats sont cuisinés à la demande</p>
        <?php if (isset($_SESSION['id_utilisateur'])): ?>
              <a href="formulaire_commande.php?menu=menu_fiesta" class="bouton_commande">COMMANDER</a>
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
 
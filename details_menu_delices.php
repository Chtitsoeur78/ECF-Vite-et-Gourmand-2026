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
              <h2>Ce mois-ci : Menu "Délices" </h2>
              <h3>Galerie photos</h3>
    <div class="galerie">
     <figure>
        <img src="images/charentes/menu_charentais.png" alt="Présentation du menu charentais de Vite & Gourmand comprenant au choix des huîtres de Marennes-Oléron ou des grattons charentais en entrée, une mouclade charentaise en plat principal, puis une galette charentaise ou un millas charentais en dessert">
        <figcaption>Découvrez les spécialités des Charentes réunies dans un menu complet proposé par Vite & Gourmand : Régalez-vous avec les Charentes dans votre assiette.</figcaption>
    </figure>
    <figure>
        <img src="images/delices/salade_nicoise.jpg" alt="Assiette de salade nicoise ">
        <figcaption>L'autenthique salade niçoise : Une des deux entrées du menu Délices chez Vite & Gourmand</figcaption>
    </figure>
    <figure>
        <img src="images/delices/quiche_lorraine.png" alt="">
        <figcaption>Les grattons charentais, la seconde entrée prévue dans le menu charentais "Vite & Gourmand"</figcaption>
    </figure>
    <figure>
        <img src="images/charentes/mouclade.png" alt="Mouclade, plat typiquement charentais à base de moules">
        <figcaption>La mouclade, le plat prinicpal du menu charentais de chez Vite et Gourmand</figcaption>
    </figure>
    <figure>
        <img src="images/charentes/galette_charentaise.png" alt="Une galette charentaise avec une part coupée">
        <figcaption>Pour découvrir les Charentes, la galette charentaise dessert typique de ce coin proposé dans le menu charentais "Vite & Gourmand"</figcaption>
    </figure>
    <figure>
        <img src="images/charentes/millas_charentais.png" alt="Part de millas charentais" >
        <figcaption>Le millas charentais, le deuxième dessert proposé dans le menu charentais "Vite & Gourmand"</figcaption>
    </figure>
    </div>
        <p>Thème : Repas classique</p>
        <h3>Liste des plats du menu : </h3>
            <h4>Entrées </h4>
              <ul>
                  <li>+ Salade niçoise</li>
                  <li>+ Quiche lorraine</li>
              </ul>
            <h4>Plat du Jour</h4>
              <ul>
                  <li>+ Boeuf bourguignon</li>
              </ul>
            <h4>Desserts</h4>
              <ul>
                  <li>+ Ile flottante</li>
                  <li>+ Clafoutis aux cerises</li>
              </ul>
        <p>Nombre minimal de personnes : 4</p>
        <p>Prix pour le nombre minimal : 160 euros</p>
        <p>Les allergènes :</p>
              <ul>
                    <li>poisson : Salade niçoise (thon, anchois)</li>
                    <li>gluten (farine de blé) : Quiche lorraine, clafoutis ayx cerises</li>
                    <li>oeufs : Salade niçoise, quiche lorraine, île flottante, clafoutis aux cerises</li>
                    <li>lait : Quiche lorraine, île flottante, clafoutis aux cerises </li>
                    <li>moutarde : Salade niçoise (dans vinaigrette)</li>
                    <li>céleri : Boeuf bourguignon (dans le bouquet garni)</li>
                    <li>suffites : Boeuf bourguignon (dans le vin servant à la cuisson)</li>
                    <li>fruits à coque : Île flottante (amandes)</li>
              </ul> 
        <p>Malgré toute notre vigilance, des traces d'autres allergènes peuvent être présentes en raison des conditions de préparation. Pour toute allergie alimentaire, n'hésitez pas à nous contacter avant de passer votre commande.</p>        <p>Conditions particulières : </p>
        <p>Régime : Classique</p>
        <p>Stock disponible : dans la mesure du possible, les plats sont cuisinés à la demande</p>
          <?php if (isset($_SESSION['id_utilisateur'])): ?>
              <a href="formulaire_commande.php?menu=menu_delices" class="bouton_commande">COMMANDE</a>
          <?php else: ?>
              <a href="formulaire_connexion_utilisateur.php" class="bouton_commande">SE CONNECTER POUR COMMANDER</a>
          <?php endif; ?>
        </section>
    </main>
     <?php include "footer.php"; ?>
   <!-- liaison avec la page externe de Javascript -->
    <script src="javascript/menu_burger.js"></script> 
  </body>
</html>

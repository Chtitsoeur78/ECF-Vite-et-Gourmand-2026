<?php
session_start();
?>

<!doctype html>
<html lang="fr">
  <head>
    <!-- balises meta indispensables -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Vite et Gourmand est une plate-forme de commande de repas utilisable dans la région de Bordeaux."
    />
    <!-- liaison avec la feuille de style externe de CSS -->
    <link rel="stylesheet" href="css/styles.css"/>
    <link rel="stylesheet" href="css/menu_burger.css?v=5"/>
    <link rel="stylesheet" href="css/details_menus.css?v=2"/>
    <link rel="stylesheet" href="css/footer.css"/>
             <!-- Titre de la Page -->
    <title>Vite et Gourmand - Détails des menus Evenement :  menu Mariage</title>
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
            <h2> le menu "Mariage"</h2>
                 <h3>Galerie - photos</h3>
    <p>Cliquez sur chaque photo pour lire son commentaire.</p>
    <div class="galerie">
    <figure>
        <img src="images/mariage/menu_mariage.png" alt="Présentation du menu Mariage de Vite & Gourmand comprenant du foie gras avec un chutney de figues et du pain d'épices, du homard à la citronnelle, un magret de canard aux cerises accompagné de pommes sarladaises, puis des profiteroles truffées aux airelles ou une pièce montée en dessert">
        <figcaption>Célébrez votre union avec le menu Mariage de Vite & Gourmand : une sélection de mets raffinés imaginée pour rendre cette journée encore plus inoubliable</figcaption>
    </figure>
    <figure>
        <img src="images/mariage/foie_gras_chutney_figues_paindepices.jpg" alt="Tranches de foie gras présentées avec un chutney de figues et des morceaux de pain d'épices">
        <figcaption>Commencez les festivités avec un foie gras délicat, accompagné de la douceur d’un chutney de figues et de pain d’épices.</figcaption>
    </figure>
    <figure>
        <img src="images/mariage/homard_roti_citronnelle.png" alt="Homard cuisiné à la citronnelle et présenté dans une assiette">
        <figcaption>Poursuivez cette dégustation raffinée avec un homard délicatement parfumé à la citronnelle.</figcaption>
    </figure>
    <figure>
        <img src="images/mariage/magret_canard_sauce_cerises_pdt_salardaises.png" alt="Magret de canard tranché et accompagné de cerises, de sauce et de pommes de terre sarladaises dorées.">
        <figcaption>Savourez un magret de canard tendre sublimé par une sauce fruitée aux cerises accompagné de savoureuses pommes de terre sarladaises, dorées et généreusement parfumées</figcaption>
    </figure>
    <figure>
        <img src="images/mariage/profiteroles _chocolat_noir_airelles.png" alt="Profiteroles garnies et nappées de chocolat, truffées aux airelles">
        <figcaption>Pour terminer sur une note gourmande et originale, laissez-vous séduire par nos profiteroles truffées aux airelles.</figcaption>
    </figure>
    <figure>
        <img src="images/mariage/piece_montee_choux_nougatine.png" alt="Pièce montée de mariage composée de plusieurs étages et décorée pour la cérémonie">
        <figcaption>Pour couronner cette journée exceptionnelle, partagez une élégante pièce montée confectionnée pour célébrer votre mariage.</figcaption>
    </figure>
    </div>
            <p>Contrairement aux autres menus proposés, le menu 'Mariage" posséde deux entrées, un plat principal et deux desserts.
            <p>Thème : Repas Evénement</p>
            <h3>Liste des plats du menu : </h3>
                    <h4>Entrées </h4>
                    <ul>
                        <li>Foie gras avec chutney de figue et pain d'épice</li>
                        <li>Homard rôti à la citronelle</li>
                    </ul>
                    <h4>Plat du Jour</h4>
                    <ul>
                        <li>Magret de canard sauce aux cerises servi de pommes de terre salardaises</li>
                    </ul>
                    <h4>Desserts</h4>
                    <ul>
                        <li>Profiterolles au chocolat noir truffées aux airelles</li>
                        <li>Pièce-montée de choux et nougatine</li>     
                    </ul>            
              <p>Nombre minimal de personnes : 12</p>
              <p>Prix pour le nombre minimal : 960 euros</p>
              <p>Les allergènes :</p>
                <ul>
                    <li>Crustacés : Homard</li>
                    <li>Fruits à coque : Nougatine de la pièce montée (amandes)</li>
                    <li>Gluten (farine de blé) : Pain d'épices accompagnant le foie gras, profiteroles au chocolat, choux de la pièce montée</li>
                    <li>Lait : Pain d'épices accompagnant le foie gras, profiteroles au chocolat, choux de la pièce montée</li>
                    <li>Oeufs : Pain d'épices accompagnant le foie gras, profiteroles au chocolat, choux de la pièce montée</li>                  
                </ul>
            <p>Malgré toute notre vigilance, des traces d'autres allergènes peuvent être présentes en raison des conditions de préparation. Pour toute allergie alimentaire, n'hésitez pas à nous contacter avant de passer votre commande.</p>
            <p>Conditions particulières :</p>
            <p>Le menu "Mariage" doit être commandé au plus tard un mois avant la date de livraison.</p>
            <p>Toute la vaisselle utilisée pour ce repas doit être rendue. </p>
            <p>Régime : Classique</p>
            <p>Stock disponible : Pour un repas de mariage, les plats sont cuisinés à la demande</p>
          <?php if (isset($_SESSION['id_utilisateur'])): ?>
              <a href="formulaire_commande.php?menu=menu_mariage" class="bouton_commande">COMMANDER</a>
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

          
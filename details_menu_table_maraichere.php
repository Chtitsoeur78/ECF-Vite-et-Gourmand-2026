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
    <link rel="stylesheet" href="css/details_menus.css?v=4">
    <link rel="stylesheet" href="css/footer.css"/>
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
              <h2> le menu "Table maraichère" </h2>      
              <h3>Galerie - photos</h3>
    <p>Cliquez sur chaque photo pour lire son commentaire.</p>
    <div class="galerie">
    <figure>
        <img src="images/table_maraichere/menu_table_maraichere.png" alt="Présentation du menu Table maraîchère de Vite & Gourmand comprenant des concombres au yaourt ou des poires au gorgonzola en entrée, un tajine végétarien aux pruneaux en plat principal, puis un pain perdu au caramel ou une bûche au citron et au praliné en dessert">
        <figcaption>Découvrez la Table maraîchère, un menu végétarien complet proposé par Vite & Gourmand : des recettes colorées et généreuses qui mettent les produits végétaux à l’honneur</figcaption>
    </figure>
    <figure>
        <img src="images/table_maraichere/salade_ concombres_yaourt_menthe.png" alt="Concombres coupés en morceaux et accompagnés d'une sauce crémeuse au yaourt">
        <figcaption>Commencez votre repas tout en fraîcheur avec des concombres croquants accompagnés d’une délicate sauce au yaourt.</figcaption>
    </figure>
    <figure>
        <img src="images/table_maraichere/poires_farcies-gorgonzola.png" alt="Poires découpées et présentées avec du gorgonzola dans une assiette">
        <figcaption>Laissez-vous surprendre par l’association de la douceur fruitée des poires et du caractère savoureux du gorgonzola.</figcaption>
    </figure>
    <figure>
        <img src="images/table_maraichere/Tajine_legumes_pruneaux.png" alt="Tajine végétarien composé de légumes et de pruneaux servi dans un plat traditionnel">
        <figcaption>Poursuivez votre découverte avec un tajine végétarien généreux, où les légumes rencontrent la douceur des pruneaux.</figcaption>
    </figure>
    <figure>
        <img src="images/table_maraichere/pain-perdu_sauce_caramel.png" alt="Tranches de pain perdu dorées et nappées de caramel dans une assiette" >
        <figcaption>Retrouvez les saveurs réconfortantes du pain perdu, délicieusement doré et accompagné d’un caramel gourmand</figcaption>
    </figure>
    <figure>
        <img src="images/table_maraichere/buche_citron_praliné.png" alt="Bûche au citron et au praliné présentée sur un plat de service" >
        <figcaption>Pour terminer sur une note raffinée, découvrez l’équilibre entre la fraîcheur du citron et la gourmandise du praliné</figcaption>
    </figure>
    </div>  
    <p>Thème : Repas classique</p>
           <h3>Liste des plats du menu : </h3>
              <h4>Entrées </h4>
                    <ul>
                        <li>Salade de concombre au yaourt</li>
                        <li>Poires farcies au gorgonzola</li>
                    </ul>
                <h4>Plat du Jour</h4>
                    <ul>
                        <li>Tajine de légumes aux pruneaux</li>
                    </ul>
                <h4>Desserts</h4>
                    <ul>
                        <li>Pain perdu à la sauce caramel</li>
                        <li>Bûche citron au praliné</li>
                    </ul>            
              <p>Nombre minimal de personnes : 4</p>
              <p>Prix pour le nombre minimal : 180 euros</p>
              <p>Les allergènes :</p>
                <ul>
                    <li>Gluten (farine de blé) : Pain perdu à la sauce caramel, bûche citron au ptaliné</li>
                    <li>Lait : Salade de concombres au yaourt, Poires farcies au gorgonzola, pain perdu à la sauce caramel, bûche citron au praliné </li>
                    <li>Oeufs : Pain perdu à la sauce caramel, bûche citron au praliné </li> 
                    <li>Fruits à coque : Poires farcies au gotgonzola (noix décoratives), bûche citron au praliné (noisettes)</li>
                    <li>céleri : Tajine de légumes aux pruneaux </li>
                </ul>
              <p>Malgré toute notre vigilance, des traces d'autres allergènes peuvent être présentes en raison des conditions de préparation. Pour toute allergie alimentaire, n'hésitez pas à nous contacter avant de passer votre commande.</p>
              <p>Conditions particulières : Le tajine (plat marocain) dans lequel est livré le tajine de légumes aux pruneaux doit être rendu</p>
              <p>Régime : Végétarien</p>
              <p>Stock disponible : dans la mesure du possible, les plats sont cuisinés à la demande.</p>
        <?php if (isset($_SESSION['id_utilisateur'])): ?>
              <a href="formulaire_commande.php?menu=menu_table_maraichere" class="bouton_commande">COMMANDER</a>
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


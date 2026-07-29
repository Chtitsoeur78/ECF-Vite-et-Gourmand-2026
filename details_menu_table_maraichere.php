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
    <link rel="stylesheet" href="css/menu_burger.css"/>
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
        
        <section class="details_menu_tablemaraichere">
              <h2> le menu "Table maraichère" </h2>
              <p>galerie de photos</p>
              <p>Thème : Repas classique</p>
              <p>La liste des plats du menu : 
                <p>Entrées </p>
                <div>+ Salade de concombre au yaourt</div>
                <div>+ Poires farcies au gorgonzola </div>
                <p>Plat du Jour</p>
                <div>+ Tajine de légumes aux pruneaux</div>
                <p>Desserts</p>
                <div>+ Pain perdu à la sauce caramel</div>
                <div>+ Bûche citron au praliné</div>
              </p>
              <p>Nombre minimal de personnes : 4</p>
              <p>Prix pour le nombre minimal : 180 euros</p>
              <p>Les allergènes :  </p>
              <p>Conditions particulières : </p>
              <p>Régime : Végétarien</p>
              <p>Stock disponible : dans la mesure du possible, les plats sont cuisinés à la demande</p>
        <a href="formulaire_commande.php?menu="table_maraichere" class="bouton_commande">COMMANDE</a>    
       <?php if (isset($_SESSION['id_utilisateur'])): ?>
              <a href="formulaire_commande.php?menu=table_maraichere" class="bouton_commande">COMMANDE</a>
        <?php else: ?>
              <a href="formulaire_connexion_utilisateur.php" class="bouton_commande">SE CONNECTER POUR COMMANDER</a>
        <?php endif; ?>
      
      
      </section>
      </main>  
  <!-- liaison avec la page externe de Javascript -->
    <script src="javascript/menu_burger.js"></script> 
  </body>
</html>

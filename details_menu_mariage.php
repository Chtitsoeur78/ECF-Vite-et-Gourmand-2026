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
    <link rel="stylesheet" href="css/menu_burger.css"/>
    <link rel="stylesheet" href="css/details_menus.css"/>
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
        <section class="details_menu">
              <h2> le menu "Mariage" </h2>
              <p>galerie de photos</p>
              <p>Thème : Repas Evénement</p>
              <p>Contrairement aux autres menus proposés, le menu 'Mariage" posséde deux entrées, un plat principal et deux desserts.
              <p>La liste des plats du menu :</p>
                <p>Entrées </p>
                <div>+ Fois gras avec chutney de figue et pain d'épice</div>
                <div>+ Homard rôti à la citronelle</div>
                <p>Plat du Jour</p>
                <div>+ Magret de canard sauce aux cerises servi de pommes de terre salardaises</div>
                <p>Desserts</p>
                <div>+ Profiterolles au chocolat noir truffées aux airelles</div>
                <div>+ Pièce-montée de choux et nougatine</div>
              </p>
              <p>Nombre minimal de personnes : 12</p>
              <p>Prix pour le nombre minimal : 960 euros</p>
              <p>Les allergènes :  </p>
              <p>Conditions particulières : </p>
              <p>Régime : Classique</p>
              <p>Stock disponible : Pour un repas de mariage, les plats sont cuisinés à la demande</p>
          <?php if (isset($_SESSION['id_utilisateur'])): ?>
              <a href="formulaire_commande.php?menu=menu_mariage" class="bouton_commande">COMMANDE</a>
          <?php else: ?>
              <a href="formulaire_connexion_utilisateur.php" class="bouton_commande">SE CONNECTER POUR COMMANDER</a>
          <?php endif; ?>
      </section>
      </main>  
  <!-- liaison avec la page externe de Javascript -->
    <script src="javascript/menu_burger.js"></script> 
  </body>
</html>

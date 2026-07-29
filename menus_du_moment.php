<!doctype html>
<html lang="fr">
  <head>
    <!-- balises meta indispensables -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- liaison avec la page externe de css -->
    <
    <link rel="stylesheet" href="css/styles.css"/>
    <link rel="stylesheet" href="css/menu_burger.css"/>
    <link rel="stylesheet" href="css/menus_du_moment.css"/>
    <link rel="stylesheet" href="css/footer.css"/>   
    <title>
      Vite & Gourmand : Menus du Moment
    </title>
  </head>
  <!-- body : Contenu de la page -->
  <body>
  <header class="header_conteneur">
      <section>
          <?php include "menu_burger.php"; ?>
      </section>
      <section>    
          <h1>Menus du Moment</h1>
      </section> 
      <section class="logo">
        <img src="images/logo1.png" alt="Logo de Vite et Gourmand, la plate-forme pour manger vite et bien"/>
      </section>
    </header>
    <main>
      <section class="menus_du_moment">
          <p>Vite et Gourmand vous propose de la cuisine française raffinée, pas chère et à déguster chez vous quand vous le souhaitez .... </p>
          <p>Nous vous proposons de nouveaux menus tous les mois.</p>
          <p>Mais sachez que chaque commande effectuée sera honorée à la date voulue quelque soit les menus proposés sur le site au même moment.</p>
          <p>Excepté pour le menu "Mariage" où tous les plats décrits vous sont proposés, vous devez choisir parmi deux entrées puis parmi deux desse</p>
          <p>Le prix proposé est un prix total : Entrée + Plat du Jour + Dessert</p>
          <p> Entre le 15 mai et le 15 Juin 2026, Vite et Gourmand vous propose : </p>
      </section>
      <section>
         <h2>Le menu "Sud-Ouest"</h2>
            <article class="contenu">
                <h3>Menu "Charentes"</h3>
                  <p>Offrez-vous ce mois-ci une escapade gourmande entre terre et mer avec notre menu charentais. </p>
                  <p>Notre Mouclade préparée à base de moules fraîches et d'une sauce onctueuse et épicée à point vous permettra de partager un menu gourmand aux accents d'Atlantique.</p>
                  <p>Nombre minimal de personnes : 4</p>
                  <p>Prix pour 4 : 200 € </p>
                <div class="menus_details">  
                  <a href="details_menu_charentes.php" class="bouton_details">DETAILS DE CE MENU</a>
                </div>
            </article>
      </section>
      <section>
          <h2>Les menus "Classiques"</h2>
            <article class="contenu">
                <h3>Menu "Délices"</h3>
                 <p>notre menu "Délices" vous invite à un voyage gourmand à travers les régions de France, en réunissant des spécialités authentiques, comme la salade niçoise ou la quiche lorraine.</p> 
                 <p>Une harmonie de recettes emblématiques et généreuses qui vcélèbre tout le savoir-faire et la richesse de la gastronomie française. </p>
                 <p>Nombre minimal de personnes : 4</p>
                 <p>Prix pour 4 : 160 €</p>
                 <a href="details_menu_delices.php" class="bouton_details">DETAILS DE CE MENU</a>
            </article>
            <article class="contenu">
                <h3> Menu "Table Maraichère"</h3>
                  <p>Alliant plaisir et gastronomie, la table Maraichère vous transporte au coeur d'une cuisine végétarienne généreuse et raffinée : Un voyage végétal subtile et équilibrée aux saveurs délicates et réconfortantes.</p>
                  <p>Une parenthèse authentique et savoureuse pensée pour célébrer la richesse du terroir et la créativité de la cuisine végétale. </p>
                  <p>Nombre minimal de personnes : 4</p>
                 <p>Prix pour 4 : 180 €</p>
                  <a href="details_menu_table_maraichere.php" class="bouton_details">DETAILS DE CE MENU</a>
            </article>
            <article class="contenu">
                <h3>Menu "le Jardin de José"</h3>
                  <p>le Jardin de José vous réserve des surprises ensoleillées abec un menu végan frais et raffiné mêlant salade de fenouil, gaspacho parfumé, tarte au citron et muffins aux fruits gourmands.</p>
                  <p>Une escapade gourmande aux saveurs du sud, sublimée par des poivrons farcis au quinoa, plein de couleurs et de caractère. </p>
                  <p>Nombre minimal de personnes : 4</p>
                  <p>Prix pour 4 : 180 €</p>
                  <a href="details_menu_jardin_de_jose.php" class="bouton_details">DETAILS DE CE MENU</a>
            </article>
            </section>
      <section>
          <h2>Les menus "Evénement"</h2>
            <article class="contenu">
                <h3>Le menu "Mariage"</h3>
                  <p>Pour le plus beau jour de votre vie, José mets les petits plats dans les grands et vous propose une parenthès gastronomique d'exception, où l'élégance du foie gras au chutney de figue répond à la finesse du homard rôti à la citronelle. </p>
                  <p>Ce menu d'apparat se prolonge avec un savoureux magret de canard accompagné de pommes de terre salardaises, avant de s'achever sur la gourmandise de profiteroles au chocolat noir truffées aux airelles et l'incontournable pièce montée, symbole de célébration et de partage </p>
                  <p>Nombre minimal de personnes : 12</p>
                  <p>Prix pour 12 : 960 €</p>
                  <a href="details_menu_mariage.php" class="bouton_details">DETAILS DE CE MENU</a>   
            </article>
            <article class="contenu">
                <h3>Le menu "Fiesta"</h3>
                  <p>Pour une bonne fête, rien de tel que de partager un menu généreux et convivial imaginé par José où les toasts au fromage de chèvre à la poire et les roulés de jambon au fromage et aux herbes ouvrent les festivités avec gourmandise.</p>
                  <p>Entre saumon nappé d'une sauce au miel et soufflé culinaire aux saveurs délicates, cette table festive promet un moment chaleureux placé sous le signe du plaisir et de la convivialité. </p>
                  <p>Nombre minimal de personnes : 4</p>
                  <p>Prix pour 4 : 240 €</p>
                  <p>
                  <a href="details_menu_fiesta.php" class="bouton_details">DETAILS DE CE MENU</a>
                  </p>
            </article>
      </section>
    </main> 
    <!-- footer : le pied de page  -->
    <?php include "footer.php"; ?>
  <!-- liaison avec la page externe de Javascript -->
    <script src="javascript/menu_burger.js"></script> 
  </body>
</html>

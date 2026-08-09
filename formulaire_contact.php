<!DOCTYPE html>
<html lang="fr"> 
<head>
<!-- balises meta indispensables -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- liaison avec la page externe de css --> 
    <link rel="stylesheet" href="css/styles.css">  
    <link rel="stylesheet" href="css/menu_burger.css"/>
    <link rel="stylesheet" href="css/formulaire_contact.css">
    <link rel="stylesheet" href="css/footer.css"/>
<title>Formulaire de Contact</title>
</head>
<body>
<!-- header : l'en-tête de la page --> 
    <header class="header_conteneur">
     <section class="menu_burger">
        <?php include "menu_burger.php"; ?>
     </section>        
     <section>
            <h1>Contactez nous !</h1>
        </section>
        <section class="logo">
        <img src="images/logo1.png" alt="Logo de Vite et Gourmand, la plate-forme pour manger vite et bien" >
      </section>
    </header> 
    <main>
        <section class="presentation">
           <p>Une question ? Une remarque ? Un retour d'expérience ? Une amélioration ? Un problème ? </p>
           <p>Dans tous les cas, n'hesitez pas à nous contacter avec ce formulaire ... </p>
           <p>A bientôt ! </p>
        </section>
    <form action="traitement_contact.php" method="post">
        <fieldset>
            <legend class="legend">Vos données personnelles</legend>
        <p>
            <label for="pseudo">PSEUDO : </label>
            <input type="text" id="pseudo" name="pseudo" placeholder="Pseudonyme">
        </p>
        <p>
            <label for="prenom">PRÉNOM * : </label>
            <input type="text" id="prenom" required name="prenom" placeholder="Prénom">
        </p>
        <p>
             <label for="nom_famille">NOM DE FAMILLE * : </label>
            <input type="text" id="nom_famille" required name="nom_famille" placeholder="Nom de famille" >
        </p>
        <p>
            <label for="email">E-MAIL * : </label>
            <input type="email" id="email" required name="email" placeholder="xxm@yy.zz" >
        </p>
        </fieldset>
        <fieldset>
            <legend class="legend">Votre message</legend> 
        <p>
            <label for="sujet"> QUEL EST LE SUJET DE VOTRE MESSAGE ? *</label>
            <select name="sujet" id="sujet" required>
            <option value="" disabled selected hidden> Choisissez le sujet de votre message</option>       
            <option value="probleme_commande">Un problème avec une commande</option>
            <option value="probleme_facturation">Un problème avec la facturation / le prix</option> 
            <option value="retour_experience">Un retour d'expérience d'un repas "Vite & Gourmand"</option>
            <option value="nouveaux_menus">Une proposition de nouveaux menus</option>
            <option value="donnees_personnelles"> Tout ce que vous voulez savoir sur vos données personnelles</option>
            <option value="origine_produits">Tout ce que vous voulez savoir sur l'origine de nos produits</option>
            <option value="autres">Autres</option>
            </select>
        </p>
        <p>
            <label for="titre">TITRE * :</label>
            <input type="text" required id="titre" name="titre" placeholder="xxxxxxxxxxxxx"/>
        </p>
        <p><label for="message">VOTRE TEXTE * </label></p>
            <textarea id="message" required name="message" placeholder="Bonjour ! Je prends contact avec vous car ... "></textarea>
        <p>
            <label>DATE D'ENVOI :</label>
            <input type="date" id="date_envoi" name="date_envoi"/>
        </p>
        <p>
            <button type="submit" name="OK" value="Envoyer"> Envoyer mon message</button>    
        </p>
        </fieldset>
    </form>
    
    </main>
<!-- liaison avec la page externe de Javascript -->
<script src="javascript/menu_burger.js"></script> 
</body>
</html>
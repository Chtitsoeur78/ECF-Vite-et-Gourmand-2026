<?php
session_start();
?>
<!DOCTYPE html> 
<html lang="fr"> 
<head> 
<!-- balises meta indispensables --> 
<meta charset="utf-8"> 
<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
<meta name="description" content="Vite et Gourmand est une plate-forme de commande de repas utilisable dans la région de Bordeaux.">
<!-- liaison avec la page externe de css --> 
<link rel="stylesheet" href="css/styles.css"> 
<link rel="stylesheet" href="css/index.css?v=2">
<link rel="stylesheet" href="css/footer.css">
<!-- Titre de la Page --> 
<title>Vite et Gourmand : Mangeons vite et bien ! </title> 
</head> 
<!-- body : Contenu de la page --> 
<body>
    <!-- header : l'en-tête de la page d'accueil --> 
    <header class="header_conteneur">
        <nav class="utilisateur_gauche">
            <a class="bouton_contact" href="formulaire_contact.php">CONTACTEZ-NOUS</a>
        </nav>
        <section class="logo"> 
            <h1 class="sr_only">Vite et Gourmand ! Mangeons vite et bien</h1> <!-- "sr_only" caché visuellement mais lisible par les lecteurs d'écran -->
            <img class="logo_img" src="images/logo1.png" alt="Logo de Vite et Gourmand, la plate-forme pour manger vite et bien" >
       </section> 
        <nav class="utilisateur_droite" aria-label="Zone-utilisateur">
            <h3>Espace <br>
                Utilisateur</h3>
        <div class="bloc_bouton"> 
            <a href="formulaire_inscription.php" class="bouton_utilisateur">CRÉER UN COMPTE</a>
            <a href="formulaire_connexion_utilisateur.php" class="bouton_utilisateur">SE CONNECTER</a>
        </div>
       <?php if (isset($_SESSION['id_utilisateur'])): ?>
        <div class="zone_connexion">
            <p>
        Bonjour <?= htmlspecialchars($_SESSION["prenom"]) ?> 👋
            </p>
        <a href="deconnexion.php" class="deconnexion">Se déconnecter</a>
        </div>
        <?php endif; ?>
        </nav>
        </header>
           <!-- Main : Le contenu principal de la page  -->
    <main>
         <section class="slogan_conteneur">
            <div class="slogan">
                <p>Vite et Gourmand</p>
                <p>Le goût du fait maison</p>
                <p>Exquis à déguster</p>
                <p>Rapidement livré</p>
            </div>
        </section>
        <section class="pub_conteneur">
        <article class="ponctualite_article">
            <img class="ponctualite_img" src="images/image_ponctualite.jpg" alt="Vite et Gourmand : des livraisons toujours à l'heure">
            <p>Une livraison ponctuelle </p>
        </article>
        <article class="qualite_produit_article">
           <img class="qualite_produit_img" src="images/image_produits_frais.jpg" alt="Vite et Gourmand : Uniquement des produits frais et de qualité"> 
            <p>Des produits frais de qualité </p>
        </article>   
        <article class="equipe_experimentee_article">
            <img class="equipe_experimentee_img" src="images/image_equipe_cuisine.jpg" alt="Vite et Gourmand : Une équipe expérimentée">
            <p>Une équipe expérimentée </p>
        </article>
        </section>
        <!-- Pour aller vers les  menus du moment -->
        <div class="menus">
        <a href="menus_du_moment.php" class="bouton_menus">DÉCOUVREZ NOS MENUS</a>
        </div>
        <!-- La présentation de l'entreprise -->
        <section>
            <h2>Qui sommes-nous ?</h2>
            <div class="presentation_contenu">
                <section class="historique">
                    <h3>L'Histoire de notre entreprise</h3>    
                    <p>Nous sommes en l'an 2000 : Après une dizaine d'années passées à cuisiner chez les Frères Troisgros à Roanne dans la Loire, José Toc décide de rentrer à Bordeaux, sa ville natale.</p>
                    <p>Il se demande comment créer un nouveau concept pour faire manger des bons petits plats chez soi, variés et sans se ruiner ?</p>
                    <p>Sa réponse : Vite et Gourmand </p>
                </section>
                <section class="equipe">
                    <h3>Notre équipe</h3>
                    <img src="images/equipe_vg_horizontal.png" alt="L'équipe de Vite et Gourmand : A gauche, Julie Durand, responsable logistique. Au centre José Toc, cuisinier, gérant, administrateur. A droite, Jean-Marie Detrot, chauffeur."> 
                </section> 
                <section class="lieu">
                    <h3>Pour nous trouver ...</h3>
                    <p>Notre adresse : 23 place des Quinconces à Bordeaux</p>                
                    <p>Notre zone de livraison : toutes les communes du département de la Gironde</p>
                </section>
            </div>
        </section>
         <!-- Les avis des clients --> 
        <section class="avis_clients">
            <h2 class="titre">Nos clients donnent leur avis ... </h2>
            <div class="avis_clients_conteneur">
                <article class="avis_clients_contenu">
                    <p><strong>Pseudo : </strong> Chtitsoeur</p> 
                    <p><strong>Note : </strong>
                    <span aria-label="5 étoiles sur 5" role="img">★★★★★</span></p>
                    <p><strong>Commentaire : </strong> Super appli ! Très pratique pour un merveilleux repas.</p> 
                    <p><strong>Publié le : </strong><time datetime="2025-11-30" aria-label="30 novembre 2025">30/11/2025</time></p>
                </article> 
                <article class="avis_clients_contenu">
                    <p><strong>Pseudo : </strong>Roro</p> 
                    <p><strong>Note : </strong>
                    <span aria-label="5 étoiles sur 5" role="img">★★★★★</span></p>
                    <p><strong>Commentaire : </strong> Excellent repas - Quantité parfaite - A recommander !</p> 
                    <p><strong>Publié le : </strong><time datetime="2025-11-30" aria-label="30 novembre 2025">30/11/2025</time></p>
                </article>
               <article class="avis_clients_contenu">
                    <p><strong>Pseudo : </strong>Olivier</p> 
                    <p><strong>Note : </strong>
                    <span aria-label="5 étoiles sur 5" role="img">★★★★★</span></p>
                    <p><strong>Commentaire : </strong> Excellent repas - Quantité parfaite </p> 
                    <p><strong>Publié le : </strong><time datetime="2025-12-09" aria-label="9 décembre 2025">09/12/2025</time></p>
                </article>
            </div>
        </section>
        </main> 
       <?php include "footer.php"; ?>  
    </body>
</html>


<!DOCTYPE html>
<html lang="fr"> 
<head>
<!-- balises meta indispensables -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- liaison avec la page externe de css --> 
    <link rel="stylesheet" href="css/styles.css">  
    <link rel="stylesheet" href="css/menu_burger.css"/>
    <link rel="stylesheet" href="css/formulaire_avis.css">
    <link rel="stylesheet" href="css/footer.css"/>
<title>Formulaire de Contact</title>
</head>
<body>
<!-- header : l'en-tête de la page d'accueil --> 
    <header class="header_conteneur">
     <section class="menu_burger">
        <?php include "menu_burger.php"; ?>
     </section>        
     <section>
            <h1>Votre avis nous intéresse</h1>
        </section>
        <section class="logo">
        <img src="images/logo1.png" alt="Logo de Vite et Gourmand, la plate-forme pour manger vite et bien" >
      </section>
    </header> 
    <main>
        <section class="introduction">
           <p>Bonjour ! </p>
           <p>Vous venez d'utiliser Vite et Gourmand et vous souhaitez nous donner votre avis ? N'hésitez pas ! </p>
           <p>Merci de remplir le formulaire ci-dessous</p>
           <p>A bientôt ! </p>
        </section>
        <form action="traitement_avis.php" method="post">
        <fieldset>
        <p>
            <label for="pseudo">PSEUDO : </label>
            <input type="text" id="pseudo" name="pseudo" placeholder="Pseudonyme">
        </p>
        <p>
            <input type="hidden" name="id_commande"
                value="<?= (int) ($_GET['id_commande'] ?? 0) ?>"
    >   </p> 
        <legend class="legend">Votre avis</legend> 
        <p>
            <label for="note"> NOTE SUR 5 *</label>
            <select name="note" id="note" required>
            <option value="" disabled selected hidden> Choisissez la note voulue</option>       
            <option value="0">0</option>   
            <option value="1">1</option>   
            <option value="2">2</option>   
            <option value="3">3</option>   
            <option value="4">4</option>   
            <option value="5">5</option>  
            </select>
        </p>
        <p><label for="message">VOTRE COMMENTAIRE * </label></p>
            <textarea id="message" required name="message" placeholder="----"></textarea>
        </p>
        <p>
            <button type="submit" name="OK" value="Envoyer"> Envoyer mon avis</button>    
        </p>
        </fieldset>
    </form>
    </main>
<!-- liaison avec la page externe de Javascript -->
<script src="javascript/menu_burger.js"></script> 
</body>
</html>
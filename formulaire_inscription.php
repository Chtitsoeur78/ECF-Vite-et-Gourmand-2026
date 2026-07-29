<!doctype html>
<html lang="fr">
  <head>
    <!-- balises meta indispensables -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- liaison avec la page externe de css -->
    <link rel="stylesheet" href="css/styles.css"/>
    <link rel="stylesheet" href="css/menu_burger.css"/>
     <link rel="stylesheet" href="css/formulaire_inscription.css"/>
    <title>
      Vite & Gourmand - Formulaire d'inscription
    </title>
  </head>
  <!-- body : Contenu de la page -->
  <body>
  <header class="header_conteneur">
      <section>
          <?php include "menu_burger.php"; ?>
      </section>
      <section>    
          <h1>Inscription</h1>
      </section> 
      <section class="logo">
        <img src="images/logo1.png" alt="Logo de Vite et Gourmand, la plate-forme pour manger vite et bien"/>
      </section>
    </header>
    <main>
      <p>
        Soyez les bienvenus chez Vite & Gourmand, l'endroit où l'on mange vite
        et bien !
      </p>
      <p>
        Pour créer votre compte, merci de remplir toutes les rubriques du
        formulaire ci-dessous ...
      </p>
      <p>Les rubriques avec une * sont obligatoires</p>
      <p>A bientôt !</p>
      <p>
        Si vous commandez au nom d'une "personne morale" (Entreprise,
        institution, association), Merci de remplir la case "Raison sociale"
        puis les données de la personne qui passe la commande
      </p>
      <form action="traitement_inscription.php" method="post"> 
        <fieldset>
          <legend class="legend">Vos Données Personnelles</legend>
          <p>
            <label for="raison_sociale"> RAISON SOCIALE :</label>
            <input type="text" id="raison_sociale"name="raison_sociale" placeholder="Raison sociale"/>
          </p>
          <p>
            <label for="pseudo"> PSEUDONYME * :</label>
            <input type="text" required id="pseudo" name="pseudo" placeholder="Pseudonyme"/>
          </p>
          <p>
            <label for="civilite">CIVILITÉ :</label>
            <select id="civilite" name="civilite">
              <option value="">Choisir</option>
              <option value="monsieur">Monsieur</option>
              <option value="madame">Madame</option>
            </select>
          </p>
            <label for="prenom">PRÉNOM *:</label>
            <input type="text" required id="prenom" name="prenom" autocomplete="given-name" placeholder="Prénom"/>
          </p>
          <p>
            <label for="nom">NOM DE FAMILLE * :</label>
            <input type="text"required id="nom" name="nom" autocomplete="family-name" placeholder="Nom de Famille"/>
          </p>
          <p>
            <label for="email">E-MAIL * :</label>
            <input type="email" required id="email" name="email" autocomplete="email" placeholder="xxxx@yyy.zzz"/>
          </p>
          <p>
            <label for="mot_de_passe">MOT DE PASSE * :</label>
            <input type="password" required id="mot_de_passe" name="mot_de_passe" minlength="10" maxlength="80" placeholder="xxxxxxxxxxxxx"/>
          </p>
           <p>
                <label for="mot_de_passe_confirme">MOT DE PASSE CONFIRMÉ * :</label>
                <input type="password" required id="mot_de_passe_confirme" name="mot_de_passe_confirme" minlength="10" maxlength="80" placeholder="confirmer votre mot de passe">
          </p>
          <p>
            <label for="telephone">TÉLÉPHONE * :</label>
            <input type="tel" required id="telephone" name="telephone" pattern="[0-9 ]{10}" autocomplete="tel" placeholder="0000000000"/>
          </p>
        </fieldset>
        <fieldset>
          <legend class="legend">Adresse de Livraison</legend>
          <p>
            <label for="livraison_adresse_complement">COMPLÉMENT D'ADRESSE :</label>
            <input type="text" id="livraison_adresse_complement" name="livraison_adresse_complement" maxlength="120" placeholder="batiment - étage - n° appartement"/>
          </p>
          <p>
            <label for="livraison_adresse">ADRESSE * :</label>
            <input type="text" required id="livraison_adresse" name="livraison_adresse" maxlength="120" placeholder="numero + voie + nom voie"/>
          </p>
          <p>
            <label for="livraison_code_postal">CODE POSTAL :</label>
            <input type="text" id="livraison_code_postal" name="livraison_code_postal"maxlength="5"pattern="[0-9]{5}" placeholder="code postal"/>
          </p>
          <p>
            <label for="livraison_ville">VILLE * :</label>
            <input type="text" required id="livraison_ville" name="livraison_ville" placeholder="nom de la ville"/>
          </p>
        </fieldset>
        <fieldset>
          <legend class="legend">Adresse de facturation</legend>
          <p>
            L'adresse de facturation est-elle la même que celle de livraison ?
            <select id="facturation" name="facturation">
              <option value="">Choisir</option>
              <option value="oui">OUI</option>
              <option value="non">NON</option>
            </select>
          </p>
          <p>Si la réponse est non, remplir l'adresse ci-dessous</p>
          <p>
            <label for="facturation_adresse_complement">COMPLÉMENT D'ADRESSE :</label>
            <input type="text" id="facturation_adresse_complement" name="facturation_adresse_complement" maxlength="120" placeholder="batiment - étage - n° appartement"/>
          </p>
          <p>
            <label for="facturation_adresse">ADRESSE :</label>
            <input type="text" id="facturation_adresse" name="facturation_adresse" maxlength="120" placeholder="numero + voie + nom voie"/>
          </p>
          <p>
            <label for="facturation_code_postal">CODE POSTAL :</label>
            <input type="text" id="facturation_code_postal" name="facturation_code_postal" maxlength="5" pattern="[0-9]{5}" placeholder="code postal"/>
          </p>
          <p>
            <label for="facturation_ville">VILLE :</label>
            <input type="text" id="facturation_ville" name="facturation_ville" placeholder="nom de la ville"/>
          </p>
        </fieldset>
          <p>
            <label>Date d'inscription :</label>
            <input type="date" id="date_inscription" name="date_inscription"/>
          </p>
        <button type="submit" name="OK" value="Envoyer">Créer un compte</button>
      </main>
      <!-- liaison avec la page externe de Javascript -->
      <script src="javascript/menu_burger.js"></script> 
  </body>
</html>

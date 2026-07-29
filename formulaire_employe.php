<!doctype html>
<html lang="fr">
  <head>
    <!-- balises meta indispensables -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- liaison avec la page externe de css -->
    <link rel="stylesheet" href="css/styles.css"/>
    <link rel="stylesheet" href="css/menu_burger.css"/>
     <link rel="stylesheet" href="css/formulaire_employe.css"/>
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
          <h1>Création "Employé"</h1>
      </section> 
      <section class="logo">
        <img src="images/logo1.png" alt="Logo de Vite et Gourmand, la plate-forme pour manger vite et bien"/>
      </section>
    </header>
    <main>
       <form action="traitement_creation_employe.php" method="post">
        <fieldset>
           <legend class="legend">Les Données Personnelles</legend>
          <p>
            <label for="civilite">CIVILITÉ :</label>
            <select id="civilite" name="civilite">
              <option value="">Choisir</option>
              <option value="monsieur">Monsieur</option>
              <option value="madame">Madame</option>
            </select>
          </p>
          <p>
            <label for="prenom">PRENOM * :</label>
            <input type="text" required id="prenom" name="prenom" autocomplete="given-name" placeholder="Prénom"/>
          </p>
          <p>
            <label for="nom">NOM DE FAMILLE * :</label>
            <input type="text" required id="nom" name="nom" autocomplete="family-name" placeholder="Nom de Famille"/>
          </p>
          <p>
            <label for="role">RÔLE :</label>
            <select id="role" name="role" required>
              <option value="">Choisir</option>
              <option value="1">1 - Administrateur</option>
              <option value="2">2 - Employé</option>
              </select>
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
          <p>
            <label for="adresse">ADRESSE * :</label>
            <input type="text" required id="adresse" name="adresse" maxlength="120" placeholder="numero + voie + nom voie"/>
          </p>
          <p>
            <label for="code_postal">CODE POSTAL :</label>
            <input type="text" id="code_postal" name="code_postal" maxlength="5"pattern="[0-9]{5}" placeholder="Code Postal"/>
          </p>
          <p>
            <label for="ville">VILLE * :</label>
            <input type="text" required id="ville" name="ville" style="width:250px" placeholder="nom de la ville"/>
          </p>
        </fieldset>
        <fieldset>
          <legend class="legend">Dans notre entreprise</legend>
        <p>
            <label for="date_embauche">DATE D'EMBAUCHE :</label>
            <input type="date" id="date_embauche" name="date_embauche"/>
          </p>
          <p>
            <label for="type_contrat">TYPE DE CONTRAT :</label>
                <select id="type_contrat" name="type_contrat">
                  <option value="">Choisir</option>
                  <option value="cdi">C.D.I</option>
                  <option value="cdd">C.D.D.</option>
                  <option value="stagiaire">Stagiaire</option>
                  <option value="interimaire">Intérimaire</option>
            </select>
          </p>
          <p>
            <label for="fonction">FONCTION :</label>
            <input type="text" id="fonction" name="fonction" style="width:400px"/>
          </p>
          <p>
            <label for="date_prise_fonction">DATE PRISE DE FONCTION :</label>
            <input type="date" id="date_prise_fonction" name="date_prise_fonction"/>
          </p>
          <p>
            <label for="date_sortie">DATE FIN DE CONTRAT :</label>
            <input type="date" id="date_fin_contrat" name="date_fin_contrat"/>
          </p>
          </fieldset>
          <fieldset>
          <p>
            <label for="date_creation_compte">Date de la Création du compte : </label>
            <input type="date" id="date_creation_compte" name="date_creation_compte"
             value="<?php echo date('Y-m-d'); ?>"/>
          </p>
          <p>
          <button type="submit" name="OK" value="Envoyer">Créer un compte "Employé"</button>
          </p>
          </fieldset>
    </main>
      <!-- liaison avec la page externe de Javascript -->
      <script src="javascript/menu_burger.js"></script> 
  </body>
</html>

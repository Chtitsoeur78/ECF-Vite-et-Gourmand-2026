<?php
session_start();
require_once 'connexion.php';

if (!isset($_SESSION['id_utilisateur'])) {
    header('Location: connexion_utilisateur.php');
    exit();
}

$id_utilisateur = (int) $_SESSION["id_utilisateur"];

$sql = "
    SELECT utilisateurs.*, commune_gironde.commune_gironde
    FROM utilisateurs
    LEFT JOIN commune_gironde
        ON utilisateurs.livraison_id_commune = commune_gironde.id_commune
    WHERE utilisateurs.id_utilisateur = :id_utilisateur
";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'id_utilisateur' => $id_utilisateur
]);

$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$utilisateur) {
    header('Location: espace_utilisateur.php');
    exit();
} ?>
<!doctype html>
<html lang="fr">
  <head>
    <!-- balises meta indispensables -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- liaison avec la page externe de css -->
    <link rel="stylesheet" href="css/styles.css"/>
    <link rel="stylesheet" href="css/menu_burger.css"/>
     <link rel="stylesheet" href="css/formulaire_moodifications_donnees_personnelles.css"/>
    <title>
      Vite & Gourmand - Formulaire de mofifications des données personnelles 
    </title>
  </head>
  <!-- body : Contenu de la page -->
  <body>
  <header class="header_conteneur">
      <section>
          <?php include "menu_burger.php"; ?>
      </section>
      <section>    
          <h1>Modifications des données personnelles</h1>
      </section> 
      <section class="logo">
        <img src="images/logo1.png" alt="Logo de Vite et Gourmand, la plate-forme pour manger vite et bien"/>
      </section>
    </header>
    <main>
      <section class="presentation">
      <p>Vous souhaitez modifier vos données personnelles pour pouvoir être toujours en contact avec Vite et Gourmand ? </p>
      <p>C'est très simple : </p>
      <p>Il vous suffit de changer ce que vous voulez dans le formulaire ci-dessous et de nous le renvoyer  </p>
      <p>A bientôt !</p>
      </section>
      <form action="traitement_modifications_donnees.php" method="post"> 
        <fieldset>
          <legend class="legend">Vos Données Personnelles</legend>
          <p>
            <label for="raison_sociale"> RAISON SOCIALE :</label>
            <input type="text" id="raison_sociale" name="raison_sociale" placeholder="Raison sociale"
             value="<?= htmlspecialchars($utilisateur['raison_sociale'] ?? "") ?>">
          </p>
          <p>
            <label for="pseudo"> PSEUDONYME  :</label>
            <input type="text" required id="pseudo" name="pseudo" placeholder="Pseudonyme"
            value="<?= htmlspecialchars($utilisateur["pseudo"] ?? "") ?>">
          </p>
          <p>
            <label for="civilite">CIVILITÉ :</label>
            <select id="civilite" name="civilite">
              <option value="">Choisir</option>
              <option value="monsieur" <?= ($utilisateur["civilite"] ?? "") === "monsieur" ? "selected" : "" ?>>Monsieur</option>
              <option value="madame" <?= ($utilisateur["civilite"] ?? "") === "madame" ? "selected" : "" ?>>Madame</option>
            </select>
          </p>
            <label for="prenom">PRÉNOM:</label>
            <input type="text" required id="prenom" name="prenom" autocomplete="given-name" placeholder="Prénom"
             value="<?= htmlspecialchars($utilisateur['prenom'] ?? "") ?>">
          </p>
          <p>
            <label for="nom">NOM DE FAMILLE * :</label>
            <input type="text"required id="nom" name="nom" autocomplete="family-name" placeholder="Nom de Famille"
            value="<?= htmlspecialchars($utilisateur['nom'] ?? "") ?>">
          </p>
          <p>
            <label for="email">E-MAIL * :</label>
            <input type="email" required id="email" name="email" autocomplete="email" placeholder="xxxx@yyy.zzz"
             value="<?= htmlspecialchars($utilisateur['email'] ?? "") ?>">
          </p>
          <p>
            <label for="telephone">TÉLÉPHONE * :</label>
            <input type="tel" required id="telephone" name="telephone" pattern="[0-9 ]{10}" autocomplete="tel" placeholder="0000000000"
            value="<?= htmlspecialchars($utilisateur['telephone'] ?? "") ?>">
          </p>
        </fieldset>
        <fieldset>
          <legend class="legend">Adresse de Livraison</legend>
          <p>
            <label for="livraison_adresse_complement">COMPLÉMENT D'ADRESSE :</label>
            <input type="text" id="livraison_adresse_complement" name="livraison_adresse_complement" maxlength="120" placeholder="batiment - étage - n° appartement"
             value="<?= htmlspecialchars($utilisateur['livraison_adresse_complement'] ?? "") ?>">
          </p>
          <p>
            <label for="livraison_adresse">ADRESSE * :</label>
            <input type="text" required id="livraison_adresse" name="livraison_adresse" maxlength="120" placeholder="numero + voie + nom voie"
            value="<?= htmlspecialchars($utilisateur['livraison_adresse'] ?? "") ?>">
          </p>
          <p>
            <label for="livraison_code_postal">CODE POSTAL :</label>
            <input type="text" id="livraison_code_postal" name="livraison_code_postal"maxlength="5"pattern="[0-9]{5}" placeholder="code postal"
            value="<?= htmlspecialchars($utilisateur['livraison_code_postal'] ?? "") ?>">
          </p>
          <p>
            <label for="livraison_ville">VILLE * :</label>
            <input type="text" required id="livraison_ville" name="livraison_ville" placeholder="nom de la ville"
            value="<?= htmlspecialchars($utilisateur['commune_gironde'] ?? "") ?>">
          </p>
        </fieldset>
        <button class="bouton_modifications" type="submit">
            Envoyer vos modifications
        </button>
      </form>
    </main>
      <!-- liaison avec la page externe de Javascript -->
      <script src="javascript/menu_burger.js"></script> 
  </body>
</html>

<?php

session_start();
require_once "connexion.php";

if (!isset($_SESSION["id_utilisateur"])) {
    header("Location: formulaire_connexion_utilisateur.php");
    exit();
}

$id_utilisateur = (int) $_SESSION["id_utilisateur"];

$id_commande = filter_input(
    INPUT_GET,
    "id_commande",
    FILTER_VALIDATE_INT
);

if (!$id_commande) {
    header("Location: espace_utilisateur.php");
    exit();
}

/* Récupération de la commande choisie */
    $sql_commande = "
    SELECT
        commandes.*,
        commune_gironde.commune_gironde,
        menus.id_menu,
        menus.nb_personnes_minimum,
        menus.prix_par_personne_euros,
        utilisateurs.livraison_adresse,
        utilisateurs.livraison_adresse_complement,
        utilisateurs.livraison_code_postal

    FROM commandes
    LEFT JOIN commune_gironde
        ON commandes.id_commune = commune_gironde.id_commune
    INNER JOIN menus
        ON commandes.nom_menu = menus.nom_menu
    INNER JOIN utilisateurs
        ON commandes.id_utilisateur = utilisateurs.id_utilisateur
    WHERE commandes.id_commande = :id_commande
      AND commandes.id_utilisateur = :id_utilisateur
      AND commandes.id_statut_commande = 1 ";

$stmt_commande = $pdo->prepare($sql_commande);

$stmt_commande->execute([
    "id_commande" => $id_commande,
    "id_utilisateur" => $id_utilisateur
]);

$commande = $stmt_commande->fetch(PDO::FETCH_ASSOC);

if (!$commande) {
    header("Location: espace_utilisateur.php");
    exit();
}

$fraisLivraison =
    (float) $commande["frais_livraison_euros"];

/* Récupération des plats du menu */

$sql_plats = "
    SELECT
        plat.id_plat,
        plat.nom_plat
    FROM menus_plats
    INNER JOIN plat
        ON menus_plats.id_plat = plat.id_plat
    WHERE menus_plats.id_menu = :id_menu
    ORDER BY plat.id_plat ASC
";

$stmt_plats = $pdo->prepare($sql_plats);

$stmt_plats->execute([
    "id_menu" => (int) $commande["id_menu"]
]);

$plats = $stmt_plats->fetchAll(PDO::FETCH_ASSOC);

$nombre_plats = count($plats);

if ($nombre_plats < 4) {
    header("Location: espace_utilisateur.php");
    exit();
}

/* Les deux premiers plats sont les entrées */

$entree1 = $plats[0]["nom_plat"];
$entree2 = $plats[1]["nom_plat"];

/* Les deux derniers plats sont les desserts */

$dessert1 = $plats[$nombre_plats - 2]["nom_plat"];
$dessert2 = $plats[$nombre_plats - 1]["nom_plat"];

?>
<!doctype html>
<html lang="fr">
  <head>
    <!-- balises meta indispensables -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- liaison avec la page externe de css -->
    <link rel="stylesheet" href="css/styles.css"/>
    <link rel="stylesheet" href="css/menu_burger.css"/>
     <link rel="stylesheet" href="css/formulaire_modifications_commandes.css"/>
    <title>
      Vite & Gourmand - Formulaire de mofifications des commandes 
    </title>
  </head>
  <!-- body : Contenu de la page -->
  <body>
  <header class="header_conteneur">
      <section>
          <?php include "menu_burger.php"; ?>
      </section>
      <section>    
          <h1>Modifications des commandes</h1>
      </section> 
      <section class="logo">
        <img src="images/logo1.png" alt="Logo de Vite et Gourmand, la plate-forme pour manger vite et bien"/>
      </section>
    </header>
    <main>
      <section class="presentation">
      <p>Tant que votre commande n'a pas été acceptée par Vite et Gourmand, vous pouvez l'annuler ou la modifier. </p>
      <p>Une seule chose ne peut pas être modifiée : le type de menu  </p>
      <p>Il vous suffit de changer ce que vous voulez dans le formulaire ci-dessous et de nous le renvoyer  </p>
      <p>A bientôt !</p>
      </section>
      <form id="formulaire_commande" action="traitement_modifications_commandes.php" method="post"
        data-prix-par-personne="<?= htmlspecialchars($commande["prix_par_personne_euros"]) ?>"
        data-frais-livraison="<?= htmlspecialchars($fraisLivraison) ?>">
       <fieldset>
          <legend class="legend">VOTRE COMMANDE</legend>
          <input type="hidden" name="id_commande" value="<?= (int) $commande["id_commande"] ?>"
>         <p>
              <label for="date_livraison">DATE DE LIVRAISON *</label>
              <input type="date" required id="date_livraison" name="date_livraison" placeholder="-- / -- / 20--"
              value="<?=htmlspecialchars($commande['date_livraison'] ?? "")  ?>">
          </p>
          <p>
            <label for="id_horaire_livraison">TRANCHE HORAIRE DE LIVRAISON *</label>
            <select name="id_horaire_livraison" id="id_horaire_livraison" required>
              <option value="">Choisir</option>
              <option value="1" <?= ((int) $commande["id_horaire_livraison"] === 1 ? "selected" : "") ?>>de 11h à 13h</option>
              <option value="2" <?= ((int) $commande["id_horaire_livraison"] === 2 ? "selected" : "") ?>>de 13h à 15h</option>
              <option value="3" <?= ((int) $commande["id_horaire_livraison"] === 3 ? "selected" : "") ?>>de 18h à 20h</option>
              <option value="4" <?= ((int) $commande["id_horaire_livraison"] === 4 ? "selected" : "") ?>>de 20h à 22h</option>
              <option value="5" <?= ((int) $commande["id_horaire_livraison"] === 5 ? "selected" : "") ?>>de 22h à minuit</option>
            </select>
        </p> 
        <p>         
            <label for="livraison_adresse_complement">COMPLÉMENT D'ADRESSE :</label>
            <input type="text" id="livraison_adresse_complement" name="livraison_adresse_complement" maxlength="120" placeholder="batiment - étage - n° appartement"
            value="<?=htmlspecialchars($commande['livraison_adresse_complement'] ?? "")  ?>">
          </p>
          <p>
            <label for="livraison_adresse">ADRESSE * :</label>
            <input type="text" id="livraison_adresse" name="livraison_adresse" maxlength="120" placeholder="numero + voie + nom de la voie"
            value="<?= htmlspecialchars($commande["livraison_adresse"] ?? "", ENT_QUOTES, "UTF-8") ?>">
          </p>
          <p>
            <label for="livraison_code_postal">CODE POSTAL :</label>
            <input type="text" id="livraison_code_postal" name="livraison_code_postal" maxlength="5"pattern="[0-9]{5}" 
            autocomplete="code_postal" placeholder="00000"
            value="<?= htmlspecialchars($commande['livraison_code_postal'] ?? "", ENT_QUOTES, "UTF-8") ?>">
          </p>
          <p>
            <label for="livraison_commune">VILLE * :</label>
            <input type="text" id="livraison_commune" name="livraison_commune" placeholder="nom de la ville"
            value="<?= htmlspecialchars($commande['commune_gironde']) ?>" required>
          </p>
          <p>
            <label for="nombre_personnes">NOMBRE DE CONVIVES * :</label>
            <input type="number" min="<?= (int) $commande["nb_personnes_minimum"] ?>" 
            id="nombre_personnes" name="nombre_personnes"
            value="<?= (int) $commande["nombre_personnes"] ?>" required>
          </p>     
          <p>Entrées : </p>
          <label><?= htmlspecialchars($entree1) ?></label>
          <input type="number" name="nombre_entree1" min="0"
          value="<?= (int) ($commande["nombre_entree1"] ?? 0) ?>">  

          <label><?= htmlspecialchars($entree2) ?></label>
          <input type="number" name="nombre_entree2" min="0" 
          value="<?= (int) ($commande["nombre_entree2"] ?? 0) ?>">  
         
          <p>Desserts : </p>
          <label><?= htmlspecialchars($dessert1) ?></label>
          <input type="number" name="nombre_dessert1" min="0" 
          value="<?= (int) ($commande["nombre_dessert1"] ?? 0) ?>">  

          <label><?= htmlspecialchars($dessert2) ?></label>
          <input type="number" name="nombre_dessert2" min="0" 
         value="<?= (int) ($commande["nombre_dessert2"] ?? 0) ?>">       
        </p>
        </fieldset>
        <fieldset>
        <legend class="legend">LES PRIX </legend>
        <p>Prix initial du menu :
         <strong id="affichage_prix_initial">0,00 €</strong>
            </p>
            <p>
                Réduction (10 %) :
                <strong id="affichage_reduction">0,00 €</strong>
            </p>
            <p>
                Prix du menu hors frais de livraison :
                <strong id="affichage_prix_menu">0,00 €</strong>
            </p>
            <p>
                Frais de livraison :
                <strong id="affichage_frais_livraison">
                    <?= number_format(
                        (float) $fraisLivraison,
                        2,
                        ',',
                        ' '
                    ) ?> €
                </strong>
            </p>
            <p>
                Total à payer :
                <strong id="affichage_prix_total">
                    <?= number_format(
                        (float) $fraisLivraison,
                        2,
                        ',',
                        ' '
                    ) ?> €
                </strong>
            </p>
            </fieldset>
        <button type="submit" name="action" value="modifier">Enregistrer les modifications</button>
        <button type="submit" name="action" value="annuler">Annuler la commande</button>
      </form>
    </main>
      <!-- liaison avec la page externe de Javascript -->
      <script src="javascript/menu_burger.js"></script> 
      <script src="javascript/calcul_prix_commande.js"></script>
  </body>
</html>

s<?php
session_start();
require_once "connexion.php";

if (!isset($_SESSION['id_utilisateur'])) {
    header("Location: connexion.php");
    exit;
}
$id_utilisateur = $_SESSION['id_utilisateur'];

$stmt = $pdo->prepare("
    SELECT  nom, 
            prenom, 
            email, 
            telephone,
            livraison_adresse, 
            livraison_code_postal, 
            livraison_id_commune 
    FROM utilisateurs 
    WHERE id_utilisateur = ?");
$stmt->execute([$id_utilisateur]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$utilisateur) {
    die("Utilisateur introuvable.");
}

$menu = $_GET['menu'] ?? '';

$menus = [
    'menu_charentes' => [
        'id_menu' => 1,
        'nom' => 'Menu charentais',
        'prix_par_personne' => 50,
        'nb_personnes_minimum' => 4,
    ],
    
    'menu_delices' => [
         'id_menu' => 2,
        'nom' => 'Menu Délices',
        'prix_par_personne' => 40,
        'nb_personnes_minimum' => 4,
    ], 
    
    'menu_table_maraichere' => [
        'id_menu' => 3,
        'nom' => 'Menu Table maraîchère',
        'prix_par_personne' => 45,
        'nb_personnes_minimum' => 4,
    ],
      
  'menu_jardin_de_jose' => [
         'id_menu' => 4,
        'nom' => 'Menu Le Jardin de José',
        'prix_par_personne' => 45,
        'nb_personnes_minimum' => 4,
    ],

  'menu_mariage' => [
        'id_menu' => 5,
        'nom' => 'Menu Mariage',
        'prix_par_personne' => 80,
        'nb_personnes_minimum' => 12,
    ], 

   'menu_fiesta' => [
        'id_menu' => 6,
        'nom' => 'Menu Fiesta',
        'prix_par_personne' => 60,
        'nb_personnes_minimum' => 4,
    ],
 ];

$idMenu = $menus[$menu]['id_menu'] ?? 0;
$nomMenu = $menus[$menu]['nom'] ?? 'Menu inconnu';
$prixParPersonne = $menus[$menu]['prix_par_personne'] ?? 0;

$stmtPlats = $pdo->prepare("
    SELECT
        plat.id_plat,
        plat.nom_plat,
        plat.pret_materiel
    FROM plat
    INNER JOIN menus_plats
        ON plat.id_plat = menus_plats.id_plat
    WHERE menus_plats.id_menu = ?
    ORDER BY plat.id_plat ASC
");

$stmtPlats->execute([$idMenu]);
$platsDuMenu = $stmtPlats->fetchAll(PDO::FETCH_ASSOC);

if (count($platsDuMenu) !== 5) {
    die("Les plats de ce menu sont incomplets.");
}

$entree1 = $platsDuMenu[0]['nom_plat'];
$entree2 = $platsDuMenu[1]['nom_plat'];
$platPrincipal = $platsDuMenu[2]['nom_plat'];
$dessert1 = $platsDuMenu[3]['nom_plat'];
$dessert2 = $platsDuMenu[4]['nom_plat'];

$pretMaterielEntree1 = (int) $platsDuMenu[0]['pret_materiel'];
$pretMaterielEntree2 = (int) $platsDuMenu[1]['pret_materiel'];
$pretMaterielPlatPrincipal = (int) $platsDuMenu[2]['pret_materiel'];
$pretMaterielDessert1 = (int) $platsDuMenu[3]['pret_materiel'];
$pretMaterielDessert2 = (int) $platsDuMenu[4]['pret_materiel'];

$stmtCommande = $pdo->prepare("
SELECT
    commandes.id_commande,
    commandes.date_livraison,
    commandes.nom_menu,
    commandes.nombre_personnes,
    commandes.prix_total,
    commune_gironde.frais_livraison_euros AS frais_livraison_euros,
    statut_commande.libelle_statut AS statut_commande,
    utilisateurs.nom AS nom_client
FROM commandes
INNER JOIN utilisateurs 
    ON commandes.id_utilisateur = utilisateurs.id_utilisateur
INNER JOIN commune_gironde
    ON commandes.id_commune = commune_gironde.id_commune
INNER JOIN statut_commande
    ON commandes.id_statut_commande = statut_commande.id_statut_commande
WHERE commandes.id_utilisateur = ?
");

$stmtCommande->execute([$id_utilisateur]);
$commande = $stmtCommande->fetch(PDO::FETCH_ASSOC);

$id_utilisateur = $_SESSION['id_utilisateur'];

$requete = $pdo->prepare("
    SELECT u.*, c.commune_gironde
    FROM utilisateurs u
    LEFT JOIN commune_gironde c
        ON u.livraison_id_commune = c.id_commune
    WHERE u.id_utilisateur = ?
");

$requete->execute([$id_utilisateur]);
$utilisateur = $requete->fetch(PDO::FETCH_ASSOC);

$fraisLivraison = 0;

if (!empty($utilisateur['livraison_id_commune'])) {

    $requeteFrais = $pdo->prepare("
        SELECT frais_livraison_euros
        FROM commune_gironde
        WHERE id_commune = ?
    ");

    $requeteFrais->execute([
        $utilisateur['livraison_id_commune']
    ]);

    $commune = $requeteFrais->fetch(PDO::FETCH_ASSOC);

    if ($commune) {
        $fraisLivraison = $commune['frais_livraison_euros'];
    }
}
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
    <link rel="stylesheet" href="css/formulaire_commande.css"/>
    <title>
      Vite & Gourmand : Mangez vite et bien - Formulaire de commande
    </title>
  </head>
  <!-- body : Contenu de la page -->
  <body>
  <header class="header_conteneur">
      <section>
          <?php include "menu_burger.php"; ?>
      </section>
      <section>    
          <h1>Commande</h1>
      </section> 
      <section class="logo">
        <img src="images/logo1.png" alt="Logo de Vite et Gourmand, la plate-forme pour manger vite et bien"/>
      </section>
    </header>
    <main>
       <form
          id="formulaire_commande" action="traitement_commande.php" method="post"
          data-prix-par-personne="<?= htmlspecialchars((string) $prixParPersonne, ENT_QUOTES, 'UTF-8') ?>"
          data-frais-livraison="<?= htmlspecialchars((string) $fraisLivraison, ENT_QUOTES, 'UTF-8') ?>"
          data-pret-materiel-plat-principal="<?= $pretMaterielPlatPrincipal ?>">
          <fieldset>
          <legend class="legend">Vos Données Personnelles</legend>
          <p>
            <label for="prenom">PRÉNOM * :</label>
            <input type="text" required
            id="prenom" 
            name="prenom" 
            autocomplete="given-name" 
            placeholder="Prénom"
            value="<?= htmlspecialchars($utilisateur['prenom']) ?>">     
          </p>
          <p>
            <label for="nom">NOM DE FAMILLE * :</label>
            <input type="text" required
            id="nom" 
            name="nom" 
            autocomplete="family-name" 
            placeholder="Nom de Famille"
            value="<?= htmlspecialchars($utilisateur['nom']) ?>">
          </p>
          <p>
            <label for="email">E-MAIL * :</label>
            <input type="email" required
            id="email" 
            name="email" 
            autocomplete="email" 
            placeholder="xxxx@yyy.zzz"
            value="<?= htmlspecialchars($utilisateur['email']) ?>">
          </p>
          <p>
            <label for="telephone">TÉLÉPHONE * :</label>
            <input type="tel" required
            id="telephone" 
            name="telephone" pattern="[0-9 ]{10}" 
            autocomplete="tel" 
            placeholder="0000000000"
            value="<?= htmlspecialchars($utilisateur['telephone']) ?>">
          </p>
        </fieldset>
        <fieldset>
          <legend class="legend">Date et heure de Livraison</legend>
          <p>
            <label for="date_livraison">DATE DE LIVRAISON *</label>
            <input type="date" required id="date_livraison" name="date_livraison" placeholder="-- / -- / 20--"/>
          </p>
           <p>
            <label for="id_horaire_livraison"> Dans quelle tranche-horaire voulez vous être livré ? *</label>
            <select name="id_horaire_livraison" id="id_horaire_livraison" required>
            <option value="">Choisir</option>
            <option value="1">de 11h à 13h</option>       
            <option value="2">de 13h à 15h</option>
            <option value="3">de 18h à 20h</option> 
            <option value="4">de 20h à 22h</option>
            <option value="5">de 22h à minuit</option>
            </select>
        </p>
        </fieldset>
        <fieldset>
          <legend class="legend">Adresse de Livraison</legend>
          <p>
            <label for="livraison_adresse_complement">COMPLÉMENT D'ADRESSE :</label>
            <input type="text" 
            id="livraison_adresse_complement" 
            name="livraison_adresse_complement" maxlength="120" 
            placeholder="batiment - étage - n° appartement"/>
          </p>
          <p>
            <label for="livraison_adresse">ADRESSE * :</label>
            <input type="text" required
            name="livraison_adresse" maxlength="120" 
            placeholder="numero + voie + nom de la voie"
            value="<?= htmlspecialchars($utilisateur['livraison_adresse']) ?>">
          </p>
          <p>
            <label for="livraison_code_postal">CODE POSTAL :</label>
            <input type="text" 
            id="livraison_code_postal" 
            name="livraison_code_postal" maxlength="5"pattern="[0-9]{5}" 
            autocomplete="livraison_code_postal" 
            placeholder="00000"
            value="<?= htmlspecialchars($utilisateur['livraison_code_postal']) ?>">
          </p>
          <p>
            <label for="livraison_id_commune">VILLE * :</label>
            <input type="text"
            id="livraison_id_commune" 
            placeholder="nom de la ville"
            value="<?= htmlspecialchars($utilisateur['commune_gironde']) ?>">
          </p>
          <input type="hidden"
          name="id_commune"
          value="<?= htmlspecialchars($utilisateur['livraison_id_commune']) ?>">
        </fieldset>
        <fieldset>
            <legend class="legend">Nombre de convives</legend>
          <p>
            <label for="nombre_personnes">NOMBRE DE CONVIVES * :</label>
            <input type="number" min="<?= (int) $menus[$menu]['nb_personnes_minimum'] ?>" step="1" required
            id="nombre_personnes" name="nb_personnes" placeholder="----"/>         
          </p>
  
         <p> Minimum pour ce menu :
            <?= (int) $menus[$menu]['nb_personnes_minimum'] ?> personnes.
        </p>
      </fieldset>
      <fieldset>
            <legend class="legend">Menu choisi</legend>
          <p>
            <label for="nom_menu">MENU CHOISI * :</label>
            <input type="text" required
            id="nom_menu" 
            name="nom_menu" 
            placeholder="type de menu choisi"
            value="<?= htmlspecialchars($nomMenu) ?>"
            readonly>
            <input
            type="hidden"
            name="id_menu"
            value="<?= htmlspecialchars($idMenu) ?>">
          </p>
          <p>Entrées : </p>
          <label><?= htmlspecialchars($entree1) ?></label>
          <input type="number" id="nb_entree1" name="nb_entree1" class="quantite_plat" min="0" value="0"
          data-pret-materiel="<?= $pretMaterielEntree1 ?>">
          <label><?= htmlspecialchars($entree2) ?></label>
          <input type="number" id="nb_entree2" name="nb_entree2" class="quantite_plat" min="0" value="0"
          data-pret-materiel="<?= $pretMaterielEntree2 ?>">
          <p>Desserts :</p>
          <label><?= htmlspecialchars($dessert1) ?></label>
          <input type="number" id="nb_dessert1" name="nb_dessert1" class="quantite_plat" min="0" value="0"
          data-pret-materiel="<?= $pretMaterielDessert1 ?>">
          <label><?= htmlspecialchars($dessert2) ?></label>
          <input type="number" id="nb_dessert2" name="nb_dessert2" class="quantite_plat" min="0" value="0"
          data-pret-materiel="<?= $pretMaterielDessert2 ?>">     
          <p>
            Matériel à rendre : <strong id="affichage_pret_materiel">Non</strong>
          </p>
      </fieldset>
      <fieldset>
            <legend class="legend">Les prix</legend>
            <p>
                Prix initial du menu :
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
                <strong>
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
            <p>
                <label for="date_commande">Date de la commande :</label>
                <input type="date" id="date_commande" name="date_commande" value="<?= date('Y-m-d') ?>"readonly>
            </p>
        </fieldset>
        <button type="submit" name="OK" value="Envoyer">Envoyer ma commande</button>
      </form>
    </main>
      <!-- liaison avec la page externe de Javascript -->
      <script src="javascript/calcul_prix_commande.js"></script>
      <script src="javascript/pret_materiel_commande.js"></script>
      <script src="javascript/menu_burger.js"></script> 
  </body>
</html>

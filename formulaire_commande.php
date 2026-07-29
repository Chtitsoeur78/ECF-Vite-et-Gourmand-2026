<?php
session_start();
require_once "connexion.php";


ini_set('display_errors', 1);
error_reporting(E_ALL);

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
        'nom' => 'Menu Charentes',
        'prix_par_personne' => 50,
    ],
    
    'menu_delices' => [
        'nom' => 'Menu Délices',
        'prix_par_personne' => 40,
    ], 
    
 'menu_table_maraichere' => [
        'nom' => 'Table Maraichère',
        'prix_par_personne' => 45,
    ],
      
  'menu_jardin_de_jose' => [
        'nom' => 'Jardin de José',
        'prix_par_personne' => 45,
    ],

  'menu_mariage' => [
        'nom' => 'Mariage',
        'prix_par_personne' => 80,
    ], 

   'menu_fiesta' => [
        'nom' => 'Fiesta',
        'prix_par_personne' => 60,
    ],
 ];

$nomMenu = $menus[$menu]['nom'] ?? 'Menu inconnu';
$prixParPersonne = $menus[$menu]['prix_par_personne'] ?? 0;

$entree1 = '';
$entree2 = '';
$dessert1 = '';
$dessert2 = '';

if ($menu == 'menu_charentes') {

    $entree1 = 'Huitres de Marennes Oleron';
    $entree2 = 'Grattons charentais';
    $dessert1 = 'Galette charentaise';
    $dessert2 = 'Millas charentais';
}

if ($menu == 'menu_delices') {

    $entree1 = 'Salade niçoise';
    $entree2 = 'Quiche lorraine';
    $dessert1 = 'Iles flottante';
    $dessert2 = 'Clafoutis aux cerises';
}

if ($menu == 'menu_table_maraichere') {

    $entree1 = 'Salade de concombres au yaourt';
    $entree2 = 'Poires farcies au gorgonzola';
    $dessert1 = 'Pain perdu à la sauce caramel';
    $dessert2 = 'Buche citron a la praline';
}

if ($menu == 'menu_jardin_de_jose') {

    $entree1 = 'Salade de fenouil, menthe et orange';
    $entree2 = 'gaspacho';
    $dessert1 = 'Tarte au citron mode vegane';
    $dessert2 = 'Muffins aux fruits mode vegane';
}

if ($menu == 'menu_mariage') {

    $entree1 = 'Foie gras avec chutney de figue et pain d épice';
    $entree2 = 'Homard rôti à la citronelle';
    $dessert1 = 'Profiterolles au chocolat noir truffees aux airelles';
    $dessert2 = 'Pièce-montée de choux et nougatine';
}

if ($menu == 'menu_fiesta') {

    $entree1 = 'Toasts au fromage de chèvre à la poire';
    $entree2 = 'Roulés de jambon au fromage et herbes';
    $dessert1 = 'Riz souffle aux smarties';
    $dessert2 = 'Dôme au chocolat praline';
}

$stmtCommande = $pdo->prepare("
SELECT
    commandes.id_commande,
    commandes.date_livraison,
    commandes.nom_menu,
    commandes.nb_personnes,
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
      <form action="traitement_commande.php" method="post"> 
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
            <input type="date" required 
            id="date_livraison" 
            name="date_livraison" 
            placeholder="-- / -- / 20--"/>
          </p>
           <p>
            <label for="id_horaire_livraison"> Dans quelle tranche-horaire voulez vous être livré ? *</label>
            <select name="id_horaire_livraison" id="id_horaire_livraison" required>
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
            value="<?= htmlspecialchars($utilisateur['commune_gironde']) ?>" readonly>
          </p>
          <input type="hidden"
          name="id_commune"
          value="<?= htmlspecialchars($utilisateur['livraison_id_commune']) ?>">
        </fieldset>
        <fieldset>
            <legend class="legend">Nombre de convives</legend>
          <p>
            <label for="nb_personnes">NOMBRE DE CONVIVES * :</label>
            <input type="number" min="4" required
            id="nb_personnes" 
            name="nb_personnes"
            placeholder="----"/>
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
          </p>
          <p>
          <h3>Entrées</h3>
          <label><?= htmlspecialchars($entree1) ?></label>
          <input type="number" name="nb_entree1" min="0" value="0">
          <label><?= htmlspecialchars($entree2) ?></label>
          <input type="number" name="nb_entree2" min="0" value="0">
          
          <h3>Desserts</h3>
          <label><?= htmlspecialchars($dessert1) ?></label>
          <input type="number" name="nb_dessert1" min="0" value="0">
          <label><?= htmlspecialchars($dessert2) ?></label>
          <input type="number" name="nb_dessert2" min="0" value="0">          
          </p>
        </fieldset>
          
    <?php
$nbPersonnes = $_POST['nb_personnes'] ?? 0;
$prixParPersonne = $_POST['prix_par_personne'] ?? 0;

$prixMenu = $nbPersonnes * $prixParPersonne;

$fraisLivraison = $commande['frais_livraison_euros'] ?? 0;

$total = $prixMenu + $fraisLivraison;
?>
<fieldset>
            <legend class="legend">Les Prix </legend>
          <p>
    Prix du menu :
  <?= number_format((float)$prixMenu, 2, ',', ' ') ?> €
  </p>
  <p>
    Frais de livraison :
    <?= number_format((float)$fraisLivraison, 2, ',', ' ') ?>
  </p>
  <p>
    Total :
    <?= number_format(
        (float)$prixMenu +
        (float)$fraisLivraison,

        2,
        ',',
        ' '
    ) ?> €
     </p>
<p>
            <label for="date_commande">Date de la commande :</label>
            <input type="date" 
            id="date_commande" 
            name="date_commande" 
            placeholder="-- / -- / 20--"/>
            value="<?php echo date('Y-m-d'); ?>"/>
      </p>
      </p>
    <button type="submit" name="OK" value="Envoyer">Envoyer la commande</button>
      </form>
    </main>
      <!-- liaison avec la page externe de Javascript -->
      <script src="javascript/menu_burger.js"></script> 
  </body>
</html>

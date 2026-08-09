<?php
session_start();
require_once "connexion.php";

if(!isset($_SESSION["id_utilisateur"])) {
    header("Location: utilisateur.php");
    exit;
}

$id_utilisateur = $_SESSION["id_utilisateur"];

$requete_en_cours = $pdo->prepare("
    SELECT commandes.*, statut_commande.libelle_statut
    FROM commandes
    INNER JOIN statut_commande
        ON commandes.id_statut_commande = statut_commande.id_statut_commande
    WHERE id_utilisateur = ?
    AND statut_commande.libelle_statut NOT IN ('terminee' , 'annulee')
    ORDER BY commandes.date_livraison ASC 
");

$requete_en_cours->execute([$id_utilisateur]);
$commandes_en_cours = $requete_en_cours->fetchAll(PDO::FETCH_ASSOC);

$requete_terminees = $pdo->prepare("
    SELECT commandes.*, statut_commande.libelle_statut
    FROM commandes
    INNER JOIN statut_commande
        ON commandes.id_statut_commande = statut_commande.id_statut_commande
    WHERE commandes.id_utilisateur = ?
    AND statut_commande.libelle_statut = 'terminee'
");

$requete_terminees->execute([$id_utilisateur]);
$commandes_terminees = $requete_terminees->fetchAll(PDO::FETCH_ASSOC);

$requete = $pdo->prepare("
    SELECT 
        utilisateurs.*,
        commune_gironde.commune_gironde
    FROM utilisateurs
    LEFT JOIN commune_gironde
        ON utilisateurs.livraison_id_commune = commune_gironde.id_commune
    WHERE utilisateurs.id_utilisateur = ?
");

$requete->execute([$id_utilisateur]);
$utilisateur = $requete->fetch(PDO::FETCH_ASSOC);
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
    <link rel="stylesheet" href="css/espace_utilisateur.css"/>
    <link rel="stylesheet" href="css/footer.css"/>
    <title>
      Vite & Gourmand - Espace Utilisateur 
    </title>
  </head>
  <!-- body : Contenu de la page -->
<body>
    <header class="header_conteneur">
      <section>
          <?php include "menu_burger.php"; ?>
      </section>
      <section class="titre_header">    
          <h1>Espace Utilisateur</h1>
      </section> 
      <section class="logo">
        <img src="images/logo1.png" alt="Logo de Vite et Gourmand, la plate-forme pour manger vite et bien"/>
      </section>
    </header>
<main>
    <section class="espace_utilisateur">
        <section class="espace_utilisateur_contenu">
            <h3>Vos données personnelles</h3>
        <section class="donnees_personnelles">
            <p>Raison sociale : <?= htmlspecialchars($utilisateur["raison_sociale"] ?? "")?></p>
            <p>Pseudo :         <?= htmlspecialchars($utilisateur["pseudo"] ?? "")?></p>
            <p>Civilité :       <?= htmlspecialchars($utilisateur["civilite"] ?? "")?></p>
            <p>Prénom :         <?= htmlspecialchars($utilisateur["prenom"] ?? "")?></p>
            <p>Nom :             <?= htmlspecialchars($utilisateur["nom"] ?? "")?></p>
            <p>EMail : <?= htmlspecialchars($utilisateur["email"] ?? "") ?></p>
            <p>Complément d'adresse : <?= htmlspecialchars($utilisateur["livraison_adresse_complement"] ?? "")?></p>
            <p>Numéro + Nom de voie : <?= htmlspecialchars($utilisateur["livraison_adresse"] ?? "")?></p>
            <p>Code Postal : <?= htmlspecialchars($utilisateur["livraison_code_postal"] ?? "")?></p>
            <p>VILLE : <?= htmlspecialchars($utilisateur["commune_gironde"] ?? "")?></p>
            <a href="modifier_utilisateur.php" class="bouton_modifier">Modifier vos Données Personnelles </a>
        </section>
         <h3>Les Commandes en cours</h3>
        <section class="commandes_en_cours">
            <table>
            <thead>
                <tr>
                <th>Date Commande</th>
                <th>N° Commande </th>
                <th>Statut Commande</th>
                <th>Date livraison</th>
                <th>Type Menu</th>
                <th>Nb Convives</th>
                <th>Prix Menu</th>
                <th>Prix total</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($commandes_en_cours)): ?>
            <?php foreach($commandes_en_cours as $commande): ?>
                <tr>
                <td><?= htmlspecialchars($commande["date_commande"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["id_commande"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["libelle_statut"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["date_livraison"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["nom_menu"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["nb_personnes"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["prix_menu"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["prix_total"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
            <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
            </table>
       </section>
       <section>
        <h3>Les Commandes terminées</h3>
        <section class="commandes_terminees">
           <table>
            <thead>
                <tr>
                <th>Date Commande</th>
                <th>N° Commande </th>
                <th>Statut Commande</th>
                <th>Date livraison</th>
                <th>Type Menu</th>
                <th>Nb Convives</th>
                <th>Prix Menu</th>
                <th>Prix total </th>
                 <th>Donner son Avis </th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($commandes_terminees)): ?>
            <?php foreach($commandes_terminees as $commande): ?>
                <tr>
                <td><?= htmlspecialchars($commande["date_commande"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["id_commande"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["libelle_statut"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["date_livraison"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["nom_menu"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["nb_personnes"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["prix_menu"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["prix_total"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>      
                <td><a href="avis_client.php?id_commande=<?= urlencode($commande["id_commande"] ?? "") ?>"
        class="bouton_avis">Donner un avis</a></td>
                </tr>           
            <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
            </table>
        </section>
        </section>   
        </section>
 </main>
    <?php include "footer.php"; ?>  
    <!-- liaison avec la page externe de Javascript -->
<script src="javascript/menu_burger.js"></script>
</body>
</html>

 
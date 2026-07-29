<?php
require_once 'connexion.php';
$sql = "

    SELECT 
        commandes.id_commande,
        commandes.date_livraison,
        commune_gironde.commune_gironde AS ville_livraison,
        commandes.nom_menu,
        commandes.nb_personnes,
        commandes.prix_total,
        statut_commande.libelle_statut AS statut_commande,
        utilisateurs.nom AS nom_client
    
        FROM commandes
    
    INNER JOIN utilisateurs 
        ON commandes.id_utilisateur = utilisateurs.id_utilisateur
    INNER JOIN commune_gironde
        ON commandes.id_commune = commune_gironde.id_commune
    INNER JOIN statut_commande
        ON commandes.id_statut_commande = statut_commande.id_statut_commande
    
    WHERE statut_commande.libelle_statut NOT IN ('terminee', 'annulee')
    ORDER BY commandes.date_livraison ASC 
   ";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql_precision = "

    SELECT 
        commandes.id_commande,
        commandes.date_livraison,
        commandes.nom_menu,
        commandes.nb_personnes,
        commandes.nb_entree1,
        commandes.nb_entree2,
        commandes.nb_dessert1,
        commandes.nb_dessert2
        
        FROM commandes

        INNER JOIN statut_commande
        ON commandes.id_statut_commande = statut_commande.id_statut_commande

        WHERE statut_commande.libelle_statut NOT IN ('terminee', 'annulee')
        ORDER BY commandes.date_livraison ASC 
        ";

$stmt = $pdo->prepare($sql_precision);
$stmt->execute();
$precision_commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql_menus = "
    SELECT 
        menus.id_menus,
        menus.titre_menus,
        menus.nb_personnes_minimum,
        menus.prix_par_personne_euros,
        menus.description,
        plat.nom_plat AS nom_plat,
        regime.libelle_regime AS nom_regime
    FROM menus
    INNER JOIN plat
        ON menus.id_plat = plat.id_plat
    INNER JOIN regime
        ON menus.id_regime = regime.id_regime
    ";
$stmt = $pdo->prepare($sql_menus);
$stmt->execute();
$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="fr">
  <head>
    <!-- balises meta indispensables -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta
      name="description"
      content="Vite et Gourmand est une plate-forme de commande de repas utilisable dans la région de Bordeaux."/>
    <!-- liaison avec la feuille de style externe de CSS -->
    <link rel="stylesheet" href="css/styles.css"/>
    <link rel="stylesheet" href="css/menu_burger.css"/>
    <link rel="stylesheet" href="css/espace_administrateur.css"/>
    <!-- Titre de la Page -->
    <title>Vite et Gourmand - Espace Administrateur</title>
  </head>
  <body>
 <!-- header : l'en-tête de la page d'accueil -->
    <header class="header_conteneur">
      <section>
      <?php include "menu_burger.php"; ?>
      </section>
      <section>
        <h1>Espace Administrateur</h1>
      </section>
      <section class="logo">
        <img src="images/logo1.png" alt="Logo de Vite et Gourmand, la plate-forme pour manger vite et bien" >
      </section>
         </header>
        <?php if (isset($_SESSION['message_succes'])): ?> 
        <p class="message_succes"> <?= $_SESSION['message_succes']; ?> </p> 
        <?php unset($_SESSION['message_succes']); ?> <?php endif; ?>
    <main>
        <section class="espace_administrateur">
         <section class="creation_compte">
            <h2>Espace "Création des Comptes Employé"</h2>
        <p>
        <a href="formulaire_employe.php" class="bouton_employes">CRÉEZ VOS COMPTES "EMPLOYÉ"</a>
        </p>
        </section>
       <section class="commandes_en_cours">     
        <h2>Espace "Commandes en cours"</h2>
            <table border="1">
            <thead>
                <tr>
                <th>N° Commande</th>
                <th>Nom Client</th>
                <th>Date livraison</th>
                <th>Ville livraison</th>
                <th>Type Menu</th>
                <th>Nb convives</th>
                <th>Prix total</th>
                <th>Statut Commande</th>
                </tr>
            </thead>
            <tbody>          
            <?php if (!empty($commandes)): ?>
            <?php foreach($commandes as $commande): ?>
                <tr>
                <td><?= htmlspecialchars($commande["id_commande"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["nom_client"] ?? "", ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["date_livraison"] ?? "", ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["ville_livraison"] ?? "", ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["nom_menu"] ?? "", ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["nb_personnes"] ?? "", ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= number_format((float)($commande["prix_total"] ?? 0), 2, ',', ' ') ?> €</td>          
                <td>
                <?php $statut = trim($commande['statut_commande'] ?? ''); ?>
            <form method="POST" action="modifier_statut.php">
                <input type="hidden" name="id_commande"
                    value="<?= htmlspecialchars($commande['id_commande'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    <select name="statut_commande">
                    <option value="en attente d acceptation"
                <?= $statut === "en attente d acceptation" ? 'selected' : '' ?>>
                En attente d'acceptation
                    </option>
                    <option value="acceptee"
                <?= $statut === 'acceptee' ? 'selected' : '' ?>>
                Acceptée
                    </option>
                    <option value="en preparation"
                <?= $statut === 'en preparation' ? 'selected' : '' ?>>
                En préparation 
                    </option>
                    <option value="en cours de livraison"
                <?= $statut === 'en cours de livraison' ? 'selected' : '' ?>>
                En cours de livraison  
                    </option>
                    <option value="livree"
                <?= $statut === 'livree' ? 'selected' : '' ?>>
                Livrée
                    </option>
                    <option value="en attente de retour de materiel"
                <?= $statut === 'en attente de retour de materiel' ? 'selected' : '' ?>>
                En attente de retour du matériel
                    </option>
                    <option value="terminee"
                <?= $statut === 'terminee' ? 'selected' : '' ?>>
                Terminée
                    </option>
                    <option value="annulee"
                <?= $statut === 'annulee' ? 'selected' : '' ?>>
                Annulée
                    </option>
                </select>
            <button type="submit">Modifier</button>
        </form>
                </td>
                </tr>
            <?php endforeach; ?>
            <?php else: ?>
                <tr>
                <td colspan="8">Aucune commande en cours actuellement.</td>
                </tr>
            <?php endif; ?>
            </tbody>
            </table>
        </section>
         <section>       
            <h2>Espace "Commandes en cours : Précisions des menus"</h2>
        <section class="precisions_menus">
            <table border="1">
            <thead>
                <tr>
                <th>N° Commande</th>
                <th>Date livraison</th>
                <th>Type Menu</th>
                <th>Nb convives</th>
                <th>Nb Entrée n°1</th>
                <th>Nb Entrée n°2</th>
                <th>Nb Dessert n°1</th>
                <th>Nb Dessert n°2</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($commandes)): ?>
            <?php foreach($precision_commandes as $commande): ?>
                <tr>
                <td><?= htmlspecialchars($commande["id_commande"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["date_livraison"] ?? "", ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["nom_menu"] ?? "", ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["nb_personnes"] ?? "", ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["nb_entree1"] ?? "", ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["nb_entree2"] ?? "", ENT_QUOTES, 'UTF-8') ?></td>    
                <td><?= htmlspecialchars($commande["nb_dessert1"] ?? "", ENT_QUOTES, 'UTF-8') ?></td>     
                <td><?= htmlspecialchars($commande["nb_dessert2"] ?? "", ENT_QUOTES, 'UTF-8') ?></td>  
                </tr>
            <?php endforeach; ?>
            <?php else: ?>
                <tr>
                <td colspan="8">Aucune commande en cours actuellement.</td>
                </tr>
            <?php endif; ?>
            </tbody>
            </table>
        </section>
        <section>
            <h2>Espace "menus"</h2>
        <section class="menus">
            <table border="1">
            <thead>
                <tr>
                <th>ID</th>
                <th>Titre Menu</th>
                <th>Nb personnes minimum</th>
                <th>Prix par personne en euro</th>
                <th>Nom des plats</th>
                <th>Nom du régime</th>
                <th>Description</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($menus as $menu): ?>
                <tr>
                <td><?= htmlspecialchars($menu["id_menus"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($menu["titre_menus"] ?? "", ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($menu["nb_personnes_minimum"] ?? "", ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($menu["prix_par_personne_euros"] ?? "", ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($menu["nom_plat"] ?? "", ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($menu["nom_regime"] ?? "", ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($menu["description"] ?? "", ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            </table>
        </section>
            <h2>ESPACE "VALIDATION DES AVIS CLIENT"</h2>
           
        </section>
        </section>      
    </main>
    <!-- liaison avec la page externe de Javascript -->
<script src="javascript/menu_burger.js"></script>
</body>
</html>

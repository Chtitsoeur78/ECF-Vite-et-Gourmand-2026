<?php

session_start();

require_once 'connexion.php';
require_once 'connexion_mongodb.php'; 

$avis_en_attente = $collectionAvis->find(
    [
        "statut_avis" => "en_attente",
        "id_commande" => ["\$exists" => true],
        "id_utilisateur" => ["\$exists" => true]
    ],
    [
        "sort" => ["date_avis" => 1]
    ]
);

$avis_refuses = $collectionAvis->find(
    [
        "statut_avis" => "refuse",
        "id_commande" => ['$exists' => true],
        "id_utilisateur" => ['$exists' => true]
    ],
    [
        "sort" => ["date_avis" => -1]
    ]
)->toArray();

$requete_nom_client = $pdo->prepare("
    SELECT nom
    FROM utilisateurs
    WHERE id_utilisateur = :id_utilisateur
");

$sql = "

    SELECT 
        commandes.id_commande,
        commandes.date_livraison,
         horaires_livraison.tranche_horaire AS tranche_horaire,
        commune_gironde.commune_gironde AS ville_livraison,
        commandes.nom_menu,
        commandes.nombre_personnes,
        commandes.prix_total,
        commandes.id_statut_commande,
        commandes.pret_materiel,
        commandes.retour_materiel,
        statut_commande.libelle_statut AS statut_commande,
        utilisateurs.nom AS nom_client
    
        FROM commandes
    INNER JOIN horaires_livraison
        ON commandes.id_horaire_livraison = horaires_livraison.id_horaire_livraison
    INNER JOIN utilisateurs 
        ON commandes.id_utilisateur = utilisateurs.id_utilisateur
    INNER JOIN commune_gironde
        ON commandes.id_commune = commune_gironde.id_commune
    INNER JOIN statut_commande
        ON commandes.id_statut_commande = statut_commande.id_statut_commande
    
    WHERE statut_commande.id_statut_commande NOT IN (7, 8)
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
        commandes.nombre_personnes,
        commandes.nombre_entree1,
        commandes.nombre_entree2,
        commandes.nombre_dessert1,
        commandes.nombre_dessert2
        
        FROM commandes

        INNER JOIN statut_commande
        ON commandes.id_statut_commande = statut_commande.id_statut_commande

        WHERE statut_commande.id_statut_commande NOT IN (7, 8)
        ORDER BY commandes.date_livraison ASC 
        ";

$stmt = $pdo->prepare($sql_precision);
$stmt->execute();
$precision_commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql_menus = "
    SELECT
        menus.id_menu,
        menus.nom_menu,
        menus.nb_personnes_minimum,
        menus.prix_par_personne_euros,
        menus.description_menu,
        regime.libelle_regime AS nom_regime,
        theme.libelle_theme,
        GROUP_CONCAT(
            plat.nom_plat
            ORDER BY menus_plats.ordre_plat
            SEPARATOR '|||'
        ) AS noms_plats

    FROM menus

    INNER JOIN menus_plats
        ON menus.id_menu = menus_plats.id_menu

    INNER JOIN plat
        ON menus_plats.id_plat = plat.id_plat

    INNER JOIN regime
        ON menus.id_regime = regime.id_regime

    INNER JOIN theme
        ON menus.id_theme = theme.id_theme

    GROUP BY
        menus.id_menu,
        menus.nom_menu,
        menus.nb_personnes_minimum,
        menus.prix_par_personne_euros,
        menus.description_menu,
        regime.libelle_regime,
        theme.libelle_theme
    ORDER BY menus.id_menu
"; 
$stmt = $pdo->prepare($sql_menus);
$stmt->execute();
$menus = $stmt->fetchAll(PDO::FETCH_ASSOC); ?>

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
    <link rel="stylesheet" href="css/menu_burger.css">
    <link rel="stylesheet" href="css/espace_employe.css?v2">
    <!-- Titre de la Page -->
    <title>Vite et Gourmand - Espace Employé</title>
  </head>
  <body>
 <!-- header : l'en-tête de la page d'accueil -->
    <header class="header_conteneur">
      <section>
      <?php include "menu_burger.php"; ?>
      </section>
      <section>
        <h1>Espace Employé</h1>
      </section>
      <section class="logo">
        <img src="images/logo1.png" alt="Logo de Vite et Gourmand, la plate-forme pour manger vite et bien" >
      </section>
    <div class="zone_connexion">
            <p>
        Bonjour <?= htmlspecialchars($_SESSION["prenom"]) ?> 
            </p>
        <a href="deconnexion.php" class="deconnexion">Se déconnecter</a>
        </div>
    </header>
    <main>
        <section class="espace_employe">
            <h2>Espace "Commandes en cours"</h2>
        <section class="commandes_en_cours">
            <table border="1">
            <thead>
                <tr>
                <th>N° Commande</th>
                <th>Nom Client</th>
                <th>Date livraison</th>
                <th>Tranche horaire de livraison</th>
                <th>Ville livraison</th>
                <th>Type Menu</th>
                <th>Nb convives</th>
                <th>Prix total</th>
                <th>Retour matériel</th>
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
                <td><?= htmlspecialchars($commande["tranche_horaire"] ?? "", ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["ville_livraison"] ?? "", ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["nom_menu"] ?? "", ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["nombre_personnes"] ?? "", ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= number_format((float)($commande["prix_total"] ?? 0), 2, ',', ' ') ?> €</td>          
                <td><?php if ((int) ($commande['pret_materiel'] ?? 0) === 0): ?> Aucun matériel à rendre
                    <?php elseif ((int) ($commande['retour_materiel'] ?? 0) === 1): ?> Matériel rendu
                    <?php else: ?>A rendre
                    <?php endif; ?>
                </td>
                <td>
            <form method="POST" action="modifier_statut.php">
                <input type="hidden" name="id_commande"
                    value="<?= htmlspecialchars($commande['id_commande'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                <select name="id_statut_commande">
                    <option value="1"
                    <?= (int)($commande['id_statut_commande'] ?? 0) === 1 ? 'selected' : '' ?>>
                    En attente d'acceptation
                    </option>
                    <option value="2"
                    <?= (int)($commande['id_statut_commande'] ?? 0) === 2 ? 'selected' : '' ?>>
                    Acceptée
                    </option>
                    <option value="3"
                    <?= (int)($commande['id_statut_commande'] ?? 0) === 3 ? 'selected' : '' ?>>
                    En préparation 
                    </option>
                    <option value="4"
                    <?= (int)($commande['id_statut_commande'] ?? 0) === 4 ? 'selected' : '' ?>>
                    En cours de livraison 
                    </option>
                    <option value="5"
                    <?= (int)($commande['id_statut_commande'] ?? 0) === 5 ? 'selected' : '' ?>>
                    Livrée
                    </option>
                    <option value="6"
                    <?= (int)($commande['id_statut_commande'] ?? 0) === 6 ? 'selected' : '' ?>>
                    En attente du retour du matériel 
                    </option>
                    <option value="7"
                    <?= (int)($commande['id_statut_commande'] ?? 0) === 7 ? 'selected' : '' ?>>
                    Terminée 
                    </option>
                    <option value="8"
                    <?= (int)($commande['id_statut_commande'] ?? 0) === 8 ? 'selected' : '' ?>>
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
                <td colspan="9">Aucune commande en cours actuellement.</td>
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
            <?php if (!empty($precision_commandes)): ?>
            <?php foreach($precision_commandes as $commande): ?>
                <tr>
                <td><?= htmlspecialchars($commande["id_commande"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["date_livraison"] ?? "", ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["nom_menu"] ?? "", ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["nombre_personnes"] ?? "", ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["nombre_entree1"] ?? "", ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["nombre_entree2"] ?? "", ENT_QUOTES, 'UTF-8') ?></td>    
                <td><?= htmlspecialchars($commande["nombre_dessert1"] ?? "", ENT_QUOTES, 'UTF-8') ?></td>     
                <td><?= htmlspecialchars($commande["nombre_dessert2"] ?? "", ENT_QUOTES, 'UTF-8') ?></td>  
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
        </section>
        <section class="menus">
        <h2>Espace « menus »</h2>
        <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre du menu</th>
                <th>Nombre de personnes minimum</th>
                <th>Prix par personne</th>
                <th>Entrée 1</th>
                <th>Entrée 2</th>
                <th>Plat principal</th>
                <th>Dessert 1</th>
                <th>Dessert 2</th>
                <th>Régime</th>
                <th>Thème</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($menus as $menu): ?>
        <?php
            $plats = explode("|||", $menu["noms_plats"] ?? ""); ?>
            <tr>
                <td><?= htmlspecialchars($menu["id_menu"] ?? "", ENT_QUOTES, "UTF-8") ?></td>
                <td><?= htmlspecialchars($menu["nom_menu"] ?? "", ENT_QUOTES, "UTF-8") ?></td>
                <td><?= htmlspecialchars($menu["nb_personnes_minimum"] ?? "", ENT_QUOTES, "UTF-8") ?></td>
                <td><?= htmlspecialchars($menu["prix_par_personne_euros"] ?? "", ENT_QUOTES, "UTF-8") ?> €</td>
                <td><?= htmlspecialchars($plats[0] ?? "", ENT_QUOTES, "UTF-8") ?></td>
                <td><?= htmlspecialchars($plats[1] ?? "", ENT_QUOTES, "UTF-8") ?></td>
                <td><?= htmlspecialchars($plats[2] ?? "", ENT_QUOTES, "UTF-8") ?></td>
                <td><?= htmlspecialchars($plats[3] ?? "", ENT_QUOTES, "UTF-8") ?></td>
                <td><?= htmlspecialchars($plats[4] ?? "", ENT_QUOTES, "UTF-8") ?></td>
                <td><?= htmlspecialchars($menu["nom_regime"] ?? "", ENT_QUOTES, "UTF-8") ?></td>
                <td><?= htmlspecialchars($menu["libelle_theme"] ?? "", ENT_QUOTES, "UTF-8") ?></td>
                <td><?= htmlspecialchars($menu["description_menu"] ?? "", ENT_QUOTES, "UTF-8") ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
 <section class="actions_menus" aria-labelledby="titre_actions_menus">
            <h3 id="titre_actions_menus" class="sr_only"> Actions de gestion des menus</h3>
        <form action="modifier_menu.php" method="get">
        <label for="menu_a_modifier">Menu à modifier :</label>
        <select name="id_menu" id="menu_a_modifier" required>
            <option value="">Sélectionnez un menu</option>
            <?php foreach ($menus as $menu): ?>
                <option value="<?= (int) $menu["id_menu"] ?>"><?= htmlspecialchars($menu["nom_menu"] ?? "", ENT_QUOTES,"UTF-8") ?></option>
            <?php endforeach; ?></select>
        <button type="submit" class="bouton_menu">Modifier le menu</button></form>
        <a href="ajouter_plat.php" class="bouton_menu">Ajouter un nouveau plat</a>
        <a href="ajouter_menu.php" class="bouton_menu">Ajouter un menu</a>
    </section>
     <h2>ESPACE "VALIDATION DES AVIS CLIENT"</h2>
    <section class="validation_avis">  
            <table border="1">
            <thead>
                <tr>
                <th>N° Commande</th>
                <th>Nom Client</th>
                <th>Pseudo</th>
                <th>Note</th>
                <th>Commentaire</th>
                <th>Validation</th>
               </tr>
            </thead>
            <tbody>
                <?php foreach ($avis_en_attente as $avis): ?>
                <?php
                    $requete_nom_client->execute([
                     ":id_utilisateur" => (int) $avis["id_utilisateur"]]);
                    $nom_client = $requete_nom_client->fetchColumn();?>
            <tr>
                <td><?= (int) $avis["id_commande"] ?></td>
                <td><?= htmlspecialchars($nom_client ?: "Utilisateur inconnu", ENT_QUOTES, "UTF-8") ?></td>
                <td><?= htmlspecialchars((string) $avis["pseudo"], ENT_QUOTES, "UTF-8") ?></td>
                <td><?= (int) $avis["note"] ?>/5</td>
                <td><?= htmlspecialchars((string) $avis["commentaire"], ENT_QUOTES, "UTF-8") ?></td>
                <td>
                <form
                    action="traitement_validation_avis.php" method="post">
                    <input type="hidden" name="id_avis" 
                    value="<?= htmlspecialchars((string) $avis["_id"], ENT_QUOTES, "UTF-8") ?>">

                    <button type="submit" name="action" value="valider">Valider</button>
                    <button type="submit" name="action" value="refuser">Refuser</button>
                </form>
                </td>    
            </tr>
             <?php endforeach; ?>
            </tbody>
            </table>
            </section>
        <h2>ESPACE "AVIS REFUSES"</h2>
        <section class="avis_refuses">  
            <table border="1">
            <thead>
                <tr>
                <th>N° Commande</th>
                <th>Nom Client</th>
                <th>Pseudo</th>
                <th>Note</th>
                <th>Commentaire</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($avis_refuses)): ?>
                <?php foreach ($avis_refuses as $avis): ?>
                <?php $requete_nom_client->execute([":id_utilisateur" => (int) $avis["id_utilisateur"]]);
                    $nom_client = $requete_nom_client->fetchColumn();?>
            <tr>
                <td><?= (int) $avis["id_commande"] ?></td>
                <td><?= htmlspecialchars($nom_client ?: "Utilisateur inconnu", ENT_QUOTES, "UTF-8") ?></td>
                <td><?= htmlspecialchars((string) $avis["pseudo"], ENT_QUOTES, "UTF-8") ?></td>
                <td><?= (int) $avis["note"] ?>/5</td>
                <td><?= htmlspecialchars((string) $avis["commentaire"], ENT_QUOTES, "UTF-8") ?></td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
                <tr>
                <td colspan="5">Aucun avis refusé.</td>
                </tr>
            <?php endif; ?>
            </tbody>
            </table>
     </section>      
    </main>
    <!-- liaison avec la page externe de Javascript -->
<script src="javascript/menu_burger.js"></script>
</body>
</html>

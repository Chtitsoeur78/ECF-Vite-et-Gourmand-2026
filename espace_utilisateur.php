<?php

session_start();
require_once __DIR__ . "/connexion.php";
require_once  __DIR__ . "/connexion_mongodb.php";

if (!isset($_SESSION["id_utilisateur"])) {
    header("Location: formulaire_connexion_utilisateur.php");
    exit;
}

$id_utilisateur = (int) $_SESSION["id_utilisateur"];

$commandes_avec_avis = [];

$avis_utilisateur = $collectionAvis->find(
    [
        "id_utilisateur" => $id_utilisateur
    ],
    [
        "projection" => [
            "id_commande" => 1
        ]
    ]
);

foreach ($avis_utilisateur as $avis) {
    $commandes_avec_avis[] = (int) $avis["id_commande"];
}

$requete_en_cours = $pdo->prepare("
    SELECT commandes.*, statut_commande.libelle_statut
    FROM commandes
    INNER JOIN statut_commande
        ON commandes.id_statut_commande = statut_commande.id_statut_commande
    WHERE commandes.id_utilisateur = ?
      AND commandes.id_statut_commande NOT IN (7, 8)
    ORDER BY commandes.date_livraison ASC
");

$requete_en_cours->execute([$id_utilisateur]);
$commandes_en_cours = $requete_en_cours->fetchAll(PDO::FETCH_ASSOC);

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

$requete_terminees = $pdo->prepare("
    SELECT commandes.*, statut_commande.libelle_statut
    FROM commandes
    INNER JOIN statut_commande
        ON commandes.id_statut_commande = statut_commande.id_statut_commande
    WHERE commandes.id_utilisateur = ?
    AND commandes.id_statut_commande = 7
    ORDER BY commandes.date_livraison DESC
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
    <link rel="stylesheet" href="css/espace_utilisateur.css">
    <link rel="stylesheet" href="css/footer.css">
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
    <?php if (($_GET["avis"] ?? "") === "envoye"): ?>
        <p class="message_succes">
             Votre avis a bien été envoyé et attend sa validation.
                </p>
    <?php endif; ?>
    <section class="espace_utilisateur">  
        <h3>Vos données personnelles</h3>         
        <section class="donnees_personnelles">
            <p>Raison sociale : <?= htmlspecialchars($utilisateur["raison_sociale"] ?? "")?></p>
            <p>Pseudo :         <?= htmlspecialchars($utilisateur["pseudo"] ?? "")?></p>
            <p>Civilité :       <?= htmlspecialchars($utilisateur["civilite"] ?? "")?></p>
            <p>Prénom :         <?= htmlspecialchars($utilisateur["prenom"] ?? "")?></p>
            <p>Nom :             <?= htmlspecialchars($utilisateur["nom"] ?? "")?></p>
            <p>Email : <?= htmlspecialchars($utilisateur["email"] ?? "") ?></p>
            <p>Téléphone : <?= htmlspecialchars($utilisateur["telephone"] ?? "") ?></p>
            <p>Complément d'adresse : <?= htmlspecialchars($utilisateur["livraison_adresse_complement"] ?? "")?></p>
            <p>Numéro + Nom de voie : <?= htmlspecialchars($utilisateur["livraison_adresse"] ?? "")?></p>
            <p>Code Postal : <?= htmlspecialchars($utilisateur["livraison_code_postal"] ?? "")?></p>
            <p>VILLE : <?= htmlspecialchars($utilisateur["commune_gironde"] ?? "")?></p>
            <a href="formulaire_modifications_donnees_personnelles.php" class="bouton_modifier">Modifier vos Données Personnelles </a>
        </section>
        <h3>Les Commandes en cours</h3>
        <section class="commandes_en_cours">
            <table>
            <thead>
                <tr>
                <th>Actions</th>
                <th>Date Commande</th>
                <th>N° Commande </th>
                <th>Statut Commande</th>
                <th>Date livraison</th>
                <th>Type Menu</th>
                <th>Nb Convives</th>
                <th>Prix Menu</th>
                <th>Prix total</th>
               
                <th>Nb Entrée 1</th>
                <th>Nb Entrée 2</th>
                <th>Nb Dessert 1</th>
                <th>Nb Dessert 2</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($commandes_en_cours)): ?>
            <?php foreach($commandes_en_cours as $commande): ?>
                <tr>
                <td class="action_commande"><?php if ((int) $commande["id_statut_commande"] === 1): ?>
                    <a href="formulaire_modifications_commandes.php?id_commande=<?= (int) $commande["id_commande"] ?>"
                     class="bouton_modifier">Annuler / modifier</a>
                <?php endif; ?></td>
                <td><?= htmlspecialchars($commande["date_commande"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["id_commande"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["libelle_statut"] ?? '', ENT_QUOTES, 'UTF-8') ?>
                <?php if (!empty($commande["date_modification_statut"])): ?>
                <br>
            <small>
                <?= date("d/m/Y à H:i", strtotime($commande["date_modification_statut"])) ?>
            </small>
            <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($commande["date_livraison"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["nom_menu"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["nombre_personnes"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["prix_menu"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["prix_total"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["nombre_entree1"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["nombre_entree2"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["nombre_dessert1"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["nombre_dessert2"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
            <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
            </table>
        </section> 
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
                <td><?= htmlspecialchars($commande["libelle_statut"] ?? '', ENT_QUOTES, 'UTF-8') ?>
                <?php if (!empty($commande["date_modification_statut"])): ?>
                <br>
                    <small>
                        <?= date("d/m/Y à H:i", strtotime($commande["date_modification_statut"])) ?>
                    </small>
                <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($commande["date_livraison"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["nom_menu"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["nombre_personnes"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["prix_menu"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($commande["prix_total"] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td>
                    <?php 
                    if (in_array(
                    (int) $commande["id_commande"],
                        $commandes_avec_avis,
                        true
                    )): ?>
                    <span class="avis_envoye">
                    Avis envoyé
                    </span>
                    <?php else: ?>
                    <a class="bouton_avis" href="formulaire_avis.php?id_commande=<?= (int) $commande["id_commande"] ?>">
                    Donner mon avis
                    </a>
                    <?php endif; ?>
                </td>
            </tr>          
            <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
            </table>
        </section>
        </section>
 </main>
    <?php include "footer_sans.php"; ?>  
    <!-- liaison avec la page externe de Javascript -->
<script src="javascript/menu_burger.js"></script>
</body>
</html>

 
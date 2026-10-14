<?php

require_once __DIR__ . "/vendor/autoload.php";

// Chargement des informations confidentielles
$configurationMongoDB = require __DIR__ . "/configuration_mongodb_atlas.php";

try {
    // Connexion à MongoDB Atlas
    $clientMongoDB = new MongoDB\Client(
        $configurationMongoDB["adresse"],
        [
            "username" => $configurationMongoDB["utilisateur"],
            "password" => $configurationMongoDB["mot_de_passe"]
        ]
    );

    // Sélection de la base de données
    $baseMongoDB = $clientMongoDB->selectDatabase(
        $configurationMongoDB["base_de_donnees"]
    );

    // Sélection de la collection des avis
    $collectionAvis = $baseMongoDB->selectCollection("avis_clients");

} catch (Throwable $e) {
    error_log(
        "Erreur de connexion MongoDB Atlas : " . $e->getMessage()
    );

    exit("Connexion à MongoDB impossible.");
}
<?php
session_start();
header("Content-Type: application/json; charset=utf-8");

require_once "connexion.php";

if (!isset($_SESSION["id_utilisateur"])) {
    http_response_code(401);

    echo json_encode([
        "succes" => false,
        "message" => "Utilisateur non connecté."
    ]);

    exit();
}

$commune = trim($_GET["commune"] ?? "");

if ($commune === "") {
    http_response_code(400);

    echo json_encode([
        "succes" => false,
        "message" => "Le nom de la commune est obligatoire."
    ]);

    exit();
}

$sql = "
    SELECT
        id_commune,
        commune_gironde,
        frais_livraison_euros
    FROM commune_gironde
    WHERE commune_gironde = :commune
    LIMIT 1
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    "commune" => $commune
]);

$resultat = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$resultat) {
    http_response_code(404);

    echo json_encode([
        "succes" => false,
        "message" => "Commune introuvable en Gironde."
    ]);

    exit();
}

echo json_encode([
    "succes" => true,
    "id_commune" => (int) $resultat["id_commune"],
    "commune" => $resultat["commune_gironde"],
    "frais_livraison" => (float) $resultat["frais_livraison_euros"]
]);






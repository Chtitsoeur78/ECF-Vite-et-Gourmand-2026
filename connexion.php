<?php
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/erreurs.log');

require_once __DIR__ . '/configuration_bdd.php';

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        $options
    );

} catch (PDOException $e) {
    error_log($e->getMessage());
    die("Une erreur de connexion est survenue.");
}






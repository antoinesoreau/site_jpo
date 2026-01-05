<?php

function getDBConnection() {
    // 1. Paramètres de configuration
    $host = 'localhost';
    $db   = 'nom_de_votre_base';
    $user = 'root';
    $pass = ''; // Sur MAMP, c'est souvent 'root'
    $charset = 'utf8mb4';

    // 2. Construction du DSN (Data Source Name)
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    // 3. Options de configuration de PDO
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lance une exception en cas d'erreur
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Retourne les données sous forme de tableau associatif
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Utilise les vraies requêtes préparées
    ];

    try {
        // 4. Création de l'instance PDO
        return new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        // En cas d'erreur, on affiche un message (à personnaliser en production)
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
}

// --- Exemple d'utilisation ---

try {
    $pdo = getDBConnection();
    echo "Connexion réussie !";
} catch (Exception $e) {
    echo "Erreur lors de la connexion : " . $e->getMessage();
}

$bdd = getDBConnection();
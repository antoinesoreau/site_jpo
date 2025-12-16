<?php 

/**
 * Établit et retourne un objet de connexion PDO.
 * * @return PDO|null L'objet PDO si la connexion réussit, ou null en cas d'échec.
 */
function connexionSQL () {
    // --- 1. Paramètres de connexion ---
    $host = 'localhost'; // Habituellement 'localhost' ou l'IP de votre serveur de BDD
    $db_name = 'jpo'; // Remplacez par le nom de votre base de données
    $user = 'root'; // Remplacez par votre nom d'utilisateur de BDD
    $password = ''; // Remplacez par votre mot de passe de BDD
    $charset = 'utf8mb4'; // Encodage recommandé

    // Data Source Name (DSN)
    $dsn = "mysql:host=$host;dbname=$db_name;charset=$charset";
    
    // Options PDO
    $options = [
        // Mode d'erreur : Lève une exception pour chaque erreur SQL
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        // Mode de récupération par défaut : Les résultats sont retournés sous forme de tableau associatif
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // Désactive l'émulation des requêtes préparées pour une meilleure sécurité et performance
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    // --- 2. Tentative de connexion ---
    try {
        // Création de l'objet PDO
        $pdo = new PDO($dsn, $user, $password, $options);
        
        // Retourne l'objet de connexion
        return $pdo;

    } catch (PDOException $e) {
        // En cas d'erreur de connexion, affiche le message d'erreur
        // Note : En production, il est préférable de ne PAS afficher $e->getMessage() 
        // pour des raisons de sécurité. Vous devriez loguer l'erreur à la place.
        echo "Erreur de connexion : " . $e->getMessage();
        
        // Retourne null si la connexion échoue
        return null;
    }
}

// --- Exemple d'utilisation ---
// $db = connexionSQL();

// if ($db) {
//     echo "Connexion à la base de données réussie !";
    
//     // Vous pouvez maintenant utiliser $db pour exécuter des requêtes :
//     // $stmt = $db->query('SELECT * FROM ma_table');
//     // $results = $stmt->fetchAll();
// } else {
//     echo "Échec de la connexion à la base de données.";
// }

?>
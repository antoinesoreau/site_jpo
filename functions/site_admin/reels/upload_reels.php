<?php
// upload_reel.php

// 1. Configuration de la réponse (API JSON)
header('Content-Type: application/json; charset=utf-8');

// 2. Connexion à la Base de Données (à adapter avec tes identifiants)
$host = 'localhost';
$db   = 'jpo';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur de connexion BDD']);
    exit;
}

// 3. Vérification de la méthode
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée. Utilisez POST.']);
    exit;
}

// 4. Configuration des fichiers
$targetDir = "uploads/reels/"; // Dossier où stocker les vidéos
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0755, true);
}

// Formats autorisés (mp4 est standard pour les reels, mov pour iphone)
$allowedTypes = ['video/mp4', 'video/quicktime', 'video/webm'];
$maxSize = 100 * 1024 * 1024; // 100MB max

try {
    // --- Validation des Entrées ---

    // A. Vérification du fichier
    if (!isset($_FILES['video_file']) || $_FILES['video_file']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("Aucun fichier vidéo valide envoyé.");
    }

    $fileTmpPath = $_FILES['video_file']['tmp_name'];
    $fileName    = $_FILES['video_file']['name'];
    $fileSize    = $_FILES['video_file']['size'];
    $fileType    = mime_content_type($fileTmpPath); // Vérification MIME réelle

    // Vérification Format
    if (!in_array($fileType, $allowedTypes)) {
        throw new Exception("Format vidéo non supporté. Utilisez MP4 ou MOV.");
    }

    // Vérification Taille
    if ($fileSize > $maxSize) {
        throw new Exception("Le fichier est trop volumineux (Max 100Mo).");
    }

    // B. Traitement du contenu WYSIWYG et Titre
    $title = trim($_POST['title'] ?? '');
    // Le contenu WYSIWYG contient du HTML. On le garde, mais on pourra le nettoyer à l'affichage (HTMLPurifier).
    // Pour l'instant, on s'assure juste qu'il n'est pas vide si c'est obligatoire.
    $wysiwygContent = $_POST['description'] ?? ''; 

    if (empty($title)) {
        throw new Exception("Le titre est obligatoire.");
    }

    // --- Traitement de l'Upload ---

    // Générer un nom unique pour éviter les conflits (ex: reel_65a4b...mp4)
    $extension = pathinfo($fileName, PATHINFO_EXTENSION);
    $newFileName = 'reel_' . bin2hex(random_bytes(8)) . '.' . $extension;
    $destPath = $targetDir . $newFileName;

    if (!move_uploaded_file($fileTmpPath, $destPath)) {
        throw new Exception("Erreur lors de l'enregistrement du fichier sur le serveur.");
    }

    // --- Enregistrement en BDD ---
    
    // Simuler un ID utilisateur (dans un vrai cas, récupérer via $_SESSION['user_id'])
    $userId = 1; 

    $sql = "INSERT INTO reels (user_id, title, description, video_path) VALUES (:uid, :title, :desc, :path)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':uid'   => $userId,
        ':title' => $title,
        ':desc'  => $wysiwygContent, // On stocke le HTML brut ici
        ':path'  => $destPath
    ]);

    // Réponse succès
    echo json_encode([
        'success' => true,
        'message' => 'Reel mis en ligne avec succès !',
        'reel_id' => $pdo->lastInsertId(),
        'path'    => $destPath
    ]);

} catch (Exception $e) {
    http_response_code(400); // Bad Request
    // En cas d'erreur et si un fichier a été uploadé mais pas BDD, on pourrait le supprimer ici
    echo json_encode(['error' => $e->getMessage()]);
}
?>
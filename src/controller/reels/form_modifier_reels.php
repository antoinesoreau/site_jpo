<?php
// edit_reel.php

// 1. Database Connection
$host = 'localhost';
$db   = 'jpo';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
    die("DB Connection failed");
}

// 2. Get the ID from the URL (e.g., ?id=5)
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: No ID provided.");
}

$reelId = (int)$_GET['id'];

// 3. Fetch current Reel data
$stmt = $pdo->prepare("SELECT * FROM reels WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $reelId]);
$reel = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reel) {
    die("Error: Reel not found.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le réel #<?php echo $reelId; ?></title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; }
        input[type="text"], textarea { width: 100%; padding: 8px; }
    </style>
</head>
<body>

    <h1>Modifier le réel</h1>
    
    <form action="modifier_reels_backend.php" method="POST">
        
        <input type="hidden" name="reel_id" value="<?php echo $reel['id']; ?>">

        <div class="form-group">
            <label>Titre:</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($reel['title']); ?>" required>
        </div>

        <div class="form-group">
            <label>Description (WYSIWYG):</label>
            <textarea name="description" rows="10"><?php echo htmlspecialchars($reel['description']); ?></textarea>
        </div>

        <button type="submit">Sauvegarder</button>
        <a href="javascript:history.back()">Annuler</a>
    </form>

</body>
</html>
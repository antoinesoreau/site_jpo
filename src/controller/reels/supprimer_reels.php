<?php
// supprimer_reels.php

// 1. Database Connection
$host = 'localhost';
$db   = 'jpo';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// 2. Get the ID (from GET or POST)
$id = $_POST['reel_id'] ?? $_GET['id'] ?? null;

if (!$id) {
    die("Error: No Reel ID provided.");
}

// 3. Logic Handler
$message = "";

// --- STEP A: PROCESS DELETION (POST Request) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
    
    try {
        // 1. Get the video path BEFORE deleting the row
        $stmt = $pdo->prepare("SELECT video_path FROM reels WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $reel = $stmt->fetch();

        if ($reel) {
            // 2. Delete the physical file from the server
            $filePath = $reel['video_path'];
            if (file_exists($filePath)) {
                if (unlink($filePath)) {
                    // File deleted successfully
                } else {
                    // Could not delete file (maybe permission issue), but we continue to DB delete
                    error_log("Warning: Could not delete file: $filePath");
                }
            }

            // 3. Delete the record from Database
            // Note: If you have Foreign Keys (likes, comments), they will be deleted automatically 
            // if you set ON DELETE CASCADE in SQL. Otherwise, delete them here first.
            $delStmt = $pdo->prepare("DELETE FROM reels WHERE id = :id");
            $delStmt->execute([':id' => $id]);

            $message = "<div style='color: green; font-weight: bold;'>✅ Reel deleted successfully!</div>";
            $hideForm = true; // Flag to hide the form after success
        } else {
            $message = "<div style='color: red;'>❌ Reel not found (already deleted?).</div>";
        }

    } catch (Exception $e) {
        $message = "<div style='color: red;'>Error: " . $e->getMessage() . "</div>";
    }
}

// --- STEP B: SHOW CONFIRMATION (GET Request or Default) ---
// We fetch the title just to show a nice confirmation message
if (!isset($hideForm)) {
    $stmt = $pdo->prepare("SELECT title FROM reels WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $reelInfo = $stmt->fetch();
    
    if (!$reelInfo) {
        die("Reel ID #$id does not exist.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Reel</title>
    <style>
        body { font-family: sans-serif; padding: 50px; text-align: center; }
        .box { border: 1px solid #ccc; padding: 20px; max-width: 400px; margin: 0 auto; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .btn { padding: 10px 20px; text-decoration: none; border-radius: 5px; cursor: pointer; border: none; font-size: 16px; margin: 5px;}
        .btn-danger { background-color: #e74c3c; color: white; }
        .btn-secondary { background-color: #95a5a6; color: white; }
        .btn-danger:hover { background-color: #c0392b; }
    </style>
</head>
<body>

    <div class="box">
        <?php if (!empty($message)): ?>
            <?php echo $message; ?>
            <br>
            <a href="index.php" class="btn btn-secondary">Return to List</a>
        
        <?php elseif (isset($reelInfo)): ?>
            <h2>⚠️ Attention</h2>
            <p>Êtes vous sûr de vouloir supprimer le réel:</p>
            <h3>"<?php echo htmlspecialchars($reelInfo['title']); ?>"</h3>
            <p>Ca supprimera toute donnée et contenu présent sur les serveurs.</p>

            <form action="supprimer_reels.php" method="POST">
                <input type="hidden" name="reel_id" value="<?php echo $id; ?>">
                <input type="hidden" name="confirm" value="yes">
                
                <button type="submit" class="btn btn-danger">Oui, supprimez !</button>
                <a href="javascript:history.back()" class="btn btn-secondary">Non, annulez.</a>
            </form>
        <?php endif; ?>
    </div>

</body>
</html>
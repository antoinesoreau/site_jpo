<?php
// update_reel_backend.php

header('Content-Type: application/json; charset=utf-8');

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
    echo json_encode(['error' => 'DB Connection failed']);
    exit;
}

// 2. Check Method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'POST method required']);
    exit;
}

try {
    // 3. Validation
    if (empty($_POST['reel_id']) || empty($_POST['title'])) {
        throw new Exception("ID and Title are required.");
    }

    $id = (int)$_POST['reel_id'];
    $title = trim($_POST['title']);
    $description = $_POST['description'] ?? '';

    // 4. SQL Update
    // We do NOT update video_path or user_id here, only text content.
    $sql = "UPDATE reels 
            SET title = :title, 
                description = :desc 
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':title' => $title,
        ':desc'  => $description,
        ':id'    => $id
    ]);

    // Check if any row was actually modified
    if ($stmt->rowCount() === 0) {
        // This might happen if the ID doesn't exist OR if the data hasn't changed
        // We can do a check, but usually, we just return success.
    }

    echo json_encode([
        'success' => true, 
        'message' => 'Reel updated successfully.',
        'id' => $id
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
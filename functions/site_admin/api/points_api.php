<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/db_config.php';

// Fonction pour récupérer un visiteur par QR code
function getVisiteurByQR($qr_code) {
    global $dtb;
    $stmt = $dtb->prepare("SELECT * FROM visiteurs WHERE qr_code = ?");
    $stmt->execute([$qr_code]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fonction pour récupérer un membre par code
function getMembreByCode($code_membre) {
    global $dtb;
    $stmt = $dtb->prepare("
        SELECT m.*, s.nom_stand, s.points_disponibles 
        FROM membres m 
        LEFT JOIN stands s ON m.id_stand = s.id_stand 
        WHERE m.code_membre = ?
    ");
    $stmt->execute([$code_membre]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fonction pour ajouter des points
function ajouterPoints($id_visiteur, $id_membre, $id_stand, $points, $commentaire = '') {
    global $dtb;
    
    // Ajouter la transaction
    $stmt = $dtb->prepare("
        INSERT INTO transactions_points (id_visiteur, id_membre, id_stand, points, commentaire) 
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$id_visiteur, $id_membre, $id_stand, $points, $commentaire]);
    
    // Mettre à jour le total du visiteur
    $stmt = $dtb->prepare("
        UPDATE visiteurs 
        SET total_points = total_points + ? 
        WHERE id_visiteur = ?
    ");
    $stmt->execute([$points, $id_visiteur]);
    
    return $dtb->lastInsertId();
}

// Fonction pour modifier des points
function modifierPoints($id_transaction, $nouveaux_points) {
    global $dtb;
    
    // Récupérer l'ancienne transaction
    $stmt = $dtb->prepare("SELECT * FROM transactions_points WHERE id_transaction = ?");
    $stmt->execute([$id_transaction]);
    $transaction = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$transaction) {
        return false;
    }
    
    $difference = $nouveaux_points - $transaction['points'];
    
    // Mettre à jour la transaction
    $stmt = $dtb->prepare("
        UPDATE transactions_points 
        SET points = ?, date_modification = NOW(), modifie = 1 
        WHERE id_transaction = ?
    ");
    $stmt->execute([$nouveaux_points, $id_transaction]);
    
    // Mettre à jour le total du visiteur
    $stmt = $dtb->prepare("
        UPDATE visiteurs 
        SET total_points = total_points + ? 
        WHERE id_visiteur = ?
    ");
    $stmt->execute([$difference, $transaction['id_visiteur']]);
    
    return true;
}

// Fonction pour récupérer l'historique d'un membre
function getHistoriqueMembre($id_membre, $limit = 10) {
    global $dtb;
    $stmt = $dtb->prepare("
        SELECT t.*, v.nom, v.prenom, v.qr_code, s.nom_stand
        FROM transactions_points t
        JOIN visiteurs v ON t.id_visiteur = v.id_visiteur
        JOIN stands s ON t.id_stand = s.id_stand
        WHERE t.id_membre = ?
        ORDER BY t.date_ajout DESC
        LIMIT ?
    ");
    $stmt->execute([$id_membre, $limit]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Router API
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'get_visiteur':
            // GET: /api/points_api.php?action=get_visiteur&qr_code=QR001
            $qr_code = $_GET['qr_code'] ?? '';
            $visiteur = getVisiteurByQR($qr_code);
            echo json_encode(['success' => true, 'data' => $visiteur]);
            break;
            
        case 'get_membre':
            // GET: /api/points_api.php?action=get_membre&code=MEMBRE001
            $code = $_GET['code'] ?? '';
            $membre = getMembreByCode($code);
            echo json_encode(['success' => true, 'data' => $membre]);
            break;
            
        case 'ajouter_points':
            // POST: /api/points_api.php?action=ajouter_points
            $data = json_decode(file_get_contents('php://input'), true);
            $id_transaction = ajouterPoints(
                $data['id_visiteur'],
                $data['id_membre'],
                $data['id_stand'],
                $data['points'],
                $data['commentaire'] ?? ''
            );
            echo json_encode(['success' => true, 'id_transaction' => $id_transaction]);
            break;
            
        case 'modifier_points':
            // POST: /api/points_api.php?action=modifier_points
            $data = json_decode(file_get_contents('php://input'), true);
            $success = modifierPoints($data['id_transaction'], $data['nouveaux_points']);
            echo json_encode(['success' => $success]);
            break;
            
        case 'historique':
            // GET: /api/points_api.php?action=historique&id_membre=1
            $id_membre = $_GET['id_membre'] ?? 0;
            $limit = $_GET['limit'] ?? 10;
            $historique = getHistoriqueMembre($id_membre, $limit);
            echo json_encode(['success' => true, 'data' => $historique]);
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Action non reconnue']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>

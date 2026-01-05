<?php
// Configuration de la base de données pour le système de points
$username = '';
$pass = '';
$host = '';
$name = 'jpo_mmi';

try {
    $dtb = new PDO('mysql:host='.$host.':3306;dbname='.$name.';charset=utf8mb4', $username, $pass);
    $dtb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}
?>

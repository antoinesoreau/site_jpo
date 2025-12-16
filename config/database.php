<?php
$username = '';
$pass = '';
$host = '';
$name = 'jpo_mmi';


try {
    $dtb = new PDO('mysql:host='.$host.':3306;dbname='.$name.';charset=utf8mb4', $username, $pass);
} catch (exception $e) {
    die('Erreur : ' . $e->getMessage());
}
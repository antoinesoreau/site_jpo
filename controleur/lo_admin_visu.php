<?php

 // Il me faut le nom du fichier qui se connecte à la bdd $pdo = require('.php');
 
 if(isset($_POST['update-profil'])){
    $cible=$_POST['cible'] ?? 0;
    $parent=$_POST['parent'] ?? 0;
    $presse=$_POST['presse'] ?? 0;
    $entreprise=$_POST['entreprise'] ?? 0;
   
    $sql = "UPDATE profils SET ";
    $sql .= "cible=$cible, WHERE id=1";
    $sql .= "parent=$parent, WHERE id=1";
    $sql .= "presse=$presse, WHERE id=1";
    $sql .= "entreprise=$entreprise WHERE id=1";
    $statement = $pdo->query($sql);
    
    if($statement) {
        echo "<script> window.location.href='lo_admin.php'; </script>";
    }
 }

 if(isset($_POST['update-pole'])){
    $crea=$_POST['crea'] ?? 0;
    $dev=$_POST['dev'] ?? 0;
    $strat=$_POST['strat'] ?? 0;
   
    $sql = "UPDATE pôles SET ";
    $sql .= "crea=$crea, WHERE id=1";
    $sql .= "dev=$dev, WHERE id=1";
    $sql .= "strat=$strat, WHERE id=1";
    $statement = $pdo->query($sql);
    
    if($statement) {
        echo "<script> window.location.href='lo_admin.php'; </script>";
    }
 }

 ?>
<!-- récup de post ajout/modif/suppr de catégorie-->
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>

<?php

// Il me faut le nom du fichier qui se connecte à la bdd $pdo = require ('.php');

// Définir l'entête pour indiquer au navigateur que la réponse est au format JSON
header('Content-Type: application/json');

// --- 1. Récupérer les données brutes de la requête POST (le corps JSON) ---
$json_data = file_get_contents('php://input');

// --- 2. Décoder la chaîne JSON en objet ou tableau PHP ---
// Le second argument 'true' permet d'obtenir un tableau associatif (plus facile à manipuler)
$data = json_decode($json_data, true);




// Ajout de catégorie (profil) il faut changer les noms des champs et de la table en fonction de la bdd

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
      $public_nom = htmlspecialchars($_POST['public_nom']);
      $public_status = htmlspecialchars($_POST['public_status']);

      // Préparer et exécuter la requête d'insertion
      $stmt = $pdo->prepare('INSERT INTO profils (public_nom, public_status) VALUES (:public_nom, :public_status)');
      $stmt->bindParam(':public_nom', $public_nom);
      $stmt->bindParam(':public_status', $public_status);

      if ($stmt->execute()) {
          echo '<p>Ce profil a été ajouté avec succès !</p>';
      } else {
          echo '<p>Une erreur est survenue lors de l\'ajout de ce profil.</p>';
      }
}

// Ajout de catégorie (pôle)

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
      $pole_nom = htmlspecialchars($_POST['pole_nom']);
      $pole_status = htmlspecialchars($_POST['pole_status']);

      // Préparer et exécuter la requête d'insertion
      $stmt = $pdo->prepare('INSERT INTO pôles (pole_nom, pole_status) VALUES (:pole_nom, :pole_status)');
      $stmt->bindParam(':pole_nom', $pole_nom);
      $stmt->bindParam(':pole_status', $pole_status);

      if ($stmt->execute()) {
          echo '<p>Ce pôle a été ajouté avec succès !</p>';
      } else {
          echo '<p>Une erreur est survenue lors de l\'ajout de ce pôle.</p>';
      }
}

// Ajout de catégorie (rubrique)

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
      $rubrique_nom = htmlspecialchars($_POST['rubrique_nom']);
      $rubrique_status = htmlspecialchars($_POST['rubrique_status']);

      // Préparer et exécuter la requête d'insertion
      $stmt = $pdo->prepare('INSERT INTO rubriques (rubrique_nom, rubrique_status) VALUES (:rubrique_nom, :rubrique_status)');
      $stmt->bindParam(':rubrique_nom', $rubrique_nom);
      $stmt->bindParam(':rubrique_status', $rubrique_status);

      if ($stmt->execute()) {
          echo '<p>Cette rubrique a été ajouté avec succès !</p>';
      } else {
          echo '<p>Une erreur est survenue lors de l\'ajout de cette rubrique.</p>';
      }
}

?>
<script>

// jsonCategorie[0] = profil/public
// jsonCategorie[1] = pôle
// jsonCategorie[2] = rubrique

//Version 1
function Modif_Categorie(){
		jsonCategorie[0].public[0].nom=document.getElementById("nom_etud").value;
        jsonCategorie[0].public[0].status=document.getElementById("status_etud").value;

        jsonCategorie[0].public[1].nom=document.getElementById("nom_parent").value;
        jsonCategorie[0].public[1].status=document.getElementById("status_parent").value;

        jsonCategorie[0].public[2].nom=document.getElementById("nom_presse").value;
        jsonCategorie[0].public[2].status=document.getElementById("status_presse").value;

        jsonCategorie[0].public[3].nom=document.getElementById("nom_entreprise").value;
        jsonCategorie[0].public[3].status=document.getElementById("status_entreprise").value;

        jsonCategorie[1].pole[0].nom=document.getElementById("nom_crea").value;
        jsonCategorie[1].pole[0].status=document.getElementById("status_crea").value;

        jsonCategorie[1].pole[1].nom=document.getElementById("nom_dev").value;
        jsonCategorie[1].pole[1].status=document.getElementById("status_dev").value;

        jsonCategorie[1].pole[2].nom=document.getElementById("nom_strat").value;
        jsonCategorie[1].pole[2].status=document.getElementById("status_strat").value;

        jsonCategorie[2].rubrique[0].nom=document.getElementById("nom_?").value;
        jsonCategorie[2].rubrique[0].status=document.getElementById("status_?").value;

}

//Version 2
function Modif_Categorie(){

SET data = JSON_MODIFY(data, '$.nom', $nom);

SET data = JSON_MODIFY(data, '$.status', $status);

}

</script>
<!-- récup de post ajout/modif/suppr de catégorie-->
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>

<?php

// Il me faut le nom du fichier qui se connecte à la bdd $pdo = require ('.php');
// sachant qu'à part les rubriques (je crois) les catégories sont prédéfinies
// faire juste un truc afficher/cacher ? regarder dans le body pour la version alternative

// Ajout de catégorie (profil)

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
</head>
<body>

<!-- j'ai repris le code de ce que j'avais fait avec d'AmdinLTE va falloir update les class -->

<!-- Visibilité de catégorie (profil) -->

<form method="post" action="lo_admin_visu.php">
    <div class="">

        <div class="form-check">
            <input class="form-check-input" name="cible" type="checkbox" value="1" id="flexCheckCible" 
            <?php if ($public_data['cible']) { echo "checked"; } ?>>
                <label class="form-check-label" for="flexCheckCible1">
                    Lycéen/Étudiant
                </label>
        </div>

        <div class="form-check">
            <input class="form-check-input" name="parent" type="checkbox" value="1" id="flexCheckParent"
                <?php if ($public_data['parent']) { echo "checked"; } ?>>
                <label class="form-check-label" for="flexCheckParent">
                     Parent
                </label>
        </div>

        <div class="form-check">
            <input class="form-check-input" name="presse" type="checkbox" value="1" id="flexCheckPresse"
            <?php if ($pole_data['presse']) { echo "checked"; } ?>>
                <label class="form-check-label" for="flexCheckPresse">
                    Presse
                </label>
        </div>

        <div class="form-check">
            <input class="form-check-input" name="entreprise" type="checkbox" value="1" id="flexCheckEntreprise"
            <?php if ($pole_data['entreprise']) { echo "checked"; } ?>>
                <label class="form-check-label" for="flexCheckEntreprise">
                    Entreprise
                </label>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary btn-sm" name="update-profil">
                <i class="bi bi-save"></i> Enregistrer
            </button>
        </div>
    </div>
</form>

<!-- Visibilité de catégorie (pôle) -->


<form method="post" action="lo_admin_visu.php">
    <div class="">

        <div class="form-check">
            <input class="form-check-input" name="crea" type="checkbox" value="1" id="flexCheckCrea" 
            <?php if ($pole_data['crea']) { echo "checked"; } ?>>
                <label class="form-check-label" for="flexCheckCrea">
                    Création Numérique
                </label>
        </div>

        <div class="form-check">
            <input class="form-check-input" name="dev" type="checkbox" value="1" id="flexCheckDev"
                <?php if ($pole_data['dev']) { echo "checked"; } ?>>
                <label class="form-check-label" for="flexCheckDev">
                     Développement Web et Dispositifs Intéractifs
                </label>
        </div>

        <div class="form-check">
            <input class="form-check-input" name="strat" type="checkbox" value="1" id="flexCheckStrat"
            <?php if ($pole_data['strat']) { echo "checked"; } ?>>
                <label class="form-check-label" for="flexCheckStrat">
                    Stratégie de Communication
                </label>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary btn-sm" name="update-pole">
                <i class="bi bi-save"></i> Enregistrer
            </button>
        </div>
    </div>
</form>

<!-- Modifier une catégorie (profil) -->

<!-- Modifier une catégorie (pôle) -->

<!-- Modifier une catégorie (rubrique) -->



<!-- "Supprimmer" une catégorie (profil) -->

<!-- "Supprimmer" une catégorie (pôle) -->

<!-- "Supprimmer" une catégorie (rubrique) -->

</body>
</html>
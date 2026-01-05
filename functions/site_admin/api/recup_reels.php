<?php 

    // mode affichage
    if (isset($_GET["action"] == "lecture")) {
        $nom_table_reels = "Reels";
        $requete = "SELECT * FROM ? ";
        $execution = [$nom_table_reels];

        // limiter le nombre d'element afficher
        if (isset($_GET["limit"])) {
            $limit = $_GET["limit"];

            $requete .= " LIMIT ?";
            $execution[] = $limit;
        }

    }

    // mode edition
    if (isset($_GET["action"] == "edition") && isset($_GET["id"])) {
        $id = $_GET["id"];

        $requete = "UPDATE "
    }
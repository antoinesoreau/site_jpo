<?php
    // API de recupération des donnée ou d'écriture pour la table commentaire 
        // possibilité d'en faire une api pour tout la gestions de la base de donnée 

    // header('Access-Control-Allow-Origin: *');  // donne acces a tout les site meme externe d'utiliser l'api
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');

    require "../../function/connexion_sql.php";

    $db = connexionSQL();
    $requete  = "";
    $execution = [];

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            
        if (isset($_GET["action"])) {
            // demande des données
            if ($_GET["action"] == "lecture") {
                // requete de base
                $requete  = "SELECT id_commentaire as Id,
                                    commentaire_date_publication as Date,
                                    commentaire_nom as Nom,
                                    commentaire_texte as Texte,
                                    commentaire_note as Note, 
                                    commentaire_status as Status
                                    From commentaire
                                    ";
                // JE GARDE MAIS C PAS UTILE POUR CE QUI EST DEMANDAIS POUR CETTE PARTIE
                //  // choix de la table
                // if (isset($_GET["table"])) {
                //     $table = $_GET["table"];
                //     $requete .= $table;
                    // jointure pour table commentaire
                    // meme si inutile dans la partie modération
                    // if ($table == "commentaire") {
                    //     $requete .= " JOIN public ON id_public = id_public
                    //                  JOIN rubrique ON id_rubrique = id_rubrique";
                    // }
                    // definir le nombre maximum d'element renvoyer

                    // PERMET DE RECUPERER LES DATES SUPERIEURS A LA DATE ENTRE
                    if (isset($_GET["date"])) {
                        $date = $_GET["date"];
                        // 1. Conversion en secondes (division entière)
                        $secondes = (int)($date / 1000) + 3600; // pour eviter le decalage horraire
                        // 2. Formatage
                        $dateStamp = date("Y-m-d H:i:s", $secondes);
                        // echo $dateStamp; // debug

                        $requete .= " WHERE commentaire_date_publication > ?" ;
                        $execution[] = $dateStamp; 
                    }


                    // PERMET D'AJOUTER UNE LIMITE AU NOMBRE D'ELEMENT ENVOYER (pratique si un affichage limiter)
                    if (isset($_GET["limit"])) {
                        $limit = intval($_GET["limit"]);
                        $requete .= " LIMIT ?";
                        $execution[] = $limit;
                    }


                    

                    // echo $requete; // debug

                    $reponse = $db->prepare($requete);
                    $reponse->execute($execution); 
                    $formatted_results = $reponse->fetchAll(PDO::FETCH_NUM); // pour construire le tableau a envoyer
                }
                // edition des données
                // verfication des prerecis
                if(
                    ($_GET["action"] == "edition") &&
                    (isset($_GET["status"])) &&
                    (isset($_GET["id"]))
                    ) {

                    $status = $_GET["status"];
                    $id = $_GET["id"];
                    $requete = "UPDATE commentaire SET commentaire_status = ? WHERE id_commentaire = ?";     
    
                    // echo $requete; // debug

                    $reponse = $db->prepare($requete);
                    $success = $reponse->execute([$status, $id]);     

                    if ($success) {
                        $formatted_results = ["success" => true, "message" => "Statut mis à jour."];
                    } else {
                        $formatted_results = ["success" => false, "message" => "Échec de la mise à jour."];
                    }
                }
                
                    // Retourner les résultats traités au format JSON
                    header('Content-Type: application/json');
                    echo json_encode($formatted_results); // Utilisation des résultats traités
            }
            else {
                // gestion des erreurs
                // ?
            }
        }
    

    



    
    



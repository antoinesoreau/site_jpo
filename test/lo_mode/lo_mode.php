<?php
    // header('Access-Control-Allow-Origin: *'); 
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
                $requete  = "SELECT id_commentaire as Id,
                                    commentaire_nom as Nom,
                                    commentaire_texte as Texte,
                                    commentaire_note as Note, 
                                    commentaire_status as Status
                                    From commentaire
                                    ";
                
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
                    if (isset($_GET["limit"])) {
                        $limit = intval($_GET["limit"]);
                        $requete .= " LIMIT ?";
                        $execution[] = $limit;
                    }
                    // echo $requete; // debug
                    $reponse = $db->prepare($requete);
                    $reponse->execute($execution); 
                    $formatted_results = $reponse->fetchAll(PDO::FETCH_NUM);
                }
                // edition des données
                if(
                    ($_GET["action"] == "edition") &&
                    (isset($_GET["modification"])) &&
                    (isset($_GET["id"]))
                    ) {
                    $modification = $_GET["modification"];
                    $id = $_GET["id"];
                    $requete = "UPDATE commentaire SET commentaire_status = ? WHERE ?";     
    
                    // echo $requete; // debug
                    $reponse = $db->prepare($requete);
                    $success = $reponse->execute([$modification, $id]);     

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
            }
        }
    

    



    
    



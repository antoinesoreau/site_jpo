
let div_tableau = document.getElementById("tableau");
div_tableau.innerHTML = ""; // vide la div tableau

const table = document.createElement("table");
div_tableau.appendChild(table)

// date en secondes
let maintenant = Date.now();

// genere le tableau pour la premiere fois
async function InitialisationTableau () {

    // mise ajour de la date
    updateDate()
    
    let intitules = ["Date de publication", "Nom", "Commentaire", "Note", "Status (Valide/Invalide)", "Action"];

    // crée la premiere ligne du tableau
    let tr = document.createElement("tr");
    table.appendChild(tr);
    intitules.forEach((intitule) => {
        let th = document.createElement("th");
        tr.appendChild(th);
        th.textContent = intitule;
    })
    
    // requete en mode lecture
    const requeteURL = "http://localhost/JPO/site_jpo/test/lo_mode/lo_mode.php?action=lecture";
    let data = [];

    try {
        const response = await fetch(requeteURL);
        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }
        // data est sous la forme 
        // 0 : id
        // 1 : date
        // 2 : nom
        // 3 : commentaire
        // 4 : note
        // 5 : status
        data = await response.json();
    }
    catch (error) {
        console.error("Erreur lors de la récupération de l'API :", error);
        return ; // stopper la fonction
    }
    console.log(data);

    Rows(data);

    
}

// mise ajour de l'heure
function updateDate() {
    maintenant = Date.now();
}

// mise ajour affichage avec nouvelle ligne
async function miseAjour() {


    
    // requete en mode lecture
    const requeteURL = `http://localhost/JPO/site_jpo/test/lo_mode/lo_mode.php?action=lecture&date=${maintenant}`;
    console.log(requeteURL); // debug
    let data = [];

    // mise ajour de la date
    updateDate()
    // console.log(date, heure); // debug

    try {
        const response = await fetch(requeteURL);
        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }
        // data est sous la forme 
        // 0 : id
        // 1 : date
        // 2 : nom
        // 3 : commentaire
        // 4 : note
        // 5 : status
        data = await response.json();
    }
    catch (error) {
        console.error("Erreur lors de la récupération de l'API :", error);
        return ; // stopper la fonction
    }
    console.log(data);

    Rows(data);
}

// creer les ligne pour chaque ligne de donnée renvoyer par l'api
function Rows (data) {
    // cette fonction sert a construire le tableau ligne par ligne

    data.forEach((row)=> {
        // creation de la ligne
        let tr = document.createElement("tr");
        table.appendChild(tr);
        // creation de chaque colone
        row.forEach((element, index) => {

            // pour tous sauf l'id
            if (index != 0) {
                let td = document.createElement("td");
                tr.appendChild(td);

                // gere la colone Status
                if (index == 5){
                    td.id = `Status-${row[0]}`; // definie l'id afin de facilité la modification
                    // si actif
                    if (element == 0) {
                        td.textContent = "Actif";
                    }
                    // si inactif
                    else {
                        td.textContent = "Inactif";
    
                    }
                }
                // gere les autres colone
                else {
                    td.textContent = element;
    
                }  
            }
        })
        // creation de la colone action 
        let td_action = document.createElement("td"); 
        tr.appendChild(td_action);
        let button = document.createElement("button");
        td_action.appendChild(button)
        button.id = `Button-Status-${row[0]}`; // pour faciliter la modification
        // addeventlistener qui permet de changer le status dans la base de donnée
        button.addEventListener('click', () => {
            modifieStatus(row[0], row[5])
            if (row[5] == 1) {
                row[5] = 0;
            }
            else {
                row[5] = 1;
            }
            // console.log(row.Status); // debug
            
        });
        if (row[5]== 0) {
            button.textContent = "Invalidé";
        }
        else {
            button.textContent = "Validé";
        }

    })
}

// permet de modifier le status du commentaire de visible a masqué par l'appui sur le boutton d'action
function modifieStatus (id, status) {
    let new_status;
    //recuperation des elements du dom
    let button = document.getElementById(`Button-Status-${id}`);
    let td = document.getElementById(`Status-${id}`);
    
    // passe de invalide a valide
    if (status == 0) {
        new_status = 1;
        button.textContent = "Validé";
        td.textContent = "Inactif";
    }
    // passe de valide a invalide
    else {
        new_status = 0;
        button.textContent = "Invalidé";
        td.textContent = "Actif";

    }
    // console.log(new_status); // debug

    // requete url api en mode edition
    let requete_URL_edition = `http://localhost/JPO/site_jpo/test/lo_mode/lo_mode.php?&action=edition&id=${id}&status=${new_status}`
    fetch(requete_URL_edition);

}

// lance la creation du tableau
InitialisationTableau();

const delais = 30 * 1000; // 30s

// refresh le tableau toutes le x (delais) seconde
setInterval(miseAjour, delais);


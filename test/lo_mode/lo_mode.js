
let div_tableau = document.getElementById("tableau");
div_tableau.innerHTML = ""; // vide la div tableau

const table = document.createElement("table");
div_tableau.appendChild(table)

// date en secondes
let maintenant = Date.now();
// let date =  maintenant.getFullYear() + '-' + 
//             String(maintenant.getMonth() + 1).padStart(2, '0') + '-' + 
//             String(maintenant.getDate()).padStart(2, '0');

// let heure = String(maintenant.getHours()).padStart(2, '0') + ':' + 
//             String(maintenant.getMinutes()).padStart(2, '0') + ':' + 
//             String(maintenant.getSeconds()).padStart(2, '0');
                 




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
        // 1 : nom
        // 2 : commentaire
        // 3 : note
        // 4 : status
        data = await response.json();
    }
    catch (error) {
        console.error("Erreur lors de la récupération de l'API :", error);
        return ; // stopper la fonction
    }
    console.log(data);

    Rows(data);

    
}

function updateDate() {
    maintenant = Date.now();

    // date =  maintenant.getFullYear() + '-' + 
    //         String(maintenant.getMonth() + 1).padStart(2, '0') + '-' + 
    //         String(maintenant.getDate()).padStart(2, '0');

    // heure = String(maintenant.getHours()).padStart(2, '0') + ':' + 
    //         String(maintenant.getMinutes()).padStart(2, '0') + ':' + 
    //         String(maintenant.getSeconds()).padStart(2, '0');

// console.log(date, heure); // "2025-12-17 13:00:00"
}

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
        // 1 : nom
        // 2 : commentaire
        // 3 : note
        // 4 : status
        data = await response.json();
    }
    catch (error) {
        console.error("Erreur lors de la récupération de l'API :", error);
        return ; // stopper la fonction
    }
    console.log(data);

    Rows(data);
}

function Rows (data) {
    // cette fonction sert a construire le tableau ligne par ligne

    data.forEach((row)=> {
        // creation de la ligne
        let tr = document.createElement("tr");
        table.appendChild(tr);
        // creation de chaque colone
        row.forEach((element, index) => {
            
            // if (index == 1) {
            //     const dateString = element;
            //     console.log(dateString)
            //     const timestampS = Math.floor(Date.parse(dateString) / 1000);
            //     console.log(timestampS);
            // }

            // pour tous suaf l'id
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
            modifieStatus(row[0], row[4])
            if (row[4] == 1) {
                row[4] = 0;
            }
            else {
                row[4] = 1;
            }
            // console.log(row.Status); // debug
            
        });
        if (row[4]== 0) {
            button.textContent = "Invalidé";
        }
        else {
            button.textContent = "Validé";
        }

    })
}

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


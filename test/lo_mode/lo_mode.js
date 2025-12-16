

async function Tableau () {
    let div_tableau = document.getElementById("tableau");
    div_tableau.innerHTML = ""; // vide la div
    let table = document.createElement("table");
    div_tableau.appendChild(table)
    let intitules = ["Nom", "Commentaire", "Note", "Status (Valide/Invalide)", "Action"];
    let tr = document.createElement("tr");
    table.appendChild(tr);
    intitules.forEach((intitule) => {
        let th = document.createElement("th");
        tr.appendChild(th);
        th.textContent = intitule;
    })
    
    const requeteURL = "http://localhost/JPO/site_jpo/test/lo_mode/lo_mode.php?table=commentaire&action=lecture";
    let data = [];

    try {
        const response = await fetch(requeteURL);
        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }
        
        data = await response.json();
    }
    catch (error) {
        console.error("Erreur lors de la récupération de l'API :", error);
        return ; // stopper la fonction
    }
    console.log(data)

    data.forEach((row)=> {
        let tr = document.createElement("tr");
        table.appendChild(tr);
        row.forEach((element, index) => {
            if (index != 0) {
                let td = document.createElement("td");
                tr.appendChild(td);
                if (index == 4){
                    td.id = `Status-${row[0]}`;
                    if (element == 0) {
                        td.textContent = "Actif";
                    }
                    else {
                        td.textContent = "Inactif";
    
                    }
                }
                else {
                    td.textContent = element;
    
                }  
            }
        })
        let td_action = document.createElement("td");
        tr.appendChild(td_action);
        let button = document.createElement("button");
        td_action.appendChild(button)
        button.id = `Button-Status-${row[0]}`;
        button.addEventListener('click', () => {
            modifieStatus(row[0], row[4])
            if (row[4] == 1) {
                row[4] = 0;
            }
            else {
                row[4] = 1;
            }
            // console.log(row.Status);
            
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
    let button = document.getElementById(`Button-Status-${id}`);
    let td = document.getElementById(`Status-${id}`);
    
    if (status == 0) {
        new_status = 1;
        button.textContent = "Validé";
        td.textContent = "Invalidé";
    }
    else {
        new_status = 0;
        button.textContent = "Invalidé";
        td.textContent = "Validé";

    }
    console.log(new_status);
    fetch(`http://localhost/JPO/site_jpo/test/lo_mode/lo_mode.php?table=commentaire&action=edition&id=${id}&modification=${new_status}`);

}

Tableau();

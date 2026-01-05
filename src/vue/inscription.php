<main>
    <!-- ONGLET -->
    <div class="tabs">
        <button id="tabInscription" class="active">Inscription</button>
        <button id="tabConnexion">Connexion</button>
    </div>

    <h1 id="title">INSCRIPTION</h1>

    <div class="flex flex-col">

        <!-- INSCRIPTION -->
        <div id="inscription">
            <div>
                <label>Email</label>
                <input type="email">
            </div>

            <div>
                <label>Mot de passe</label>
                <input type="password">
            </div>

            <div>
                <label>Confirmation mot de passe</label>
                <input type="password">
            </div>

            <div>
                <p>Je certifie avoir lu les mentions légales</p>
            </div>

            <div>
                <button>Inscription</button>
            </div>
        </div>

        <!-- CONNEXION -->
        <div id="connexion" style="display: none;">
            <div>
                <label>Email</label>
                <input type="email">
            </div>

            <div>
                <label>Mot de passe</label>
                <input type="password">
            </div>

            <div>
                <button>Connexion</button>
            </div>
        </div>

    </div>
</main>

<script>
    const tabInscription = document.getElementById("tabInscription");
    const tabConnexion = document.getElementById("tabConnexion");

    const inscription = document.getElementById("inscription");
    const connexion = document.getElementById("connexion");

    const title = document.getElementById("title");

    tabInscription.addEventListener("click", () => {
        inscription.style.display = "block";
        connexion.style.display = "none";
        title.textContent = "INSCRIPTION";

        tabInscription.classList.add("active");
        tabConnexion.classList.remove("active");
    });

    tabConnexion.addEventListener("click", () => {
        inscription.style.display = "none";
        connexion.style.display = "block";
        title.textContent = "CONNEXION";

        tabConnexion.classList.add("active");
        tabInscription.classList.remove("active");
    });
</script>
// Configuration
const API_URL = '../api/points_api.php';

// État global
let currentVisiteur = null;
let currentMembre = null;
let selectedPoints = null;
let currentTransaction = null;

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    // Charger le membre (normalement il serait en session)
    loadMembreFromSession();
    
    // Event listeners
    document.getElementById('scanForm').addEventListener('submit', handleScanQR);
    document.getElementById('manualForm').addEventListener('submit', handleManualCode);
    document.getElementById('confirmBtn').addEventListener('click', handleConfirmPoints);
    document.getElementById('resetBtn').addEventListener('click', resetForm);
    
    // Points selection
    const pointBtns = document.querySelectorAll('.point-btn');
    pointBtns.forEach(btn => {
        btn.addEventListener('click', () => selectPoints(btn));
    });
    
    // Toggle entre scan et manuel
    document.getElementById('showManualBtn').addEventListener('click', () => {
        document.getElementById('scanSection').classList.add('hidden');
        document.getElementById('manualSection').classList.remove('hidden');
    });
    
    document.getElementById('showScanBtn').addEventListener('click', () => {
        document.getElementById('manualSection').classList.add('hidden');
        document.getElementById('scanSection').classList.remove('hidden');
    });
});

// Charger les infos du membre (simulation - normalement depuis session PHP)
function loadMembreFromSession() {
    // Dans la vraie version, ces données viendraient de PHP session
    // Pour le test, on utilise un membre par défaut
    currentMembre = {
        id_membre: 1,
        nom: 'Dupont',
        prenom: 'Marie',
        code_membre: 'MEMBRE001',
        id_stand: 1,
        nom_stand: 'Stand Créa',
        points_disponibles: '3,6,9'
    };
    
    // Afficher le nom du membre
    const membreInfo = document.getElementById('membreInfo');
    if (membreInfo && currentMembre) {
        membreInfo.textContent = `${currentMembre.prenom} ${currentMembre.nom} - ${currentMembre.nom_stand}`;
    }
    
    // Configurer les boutons de points disponibles
    setupPointsButtons(currentMembre.points_disponibles);
}

// Configurer les boutons de points
function setupPointsButtons(pointsDisponibles) {
    const points = pointsDisponibles.split(',').map(p => parseInt(p.trim()));
    const container = document.querySelector('.points-grid');
    container.innerHTML = '';
    
    points.forEach(point => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'point-btn';
        btn.textContent = `${point} pts`;
        btn.dataset.points = point;
        btn.addEventListener('click', () => selectPoints(btn));
        container.appendChild(btn);
    });
}

// Gérer le scan QR
async function handleScanQR(e) {
    e.preventDefault();
    const qrCode = document.getElementById('qrInput').value.trim();
    
    if (!qrCode) {
        showError('Veuillez entrer un code QR');
        return;
    }
    
    showLoading();
    
    try {
        const response = await fetch(`${API_URL}?action=get_visiteur&qr_code=${qrCode}`);
        const result = await response.json();
        
        hideLoading();
        
        if (result.success && result.data) {
            currentVisiteur = result.data;
            showVisiteurInfo();
        } else {
            showError('Visiteur non trouvé');
        }
    } catch (error) {
        hideLoading();
        showError('Erreur de connexion: ' + error.message);
    }
}

// Gérer l'entrée manuelle
async function handleManualCode(e) {
    e.preventDefault();
    const qrCode = document.getElementById('manualInput').value.trim();
    
    if (!qrCode) {
        showError('Veuillez entrer un code');
        return;
    }
    
    showLoading();
    
    try {
        const response = await fetch(`${API_URL}?action=get_visiteur&qr_code=${qrCode}`);
        const result = await response.json();
        
        hideLoading();
        
        if (result.success && result.data) {
            currentVisiteur = result.data;
            showVisiteurInfo();
            // Retour à la vue scan
            document.getElementById('manualSection').classList.add('hidden');
            document.getElementById('scanSection').classList.remove('hidden');
        } else {
            showError('Visiteur non trouvé');
        }
    } catch (error) {
        hideLoading();
        showError('Erreur de connexion: ' + error.message);
    }
}

// Afficher les infos du visiteur
function showVisiteurInfo() {
    const visiteurSection = document.getElementById('visiteurSection');
    const visiteurName = document.getElementById('visiteurName');
    const visiteurPoints = document.getElementById('visiteurPoints');
    
    visiteurName.textContent = `${currentVisiteur.prenom || ''} ${currentVisiteur.nom || 'Anonyme'}`.trim();
    visiteurPoints.textContent = `${currentVisiteur.total_points} points`;
    
    visiteurSection.classList.remove('hidden');
    document.getElementById('pointsSection').classList.remove('hidden');
}

// Sélectionner des points
function selectPoints(btn) {
    document.querySelectorAll('.point-btn').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
    selectedPoints = parseInt(btn.dataset.points);
    document.getElementById('confirmBtn').disabled = false;
}

// Confirmer l'ajout de points
async function handleConfirmPoints() {
    if (!currentVisiteur || !selectedPoints || !currentMembre) {
        showError('Informations manquantes');
        return;
    }
    
    showLoading();
    
    try {
        const response = await fetch(`${API_URL}?action=ajouter_points`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id_visiteur: currentVisiteur.id_visiteur,
                id_membre: currentMembre.id_membre,
                id_stand: currentMembre.id_stand,
                points: selectedPoints,
                commentaire: ''
            })
        });
        
        const result = await response.json();
        
        hideLoading();
        
        if (result.success) {
            showSuccess(`${selectedPoints} points ajoutés avec succès!`);
            currentTransaction = result.id_transaction;
            
            // Mettre à jour les points du visiteur
            currentVisiteur.total_points = parseInt(currentVisiteur.total_points) + selectedPoints;
            document.getElementById('visiteurPoints').textContent = `${currentVisiteur.total_points} points`;
            
            // Réinitialiser après 2 secondes
            setTimeout(resetForm, 2000);
        } else {
            showError('Erreur lors de l\'ajout des points');
        }
    } catch (error) {
        hideLoading();
        showError('Erreur de connexion: ' + error.message);
    }
}

// Réinitialiser le formulaire
function resetForm() {
    currentVisiteur = null;
    selectedPoints = null;
    
    document.getElementById('qrInput').value = '';
    document.getElementById('manualInput').value = '';
    document.getElementById('visiteurSection').classList.add('hidden');
    document.getElementById('pointsSection').classList.add('hidden');
    document.getElementById('confirmBtn').disabled = true;
    
    document.querySelectorAll('.point-btn').forEach(btn => btn.classList.remove('selected'));
    
    hideMessages();
}

// Afficher message de succès
function showSuccess(message) {
    const successDiv = document.getElementById('successMessage');
    successDiv.textContent = message;
    successDiv.classList.remove('hidden');
    
    setTimeout(() => {
        successDiv.classList.add('hidden');
    }, 3000);
}

// Afficher message d'erreur
function showError(message) {
    const errorDiv = document.getElementById('errorMessage');
    errorDiv.textContent = message;
    errorDiv.classList.remove('hidden');
    
    setTimeout(() => {
        errorDiv.classList.add('hidden');
    }, 3000);
}

// Afficher loading
function showLoading() {
    document.getElementById('loading').classList.remove('hidden');
}

// Cacher loading
function hideLoading() {
    document.getElementById('loading').classList.add('hidden');
}

// Cacher tous les messages
function hideMessages() {
    document.getElementById('successMessage').classList.add('hidden');
    document.getElementById('errorMessage').classList.add('hidden');
}

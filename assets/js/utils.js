// ============================================================
// utils.js — Fonctions utilitaires partagées par tout le groupe
// ============================================================

/**
 * Envoie une requête AJAX vers l'API PHP
 * C'est la fonction principale que tout le monde utilise
 * 
 * @param {string} url       - Ex: 'api/posts/get_posts.php'
 * @param {string} method    - 'GET' ou 'POST'
 * @param {object} data      - Les données à envoyer (optionnel)
 * @returns {Promise}        - La réponse JSON du serveur
 * 
 * Exemple d'utilisation :
 *   const result = await apiCall('api/auth/login.php', 'POST', { email, password });
 */
async function apiCall(url, method = 'GET', data = null) {
    const options = {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            // Envoie automatiquement le token si l'utilisateur est connecté
            'Authorization': getToken() ? `Bearer ${getToken()}` : ''
        }
    };

    if (data && method === 'POST') {
        options.body = JSON.stringify(data);
    }

    try {
        const response = await fetch(url, options);
        const json = await response.json();
        return json;
    } catch (error) {
        console.error('Erreur API :', error);
        return { success: false, error: 'Erreur de connexion au serveur' };
    }
}


// ============================================================
// Gestion du token (sessionStorage)
// ============================================================

/** Sauvegarde le token de connexion */
function saveToken(token) {
    sessionStorage.setItem('auth_token', token);
}

/** Récupère le token */
function getToken() {
    return sessionStorage.getItem('auth_token');
}

/** Sauvegarde les infos de l'utilisateur connecté */
function saveUser(user) {
    sessionStorage.setItem('user', JSON.stringify(user));
}

/** Récupère les infos de l'utilisateur connecté */
function getUser() {
    const user = sessionStorage.getItem('user');
    return user ? JSON.parse(user) : null;
}

/** Vérifie si l'utilisateur est connecté */
function estConnecte() {
    return getToken() !== null;
}

/** Déconnecte l'utilisateur */
function deconnecter() {
    sessionStorage.removeItem('auth_token');
    sessionStorage.removeItem('user');
    window.location.reload();
}


// ============================================================
// Affichage dynamique des vues
// ============================================================

/**
 * Charge une vue HTML dans le conteneur principal
 * @param {string} vuePath - Ex: 'vues/clients/accueil.html'
 */
async function chargerVue(vuePath) {
    const container = document.getElementById('app-container');
    try {
        const response = await fetch(vuePath);
        const html = await response.text();
        container.innerHTML = html;
    } catch (error) {
        container.innerHTML = '<p>Erreur lors du chargement de la page.</p>';
    }
}


// ============================================================
// Utilitaires divers
// ============================================================

/** Formate une date en français */
function formaterDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

/** Affiche une notification temporaire */
function afficherNotification(message, type = 'success') {
    const notif = document.createElement('div');
    notif.className = `notif notif-${type}`;
    notif.textContent = message;
    document.body.appendChild(notif);
    setTimeout(() => notif.remove(), 3000);
}

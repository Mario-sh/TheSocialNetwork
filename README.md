# TheSocialNetwork

> Application web de type réseau social inspirée de Facebook — Examen Final PHP & AJAX

![PHP](https://img.shields.io/badge/PHP-natif-777BB4?style=flat-square&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat-square&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6-F7DF1E?style=flat-square&logo=javascript&logoColor=black)
![AJAX](https://img.shields.io/badge/AJAX-Fetch_API-00D09C?style=flat-square)

---

## Description du projet

**TheSocialNetwork** est une application web full-stack de type réseau social développée en PHP natif, JavaScript (AJAX/Fetch) et MySQL dans le cadre de l'examen final du cours de développement web.

L'application permet aux utilisateurs de s'inscrire, publier des articles, interagir avec d'autres membres via les likes, commentaires et un système de messagerie en temps réel simulé. Un espace d'administration complet est également disponible pour les modérateurs et administrateurs.

---

## Fonctionnalités

### Côté Client
- **Authentification complète** — Inscription, connexion, mot de passe oublié avec email HTML
- **Fil d'actualité** — Affichage des publications avec likes, dislikes et commentaires en AJAX
- **Gestion des amis** — Envoi, réception et gestion des invitations d'amitié
- **Profil personnel** — Modification des informations et de la photo de profil
- **Chat en temps réel** — Messagerie instantanée simulée avec rafraîchissement toutes les 3 secondes

### Back-office
- **Deux rôles** — Administrateur et Modérateur
- **Dashboard** — Statistiques détaillées (utilisateurs, publications, messages, réactions)
- **Gestion des utilisateurs** — Consultation, suppression, changement de rôle
- **Gestion des articles** — Consultation et suppression des publications

---

## Technologies utilisées

| Couche | Technologies |
|--------|-------------|
| Frontend | HTML5, CSS3, JavaScript ES6 natif |
| Requêtes HTTP | Fetch API (AJAX) |
| Backend | PHP 8 natif |
| Base de données | MySQL 8 avec PDO |
| Versioning | Git & GitHub |
| Serveur local | XAMPP (Apache + MySQL) |

---

## Architecture du projet

```
TheSocialNetwork/
├── index.html                    # Point d'entrée unique
├── assets/
│   ├── css/                      # Styles CSS
│   ├── js/
│   │   └── utils.js              # Fonctions JS partagées (apiCall, token, etc.)
│   └── images/                   # Images et avatars
├── vues/
│   ├── clients/                  # Pages utilisateur
│   │   ├── login.html
│   │   ├── register.html
│   │   ├── accueil.html
│   │   ├── amis.html
│   │   ├── profil.html
│   │   ├── chat.html
│   │   ├── forgot_password.html
│   │   └── reset_password.html
│   └── back-office/              # Pages administration
│       ├── login.html
│       ├── dashboard.html
│       ├── utilisateurs.html
│       └── articles.html
├── api/
│   ├── config.php                # Connexion BDD
│   ├── auth_check.php            # Vérification token
│   ├── auth/                     # Module authentification
│   ├── posts/                    # Module publications
│   ├── friends/                  # Module amis
│   ├── profile/                  # Module profil
│   ├── chat/                     # Module messagerie
│   └── admin/                    # Module administration
└── database/
    └── schema.sql                # Structure de la base de données
```

---

## Installation & Démarrage

### Prérequis
- [XAMPP](https://www.apachefriends.org) (Apache + PHP + MySQL)
- [Git](https://git-scm.com)

### Étapes

**1. Cloner le dépôt**
```bash
git clone https://github.com/Mario-sh/TheSocialNetwork.git
cd TheSocialNetwork
```

**2. Placer le projet dans htdocs**
```
C:\xampp\htdocs\TheSocialNetwork\
```

**3. Démarrer XAMPP**

Ouvrir XAMPP Control Panel et démarrer **Apache** et **MySQL**.

**4. Configurer la base de données**

- Ouvrir http://localhost/phpmyadmin
- Créer une base de données nommée `reseau_social`
- Importer le fichier `database/schema.sql`

**5. Configurer config.php**
```bash
copy api\config.example.php api\config.php
```

Ouvrir `api/config.php` et renseigner les identifiants MySQL :
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'reseau_social');
define('DB_USER', 'root');
define('DB_PASS', '');
define('BASE_URL', 'http://localhost/TheSocialNetwork');
define('SECRET_KEY', 'votre_cle_secrete');
```

**6. Lancer l'application**

Ouvrir http://localhost/TheSocialNetwork

---

## Identifiants de test

### Compte Utilisateur
| Champ | Valeur |
|-------|--------|
| Email | `dylan@test.com` |
| Mot de passe | `Test1234!` |

### Compte Administrateur
| Champ | Valeur |
|-------|--------|
| Email | `admin@test.com` |
| Mot de passe | `Test1234!` |
| URL back-office | http://localhost/TheSocialNetwork/vues/back-office/login.html |

### Compte Modérateur
| Champ | Valeur |
|-------|--------|
| Email | `moderateur@test.com` |
| Mot de passe | `Test1234!` |

---

## Fonctionnement technique

### Authentification sans rechargement

La gestion des sessions est assurée côté JavaScript via `sessionStorage`. À la connexion, le serveur PHP génère un token unique stocké en base de données et retourné au client. Ce token est ensuite envoyé dans le header `Authorization` de chaque requête AJAX.

```
Utilisateur → login.html → login.php → token → sessionStorage
Chaque requête → Authorization: Bearer {token} → auth_check.php → $user_id
```

### Architecture API REST

Chaque fonctionnalité dispose de ses propres endpoints PHP qui reçoivent des données JSON et retournent du JSON.

```
Frontend (HTML/JS)  ←→  API PHP  ←→  MySQL
     fetch()              PDO
  sessionStorage         Requêtes préparées
```

### Chat en temps réel

Le module de chat utilise `setInterval` toutes les **3 secondes** pour interroger le serveur et afficher les nouveaux messages sans recharger la page.

---

## Sécurité

- **Requêtes préparées PDO** — protection contre les injections SQL
- **`password_hash()` / `password_verify()`** — mots de passe jamais stockés en clair
- **Tokens d'authentification** — génération avec `bin2hex(random_bytes(32))`
- **Validation des entrées** — vérification côté PHP avant toute insertion en BDD
- **Headers CORS** — configurés sur toutes les routes API

---

## Membres du groupe

**Groupe N°1**

| # | Nom & Prénom | Rôle | Module |
|---|-------------|------|--------|
| 1 | LOKOSSOU SOTON Mario Miguel Dylane | Chef de projet | Authentification + Infrastructure + Pages HTML |
| 2 | ZOHOUN Melris | Développeur | Flux d'articles + Likes + Commentaires |
| 3 | SENOU Michael | Développeur | Gestion des Amis + Chat |
| 4 | DJESSOU Merlaud | Développeur | Back-office Administration |
| 5 | DIALLO Abdoulrahmane | Développeur | Profil personnel |

---

## Dépôt GitHub

https://github.com/Mario-sh/TheSocialNetwork

---


*Examen Final — TP Réseau Social Web en PHP et AJAX*
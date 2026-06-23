# 📘 Réseau Social Web — Examen Final PHP & AJAX

> Application web de type réseau social inspirée de Facebook.  
> Développée en PHP natif, JavaScript (Fetch/AJAX) et MySQL.

---

## 👥 Membres du Groupe

| Membre | Rôle | Modules |
|--------|------|---------|
| [Ton Prénom NOM] | Chef de projet | Auth, Infrastructure, BDD |
| [Prénom NOM] | Développeur Frontend/Backend | Flux articles, Likes, Commentaires |
| [Prénom NOM] | Développeur Frontend/Backend | Amis, Profil, Chat |
| [Prénom NOM] | Développeur Backend | Back-office, Dashboard admin |

---

## 🏗️ Architecture du Projet

```
reseau-social/
│
├── index.html                  # Point d'entrée unique de l'application
│
├── assets/
│   ├── css/
│   │   └── style.css           # Styles globaux
│   ├── js/
│   │   ├── auth.js             # Gestion de l'authentification côté JS
│   │   ├── posts.js            # Flux articles
│   │   ├── friends.js          # Gestion des amis
│   │   ├── chat.js             # Module chat
│   │   └── utils.js            # Fonctions utilitaires partagées
│   └── images/                 # Images statiques
│
├── vues/
│   ├── clients/                # Pages accessibles aux utilisateurs
│   │   ├── accueil.html
│   │   ├── profil.html
│   │   ├── amis.html
│   │   └── chat.html
│   └── back-office/            # Pages d'administration
│       ├── dashboard.html
│       ├── utilisateurs.html
│       └── articles.html
│
├── api/                        # Scripts PHP (API REST)
│   ├── config.php              # Connexion BDD (NE PAS MODIFIER)
│   ├── auth/
│   │   ├── register.php
│   │   ├── login.php
│   │   └── forgot_password.php
│   ├── posts/
│   │   ├── get_posts.php
│   │   ├── create_post.php
│   │   ├── like.php
│   │   └── comment.php
│   ├── friends/
│   │   ├── get_friends.php
│   │   ├── send_request.php
│   │   └── respond_request.php
│   ├── chat/
│   │   ├── get_messages.php
│   │   └── send_message.php
│   └── admin/
│       ├── login_admin.php
│       ├── get_stats.php
│       └── manage_users.php
│
└── database/
    └── schema.sql              # Script de création de la BDD
```

---

## ⚙️ Installation & Configuration

### Prérequis
- **WSL Ubuntu** (Linux) ou **XAMPP** (Windows)
- PHP 8.x
- MySQL 8.x
- Navigateur moderne

### Étape 1 — Cloner le projet

```bash
git clone https://github.com/VOTRE_USERNAME/reseau-social.git
cd reseau-social
```

### Étape 2 — Configurer la base de données

```bash
# Créer la base de données
mysql -u root -p < database/schema.sql
```

### Étape 3 — Configurer la connexion BDD

Copier le fichier de config exemple et remplir vos informations :

```bash
cp api/config.example.php api/config.php
```

Ouvrir `api/config.php` et modifier :

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'reseau_social');
define('DB_USER', 'root');       // Votre utilisateur MySQL
define('DB_PASS', '');           // Votre mot de passe MySQL
```

### Étape 4 — Lancer le projet

**Sur WSL Ubuntu :**
```bash
sudo service apache2 start
sudo service mysql start
# Accéder via : http://localhost
```

**Sur XAMPP (Windows) :**
- Lancer XAMPP Control Panel
- Démarrer Apache + MySQL
- Accéder via : `http://localhost`

---

## 🔑 Identifiants de Test

> ⚠️ À compléter avant la soumission finale

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Utilisateur client | test@test.com | Test1234! |
| Modérateur | moderateur@test.com | Modo1234! |
| Administrateur | admin@test.com | Admin1234! |

---

## 🌿 Branches Git

| Branche | Description | Responsable |
|---------|-------------|-------------|
| `main` | Code stable, version finale | Chef de projet |
| `develop` | Branche d'intégration | Chef de projet |
| `feature/auth` | Authentification | Membre 1 |
| `feature/posts` | Flux articles | Membre 2 |
| `feature/friends-chat` | Amis, Profil, Chat | Membre 3 |
| `feature/backoffice` | Back-office admin | Membre 4 |

---

## 📋 Avancement des Fonctionnalités

- [ ] Authentification (inscription, connexion, mot de passe oublié)
- [ ] Flux d'articles (affichage, création, image)
- [ ] Likes / Dislikes avec persistance
- [ ] Commentaires en AJAX
- [ ] Gestion des amis
- [ ] Profil utilisateur
- [ ] Module Chat
- [ ] Back-office Modérateur
- [ ] Back-office Administrateur
- [ ] Dashboard statistiques

---

## 🔗 Liens

- **Dépôt GitHub :** [INSÉRER LE LIEN ICI]
- **Google Classroom :** https://classroom.google.com/c/ODY3ODgyODgwMDE0/a/ODY3ODgyNzQxNzk0/details

---

## 📅 Date limite

**28 juin 2026 à 23h59**

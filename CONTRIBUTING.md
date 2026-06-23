# 🌿 Guide Git — Pour Tout le Groupe

> Lis ce fichier UNE FOIS avant de toucher au code.  
> Il t'explique exactement quoi faire pour ne jamais casser le travail des autres.

---

## 💡 Le principe de base

On travaille chacun sur **notre propre branche**.  
On ne touche **jamais** directement à `main` ou `develop`.

```
main          ← version finale stable (on n'y touche pas)
  └── develop ← on intègre ici quand ça marche
        ├── feature/auth          ← Membre 1
        ├── feature/posts         ← Membre 2
        ├── feature/friends-chat  ← Membre 3
        └── feature/backoffice    ← Membre 4
```

---

## 🚀 Installation initiale (à faire UNE SEULE FOIS)

### 1. Installer Git

**Ubuntu / WSL :**
```bash
sudo apt install git -y
```

**Windows :**  
Télécharger et installer : https://git-scm.com/download/win

### 2. Configurer ton identité Git
```bash
git config --global user.name "Ton Prénom NOM"
git config --global user.email "ton.email@example.com"
```

### 3. Cloner le projet
```bash
git clone https://github.com/VOTRE_USERNAME/reseau-social.git
cd reseau-social
```

### 4. Aller sur TA branche (remplace par la tienne)
```bash
# Membre 1 :
git checkout feature/auth

# Membre 2 :
git checkout feature/posts

# Membre 3 :
git checkout feature/friends-chat

# Membre 4 :
git checkout feature/backoffice
```

---

## 📅 Routine quotidienne — Ce que tu fais CHAQUE JOUR

### Matin — Récupérer les mises à jour des autres
```bash
# 1. Aller sur develop
git checkout develop

# 2. Télécharger les dernières modifications
git pull origin develop

# 3. Retourner sur ta branche
git checkout feature/TON-MODULE

# 4. Intégrer les mises à jour de develop dans ta branche
git merge develop
```

### Soir — Sauvegarder ton travail
```bash
# 1. Vérifier ce que tu as modifié
git status

# 2. Ajouter tes fichiers modifiés
git add .

# 3. Créer un commit (message clair et précis)
git commit -m "feat: ajout du formulaire de connexion"

# 4. Envoyer sur GitHub
git push origin feature/TON-MODULE
```

---

## ✍️ Comment écrire un bon message de commit

Le format : `type: description courte`

| Type | Quand l'utiliser | Exemple |
|------|-----------------|---------|
| `feat` | Tu ajoutes une nouvelle fonctionnalité | `feat: création du formulaire d'inscription` |
| `fix` | Tu corriges un bug | `fix: correction de l'affichage des likes` |
| `style` | Tu modifies le CSS | `style: mise en page du profil utilisateur` |
| `db` | Tu modifies la BDD | `db: ajout de la table messages` |

---

## 🔀 Intégrer son travail dans develop

Quand ton module est **terminé et testé**, tu demandes au chef de projet de fusionner.  
Il fera lui-même le merge dans `develop` après vérification.

**Ne jamais faire `git push origin main` ou `git push origin develop` directement.**

---

## 🆘 Commandes d'urgence

```bash
# J'ai fait une erreur et je veux annuler mes dernières modifications
git checkout -- .

# Je veux voir l'historique de mes commits
git log --oneline

# Je veux voir sur quelle branche je suis
git branch

# Conflit ? Appeler le chef de projet immédiatement.
```

---

## ⚠️ Les règles d'or

1. **Ne jamais push sur `main`** directement
2. **Toujours pull avant de commencer** à travailler
3. **Committer souvent** — plusieurs petits commits valent mieux qu'un seul gros
4. **Message de commit clair** — le prof vérifie ça
5. **En cas de doute** — appelle le chef de projet avant de faire quoi que ce soit

---

## 📁 Fichiers que tu ne dois PAS modifier

| Fichier | Propriétaire |
|---------|-------------|
| `api/config.php` | Chef de projet uniquement |
| `database/schema.sql` | Chef de projet uniquement |
| `assets/js/utils.js` | Tout le monde peut lire, chef de projet modifie |
| `index.html` | Coordonner avec le chef avant de modifier |

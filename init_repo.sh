#!/bin/bash
# ============================================================
# SCRIPT D'INITIALISATION DU REPO — À exécuter UNE SEULE FOIS
# par le chef de projet
# ============================================================
# Usage : bash init_repo.sh

echo "🚀 Initialisation du dépôt Git..."

# Init Git
git init
git add .
git commit -m "feat: initialisation du projet — structure complète"

# Remplacer l'URL par ton vrai repo GitHub
echo ""
echo "⚠️  Crée d'abord ton repo sur GitHub, puis colle l'URL ici :"
read -p "URL du repo GitHub (ex: https://github.com/user/reseau-social.git) : " REPO_URL

git remote add origin $REPO_URL
git branch -M main
git push -u origin main

echo ""
echo "🌿 Création des branches..."

# Branche develop
git checkout -b develop
git push origin develop

# Branches des membres
git checkout -b feature/auth
git push origin feature/auth
git checkout develop

git checkout -b feature/posts
git push origin feature/posts
git checkout develop

git checkout -b feature/friends-chat
git push origin feature/friends-chat
git checkout develop

git checkout -b feature/backoffice
git push origin feature/backoffice
git checkout develop

echo ""
echo "✅ Repo initialisé avec succès !"
echo ""
echo "Branches créées :"
git branch -a
echo ""
echo "📋 Prochaine étape :"
echo "   → Va sur GitHub > Settings > Branches"
echo "   → Ajoute une règle de protection sur 'main'"
echo "   → Active : 'Require a pull request before merging'"

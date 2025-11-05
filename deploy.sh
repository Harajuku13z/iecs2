#!/bin/bash

# Script de déploiement pour IECS2
# Usage: ./deploy.sh

# Note: set -e est désactivé pour permettre la gestion d'erreurs personnalisée

# Couleurs pour les messages
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Variables de configuration
DB_USER="u570136219_uni"
DB_NAME="u570136219_uni"
DB_PASSWORD="Harajuku1993@"
DB_HOST="localhost"
PROJECT_DIR="/home/u570136219/public_html"
GIT_REPO="https://github.com/Harajuku13z/iecs2.git"
GIT_BRANCH="main"

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}Déploiement IECS2${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""

# Vérifier si on est dans le bon répertoire
if [ ! -d "$PROJECT_DIR" ]; then
    echo -e "${YELLOW}Le répertoire $PROJECT_DIR n'existe pas. Création...${NC}"
    mkdir -p "$PROJECT_DIR"
fi

cd "$PROJECT_DIR"

# Vérifier si c'est un dépôt git
if [ ! -d ".git" ]; then
    echo -e "${YELLOW}Initialisation du dépôt Git...${NC}"
    git init
    git remote add origin "$GIT_REPO" 2>/dev/null || git remote set-url origin "$GIT_REPO"
    git fetch origin
    git checkout -b "$GIT_BRANCH" origin/"$GIT_BRANCH" 2>/dev/null || git checkout "$GIT_BRANCH"
else
    echo -e "${GREEN}Mise à jour depuis GitHub...${NC}"
    git fetch origin
    git reset --hard origin/"$GIT_BRANCH"
fi

echo -e "${GREEN}✓ Code source mis à jour${NC}"
echo ""

# Installer les dépendances Composer
echo -e "${YELLOW}Installation des dépendances Composer...${NC}"
if command -v composer &> /dev/null; then
    composer install --no-dev --optimize-autoloader --no-interaction
elif [ -f "composer.phar" ]; then
    php composer.phar install --no-dev --optimize-autoloader --no-interaction
else
    curl -sS https://getcomposer.org/installer | php
    php composer.phar install --no-dev --optimize-autoloader --no-interaction
fi
echo -e "${GREEN}✓ Dépendances Composer installées${NC}"
echo ""

# Installer les dépendances NPM
echo -e "${YELLOW}Installation des dépendances NPM...${NC}"
if command -v npm &> /dev/null; then
    npm install --production --legacy-peer-deps
    echo -e "${GREEN}✓ Dépendances NPM installées${NC}"
else
    echo -e "${RED}⚠ npm n'est pas installé. Ignoré.${NC}"
fi
echo ""

# Compiler les assets
echo -e "${YELLOW}Compilation des assets...${NC}"
if command -v npm &> /dev/null; then
    npm run build
    echo -e "${GREEN}✓ Assets compilés${NC}"
else
    echo -e "${YELLOW}⚠ npm n'est pas disponible. Les assets ne seront pas compilés.${NC}"
fi
echo ""

# Configuration du .env
echo -e "${YELLOW}Configuration du fichier .env...${NC}"
if [ ! -f ".env" ]; then
    if [ -f ".env.example" ]; then
        cp .env.example .env
    else
        echo -e "${RED}⚠ Fichier .env.example non trouvé${NC}"
    fi
fi

# Configuration de la base de données MySQL
sed -i "s/^DB_CONNECTION=.*/DB_CONNECTION=mysql/" .env
sed -i "s/^DB_HOST=.*/DB_HOST=$DB_HOST/" .env
sed -i "s/^DB_PORT=.*/DB_PORT=3306/" .env
sed -i "s/^DB_DATABASE=.*/DB_DATABASE=$DB_NAME/" .env
sed -i "s/^DB_USERNAME=.*/DB_USERNAME=$DB_USER/" .env
sed -i "s/^DB_PASSWORD=.*/DB_PASSWORD=$DB_PASSWORD/" .env

# Configuration de l'environnement de production
sed -i "s/^APP_ENV=.*/APP_ENV=production/" .env
sed -i "s/^APP_DEBUG=.*/APP_DEBUG=false/" .env

# Générer la clé d'application si nécessaire
if ! grep -q "APP_KEY=" .env || grep -q "APP_KEY=$" .env; then
    php artisan key:generate --force
fi

echo -e "${GREEN}✓ Configuration .env mise à jour${NC}"
echo ""

# Tester la connexion à la base de données
echo -e "${YELLOW}Test de connexion à la base de données...${NC}"
php artisan db:show --database=mysql 2>/dev/null || {
    echo -e "${YELLOW}⚠ La base de données n'existe pas encore ou la connexion a échoué${NC}"
    echo -e "${YELLOW}  Veuillez créer la base de données manuellement si nécessaire${NC}"
}

# Exécuter les migrations
echo -e "${YELLOW}Exécution des migrations...${NC}"
php artisan migrate --force --database=mysql
echo -e "${GREEN}✓ Migrations exécutées${NC}"
echo ""

# Migrer les données de SQLite vers MySQL si le fichier SQLite existe
if [ -f "database/database.sqlite" ]; then
    echo -e "${YELLOW}Migration des données de SQLite vers MySQL...${NC}"
    if php artisan migrate:sqlite-to-mysql --force 2>/dev/null; then
        echo -e "${GREEN}✓ Données migrées${NC}"
    else
        echo -e "${YELLOW}⚠ Erreur lors de la migration. Exécution des seeders à la place...${NC}"
        php artisan db:seed --class=AdminSeeder --force
        php artisan db:seed --class=IESCADataSeeder --force
        php artisan db:seed --class=ActualitesEvenementsSeeder --force
        echo -e "${GREEN}✓ Seeders exécutés${NC}"
    fi
    echo ""
else
    echo -e "${YELLOW}⚠ Fichier SQLite non trouvé. Exécution des seeders...${NC}"
    # Exécuter les seeders
    php artisan db:seed --class=AdminSeeder --force
    php artisan db:seed --class=IESCADataSeeder --force
    php artisan db:seed --class=ActualitesEvenementsSeeder --force
    echo -e "${GREEN}✓ Seeders exécutés${NC}"
    echo ""
fi

# Créer le lien symbolique pour le storage
echo -e "${YELLOW}Création du lien symbolique storage...${NC}"
php artisan storage:link --force 2>/dev/null || echo -e "${YELLOW}⚠ Le lien existe déjà${NC}"
echo -e "${GREEN}✓ Lien symbolique créé${NC}"
echo ""

# Optimiser Laravel pour la production
echo -e "${YELLOW}Optimisation de Laravel...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
echo -e "${GREEN}✓ Optimisations terminées${NC}"
echo ""

# Configurer les permissions
echo -e "${YELLOW}Configuration des permissions...${NC}"
chmod -R 775 storage bootstrap/cache
chmod -R 755 public
find storage bootstrap/cache -type f -exec chmod 664 {} \;
find storage bootstrap/cache -type d -exec chmod 775 {} \;
echo -e "${GREEN}✓ Permissions configurées${NC}"
echo ""

# Nettoyer les fichiers de debug
echo -e "${YELLOW}Nettoyage des fichiers de debug...${NC}"
rm -f public/test.php public/debug.php 2>/dev/null || true
echo -e "${GREEN}✓ Fichiers de debug supprimés${NC}"
echo ""

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}Déploiement terminé avec succès !${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "${YELLOW}Informations importantes:${NC}"
echo -e "  - Base de données: ${DB_NAME}"
echo -e "  - Utilisateur: ${DB_USER}"
echo -e "  - Environnement: production"
echo -e "  - Debug: désactivé"
echo ""
echo -e "${YELLOW}Prochaines étapes:${NC}"
echo -e "  1. Vérifiez que le DocumentRoot pointe vers: $PROJECT_DIR/public"
echo -e "  2. Vérifiez que mod_rewrite est activé"
echo -e "  3. Testez votre site en ligne"
echo ""


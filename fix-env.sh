#!/bin/bash

# Script de correction rapide pour le .env
# Usage: ./fix-env.sh

# Variables de configuration
DB_USER="u570136219_uni"
DB_NAME="u570136219_uni"
DB_PASSWORD="Harajuku1993@"
DB_HOST="localhost"

echo "Correction du fichier .env..."

# Fonction pour mettre à jour ou ajouter une variable dans .env
update_env() {
    local key=$1
    local value=$2
    if grep -q "^${key}=" .env; then
        # Échapper les caractères spéciaux pour sed
        local escaped_value=$(echo "$value" | sed 's/[[\.*^$()+?{|]/\\&/g')
        sed -i.bak "s|^${key}=.*|${key}=${escaped_value}|" .env
    else
        echo "${key}=${value}" >> .env
    fi
}

# Configuration de la base de données MySQL
update_env "DB_CONNECTION" "mysql"
update_env "DB_HOST" "$DB_HOST"
update_env "DB_PORT" "3306"
update_env "DB_DATABASE" "$DB_NAME"
update_env "DB_USERNAME" "$DB_USER"
update_env "DB_PASSWORD" "$DB_PASSWORD"

# Nettoyer le backup
rm -f .env.bak 2>/dev/null || true

# Vider le cache
php artisan config:clear

echo "✓ Fichier .env corrigé !"
echo ""
echo "Configuration actuelle:"
grep "^DB_" .env | sed 's/\(DB_PASSWORD=\).*/\1***/' || true
echo ""
echo "Test de connexion:"
php artisan db:show || echo "⚠ Erreur de connexion. Vérifiez les identifiants."


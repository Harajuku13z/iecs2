#!/bin/bash

# Script pour désactiver le mode debug
# Usage: ./disable-debug.sh

echo "Désactivation du mode debug..."

# Fonction pour mettre à jour ou ajouter une variable dans .env
update_env() {
    local key=$1
    local value=$2
    if grep -q "^${key}=" .env; then
        local escaped_value=$(echo "$value" | sed 's/[[\.*^$()+?{|]/\\&/g')
        sed -i.bak "s|^${key}=.*|${key}=${escaped_value}|" .env
    else
        echo "${key}=${value}" >> .env
    fi
}

# Désactiver le debug
update_env "APP_DEBUG" "false"
update_env "APP_ENV" "production"

# Nettoyer le backup
rm -f .env.bak 2>/dev/null || true

# Optimiser pour la production
echo "Optimisation pour la production..."
php artisan config:cache 2>/dev/null || true
php artisan route:cache 2>/dev/null || true
php artisan view:cache 2>/dev/null || true

echo "✅ Mode debug désactivé et optimisations appliquées !"


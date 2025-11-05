#!/bin/bash

# Script pour activer le mode debug et voir les erreurs
# Usage: ./enable-debug.sh

echo "Activation du mode debug..."

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

# Activer le debug
update_env "APP_DEBUG" "true"
update_env "APP_ENV" "local"

# Nettoyer le backup
rm -f .env.bak 2>/dev/null || true

# Vider tous les caches
echo "Vidage des caches..."
php artisan config:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true

echo "✅ Mode debug activé !"
echo ""
echo "Maintenant, rafraîchissez votre page web pour voir l'erreur exacte."
echo ""
echo "⚠️  ATTENTION: Désactivez le debug en production après avoir résolu le problème !"
echo "   Exécutez: ./disable-debug.sh"


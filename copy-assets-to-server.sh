#!/bin/bash

# Script pour copier les assets compilés depuis local vers le serveur
# Usage: ./copy-assets-to-server.sh [user@server:/path/to/public_html]

set -e

if [ -z "$1" ]; then
    echo "Usage: ./copy-assets-to-server.sh user@server:/path/to/public_html"
    echo ""
    echo "Exemple:"
    echo "  ./copy-assets-to-server.sh u570136219@iesc.osmoseconsulting.fr:/home/u570136219/domains/iesc.osmoseconsulting.fr/public_html"
    exit 1
fi

SERVER_PATH="$1"
REMOTE_DIR=$(echo "$SERVER_PATH" | cut -d: -f2)
REMOTE_HOST=$(echo "$SERVER_PATH" | cut -d: -f1)

echo "📦 Copie des assets compilés vers le serveur..."
echo "   Serveur: $REMOTE_HOST"
echo "   Chemin: $REMOTE_DIR"
echo ""

# Vérifier que les fichiers existent localement
if [ ! -f "public/build/manifest.json" ]; then
    echo "❌ Erreur: public/build/manifest.json n'existe pas localement"
    echo "   Compilez d'abord avec: npm run build"
    exit 1
fi

# Copier le dossier build complet
echo "📤 Copie de public/build/ vers le serveur..."
rsync -avz --progress public/build/ "$SERVER_PATH/public/build/"

# Vérifier la copie
echo ""
echo "✅ Copie terminée !"
echo ""
echo "🔍 Vérification sur le serveur..."
ssh "$REMOTE_HOST" "cd $REMOTE_DIR && ls -lh public/build/manifest.json public/build/assets/ 2>/dev/null || echo '⚠️  Vérifiez que les fichiers ont été copiés'"

echo ""
echo "✨ N'oubliez pas de vider les caches sur le serveur :"
echo "   ssh $REMOTE_HOST 'cd $REMOTE_DIR && php artisan view:clear && php artisan config:clear'"


#!/bin/bash

# Script pour forcer le fallback CDN sur le serveur
# Cela désactive complètement Vite et utilise uniquement CDN

echo "🔧 Forcer le fallback CDN sur le serveur..."

cd "$(dirname "$0")"

# Supprimer le manifest pour forcer le fallback
if [ -f "public/build/manifest.json" ]; then
    echo "📦 Sauvegarde du manifest existant..."
    mv public/build/manifest.json public/build/manifest.json.backup
fi

# Créer un fichier .env.local pour forcer le mode production
if ! grep -q "VITE" .env 2>/dev/null; then
    echo "" >> .env
    echo "# Force CDN fallback" >> .env
    echo "VITE_APP_URL=" >> .env
fi

# Vider les caches
if command -v php &> /dev/null; then
    php artisan view:clear 2>/dev/null || true
    php artisan config:clear 2>/dev/null || true
    php artisan cache:clear 2>/dev/null || true
fi

echo ""
echo "✅ Fallback CDN forcé !"
echo "   Le manifest.json a été sauvegardé en .backup"
echo "   Le site utilisera maintenant uniquement Bootstrap CDN"
echo ""
echo "💡 Pour restaurer Vite:"
echo "   mv public/build/manifest.json.backup public/build/manifest.json"


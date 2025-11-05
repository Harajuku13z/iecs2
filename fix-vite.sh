#!/bin/bash

# Script pour corriger le problème Vite manifest
# Usage: ./fix-vite.sh

echo "Correction du problème Vite manifest..."

# Créer le dossier build s'il n'existe pas
mkdir -p public/build

# Option 1: Essayer de compiler les assets avec npm
if command -v npm &> /dev/null; then
    echo "npm est disponible, compilation des assets..."
    npm install
    npm run build
    
    if [ -f "public/build/manifest.json" ]; then
        echo "✅ Assets compilés avec succès !"
        exit 0
    fi
fi

# Option 2: Créer un manifest.json minimal pour éviter l'erreur
echo "Création d'un manifest.json minimal..."

cat > public/build/manifest.json << 'EOF'
{
  "resources/css/app.css": {
    "file": "assets/app.css",
    "src": "resources/css/app.css",
    "isEntry": true
  },
  "resources/js/app.js": {
    "file": "assets/app.js",
    "src": "resources/js/app.js",
    "isEntry": true
  }
}
EOF

# Créer les fichiers CSS/JS de base s'ils n'existent pas
mkdir -p public/build/assets

# Créer un fichier CSS minimal
if [ ! -f "public/build/assets/app.css" ]; then
    echo "Création d'un fichier CSS minimal..."
    cat > public/build/assets/app.css << 'CSSEOF'
/* Styles de base - Compilez avec npm run build pour les styles complets */
body {
    margin: 0;
    padding: 0;
    font-family: system-ui, -apple-system, sans-serif;
}
CSSEOF
fi

# Créer un fichier JS minimal
if [ ! -f "public/build/assets/app.js" ]; then
    echo "Création d'un fichier JS minimal..."
    echo "// JavaScript de base" > public/build/assets/app.js
fi

echo "✅ Manifest.json créé !"
echo ""
echo "⚠️  NOTE: Les fichiers CSS/JS sont minimaux."
echo "   Pour les styles complets, compilez avec: npm run build"
echo ""
echo "Vérification:"
ls -lh public/build/manifest.json 2>/dev/null || echo "❌ Erreur: manifest.json non créé"


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

# Créer un fichier CSS avec Bootstrap et styles de base
if [ ! -f "public/build/assets/app.css" ]; then
    echo "Création d'un fichier CSS avec Bootstrap et styles de base..."
    cat > public/build/assets/app.css << 'CSSEOF'
/* Import Bootstrap depuis CDN si nécessaire */
@import url('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css');

/* Import Google Fonts */
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');

/* Global font family - sans-serif */
* {
    font-family: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif !important;
}

body {
    font-family: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif !important;
    margin: 0;
    padding: 0;
}

h1, h2, h3, h4, h5, h6 {
    font-family: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif !important;
}

/* Styles de base pour la navigation */
.navbar {
    padding: 1rem 0;
    transition: all 0.3s ease;
}

.navbar-brand {
    transition: transform 0.3s ease;
}

.navbar-brand:hover {
    transform: scale(1.05);
}

/* Styles de base pour les boutons */
.btn {
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary {
    background-color: var(--color-primary, #A66060);
    border-color: var(--color-primary, #A66060);
}

.btn-primary:hover {
    background-color: var(--color-secondary, #9E5A59);
    border-color: var(--color-secondary, #9E5A59);
}
CSSEOF
fi

# Créer un fichier JS avec Bootstrap
if [ ! -f "public/build/assets/app.js" ]; then
    echo "Création d'un fichier JS avec Bootstrap..."
    cat > public/build/assets/app.js << 'JSEOF'
// Bootstrap JavaScript (chargé depuis CDN dans le fallback)
// Code JavaScript personnalisé peut être ajouté ici

// Initialisation des composants Bootstrap si nécessaire
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les tooltips Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Initialiser les popovers Bootstrap
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
});
JSEOF
fi

echo "✅ Manifest.json créé !"
echo ""
echo "⚠️  NOTE: Les fichiers CSS/JS sont minimaux."
echo "   Pour les styles complets, compilez avec: npm run build"
echo ""
echo "Vérification:"
ls -lh public/build/manifest.json 2>/dev/null || echo "❌ Erreur: manifest.json non créé"


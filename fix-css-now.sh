#!/bin/bash

# Script de correction immédiate pour le CSS
# Force la création du manifest et des fichiers CSS/JS

echo "🔧 Correction immédiate du CSS..."

cd "$(dirname "$0")"

# Supprimer l'ancien build pour forcer la régénération
rm -rf public/build
mkdir -p public/build/assets

# Créer le manifest.json
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

# Créer un fichier CSS complet avec Bootstrap
cat > public/build/assets/app.css << 'CSSEOF'
/* Bootstrap CSS - Import depuis CDN */
@import url('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css');

/* Google Fonts */
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');

/* Reset et styles de base */
* {
    font-family: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif !important;
    box-sizing: border-box;
}

body {
    font-family: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif !important;
    margin: 0;
    padding: 0;
    overflow-x: hidden;
}

h1, h2, h3, h4, h5, h6 {
    font-family: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif !important;
    font-weight: 700;
}

/* Navigation */
.navbar {
    padding: 1rem 0;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.navbar-brand {
    transition: transform 0.3s ease;
    font-weight: 700;
}

.navbar-brand:hover {
    transform: scale(1.05);
}

.nav-link {
    font-weight: 600 !important;
    padding: 0.75rem 1.25rem !important;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.nav-link:hover {
    background-color: rgba(0,0,0,0.05);
}

/* Boutons */
.btn {
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
}

.btn-primary {
    background-color: var(--color-primary, #A66060);
    border-color: var(--color-primary, #A66060);
    color: white;
}

.btn-primary:hover {
    background-color: var(--color-secondary, #9E5A59);
    border-color: var(--color-secondary, #9E5A59);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.btn-secondary {
    background-color: var(--color-secondary, #9E5A59);
    border-color: var(--color-secondary, #9E5A59);
    color: white;
}

/* Cards */
.card {
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    border: none;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.15);
}

/* Formulaires */
.form-control, .form-select {
    border-radius: 8px;
    padding: 0.75rem 1rem;
    border: 1px solid #ddd;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: var(--color-primary, #A66060);
    box-shadow: 0 0 0 0.2rem rgba(166, 96, 96, 0.25);
}

/* Sections */
section {
    padding: 4rem 0;
}

/* Hero Section */
.hero-section {
    min-height: 70vh;
    display: flex;
    align-items: center;
    background-size: cover;
    background-position: center;
    position: relative;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(166, 96, 96, 0.7) 0%, rgba(158, 90, 89, 0.7) 100%);
}

.hero-content {
    position: relative;
    z-index: 1;
    color: white;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-section {
        min-height: 50vh;
    }
    
    section {
        padding: 2rem 0;
    }
}
CSSEOF

# Créer un fichier JS avec Bootstrap
cat > public/build/assets/app.js << 'JSEOF'
// Bootstrap JavaScript et initialisation
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
    
    // Smooth scroll pour les ancres
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
JSEOF

# Vérifier les permissions
chmod 644 public/build/manifest.json
chmod 644 public/build/assets/app.css
chmod 644 public/build/assets/app.js

# Vider tous les caches
if command -v php &> /dev/null; then
    php artisan view:clear 2>/dev/null || true
    php artisan config:clear 2>/dev/null || true
    php artisan cache:clear 2>/dev/null || true
    php artisan route:clear 2>/dev/null || true
fi

echo ""
echo "✅ Fichiers créés :"
echo "   - public/build/manifest.json"
echo "   - public/build/assets/app.css"
echo "   - public/build/assets/app.js"
echo ""
echo "✅ Caches vidés"
echo ""
echo "🔍 Vérification :"
ls -lh public/build/manifest.json
ls -lh public/build/assets/app.css
echo ""
echo "✨ Terminé ! Rafraîchissez votre navigateur avec Ctrl+F5 (ou Cmd+Shift+R sur Mac)"


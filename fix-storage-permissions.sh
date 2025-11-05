#!/bin/bash

# Script pour corriger les permissions et l'accès au storage
# Usage: ./fix-storage-permissions.sh

echo "🔧 Correction des permissions du storage..."

# Vérifier que le lien symbolique existe
if [ ! -L "public/storage" ]; then
    echo "⚠️  Le lien symbolique public/storage n'existe pas"
    echo "   Création du lien..."
    
    if [ -d "storage/app/public" ]; then
        ln -s ../storage/app/public public/storage
        echo "✅ Lien créé"
    else
        echo "❌ Le dossier storage/app/public n'existe pas"
        echo "   Création du dossier..."
        mkdir -p storage/app/public
        echo "✅ Dossier créé"
        ln -s ../storage/app/public public/storage
        echo "✅ Lien créé"
    fi
else
    echo "✅ Lien symbolique existe"
    ls -la public/storage
fi

echo ""
echo "🔐 Configuration des permissions..."

# Permissions pour les dossiers
chmod 755 storage 2>/dev/null || true
chmod 755 storage/app 2>/dev/null || true
chmod 755 storage/app/public 2>/dev/null || true
chmod 755 public 2>/dev/null || true
chmod 755 public/storage 2>/dev/null || true

# Permissions pour les fichiers
find storage/app/public -type d -exec chmod 755 {} \; 2>/dev/null || true
find storage/app/public -type f -exec chmod 644 {} \; 2>/dev/null || true

# Vérifier le propriétaire (peut nécessiter sudo selon la configuration)
echo ""
echo "📁 Permissions actuelles:"
ls -la storage/app/ | head -5
ls -la storage/app/public/ | head -5
ls -la public/ | grep storage

# Créer le .htaccess dans storage/app/public si nécessaire
if [ ! -f "storage/app/public/.htaccess" ]; then
    echo ""
    echo "📝 Création du .htaccess dans storage/app/public..."
    cat > storage/app/public/.htaccess << 'HTACCESSEOF'
# Autoriser l'accès aux fichiers dans storage
Options -Indexes

# Autoriser l'accès à tous les fichiers
<FilesMatch ".*">
    Require all granted
</FilesMatch>

# Alternative pour les anciennes versions d'Apache
<IfModule !mod_authz_core.c>
    Order allow,deny
    Allow from all
</IfModule>

# Autoriser l'accès aux fichiers spécifiques
<FilesMatch "\.(jpg|jpeg|png|gif|webp|pdf|doc|docx|xls|xlsx|txt|csv|zip|mp4|mp3)$">
    Require all granted
</FilesMatch>

# Désactiver l'exécution de PHP dans ce dossier
<FilesMatch "\.php$">
    Require all denied
</FilesMatch>
HTACCESSEOF
    chmod 644 storage/app/public/.htaccess
    echo "✅ .htaccess créé"
fi

echo ""
echo "✅ Permissions configurées !"
echo ""
echo "🔍 Vérification du lien:"
if [ -L "public/storage" ]; then
    echo "   ✅ Lien symbolique: $(readlink public/storage)"
    if [ -d "storage/app/public" ]; then
        echo "   ✅ Dossier cible existe"
        echo "   📊 Contenu:"
        ls -la storage/app/public/ | head -10
    else
        echo "   ❌ Dossier cible n'existe pas"
    fi
else
    echo "   ❌ Lien symbolique n'existe pas"
fi

echo ""
echo "📝 Note: Si vous avez toujours une erreur 403, vérifiez:"
echo "   1. Que le serveur web peut lire les fichiers (propriétaire/groupe)"
echo "   2. Que le .htaccess ne bloque pas l'accès"
echo "   3. Les logs d'erreur Apache/Nginx"


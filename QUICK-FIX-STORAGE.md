# 🔧 Correction Rapide - Erreur 403 Storage

## Solution immédiate

Sur votre serveur SSH, exécutez ces commandes dans l'ordre :

```bash
cd /home/u570136219/domains/iesc.osmoseconsulting.fr/public_html

# 1. Mettre à jour le code depuis GitHub
git pull origin main

# 2. Créer le lien symbolique (si pas déjà fait)
ln -s ../storage/app/public public/storage 2>/dev/null || true

# 3. Créer le .htaccess dans storage/app/public
cat > storage/app/public/.htaccess << 'EOF'
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
EOF

# 4. Configurer les permissions
chmod -R 755 storage/app/public
find storage/app/public -type f -exec chmod 644 {} \;
chmod 644 storage/app/public/.htaccess
chmod 755 public

# 5. Vérifier
ls -la public/storage
ls -la storage/app/public/.htaccess
```

## Vérification

Testez l'accès à une image :
```
https://iesc.osmoseconsulting.fr/storage/profiles/nom-fichier.jpg
```

Si ça ne fonctionne toujours pas, vérifiez :
1. Que les fichiers existent : `ls -la storage/app/public/profiles/`
2. Que le lien symbolique existe : `ls -la public/storage`
3. Les logs d'erreur Apache : `tail -f /var/log/apache2/error.log` (ou chemin selon votre config)


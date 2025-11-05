# 📤 Guide : Upload des Images par FTP

## 📁 Dossiers à envoyer par FTP

Pour avoir toutes vos images locales disponibles en ligne, vous devez envoyer ces dossiers :

### 1. **Dossier principal : `storage/app/public/`**

C'est le dossier le plus important ! Il contient toutes les images et fichiers uploadés :

```
storage/app/public/
├── profiles/          # Photos de profil des utilisateurs
├── candidatures/      # Documents de candidature (CV, diplômes, etc.)
├── ressources/        # Ressources pédagogiques uploadées
├── actualites/        # Images des actualités
├── evenements/        # Images des événements
└── (autres fichiers uploadés)
```

**Chemin complet sur le serveur :**
```
/home/u570136219/domains/iesc.osmoseconsulting.fr/public_html/storage/app/public/
```

### 2. **Vérifier le lien symbolique : `public/storage/`**

Après avoir uploadé `storage/app/public/`, vous devez créer le lien symbolique :

```bash
# Sur le serveur SSH
cd /home/u570136219/domains/iesc.osmoseconsulting.fr/public_html
php artisan storage:link
```

Cela crée un lien de `public/storage` vers `storage/app/public` pour que les images soient accessibles via l'URL `/storage/`.

### 3. **Si vous avez des images dans `public/` directement**

Si vous avez des images statiques dans `public/` (comme logos, favicon, etc.) :

```
public/
├── favicon.ico
├── images/      # Si vous avez un dossier images
├── logos/        # Si vous avez un dossier logos
└── (autres fichiers statiques)
```

## 🔧 Instructions étape par étape

### Option A : Via FTP (FileZilla, WinSCP, etc.)

1. **Connectez-vous à votre serveur FTP**
   - Hôte : `iesc.osmoseconsulting.fr` (ou l'adresse fournie par votre hébergeur)
   - Utilisateur : `u570136219`
   - Mot de passe : (votre mot de passe FTP)

2. **Naviguez vers le dossier du site**
   ```
   /home/u570136219/domains/iesc.osmoseconsulting.fr/public_html/
   ```

3. **Uploadez le dossier `storage/app/public/`**
   - Dans FileZilla : Glissez-déposez le dossier `storage/app/public/` de votre machine locale
   - Vers : `/home/u570136219/domains/iesc.osmoseconsulting.fr/public_html/storage/app/public/`
   - **Important** : Conservez la structure des sous-dossiers (profiles/, candidatures/, etc.)

4. **Vérifiez les permissions**
   - Les dossiers doivent être en `755`
   - Les fichiers doivent être en `644`
   - Sur certains serveurs, vous pouvez avoir besoin de `775` pour les dossiers

### Option B : Via SSH (rsync - plus rapide)

Depuis votre machine locale :

```bash
cd /Applications/XAMPP/xamppfiles/htdocs/IECS2

# Copier le dossier storage/app/public
rsync -avz --progress storage/app/public/ u570136219@iesc.osmoseconsulting.fr:/home/u570136219/domains/iesc.osmoseconsulting.fr/public_html/storage/app/public/

# Créer le lien symbolique
ssh u570136219@iesc.osmoseconsulting.fr "cd /home/u570136219/domains/iesc.osmoseconsulting.fr/public_html && php artisan storage:link"
```

### Option C : Via le script de déploiement

Le script `deploy.sh` devrait déjà gérer cela, mais vous pouvez aussi créer un script dédié :

```bash
# Sur le serveur
cd /home/u570136219/domains/iesc.osmoseconsulting.fr/public_html
./deploy.sh
```

## ✅ Vérification après upload

1. **Vérifier que les fichiers sont présents :**
   ```bash
   # Sur le serveur SSH
   ls -la storage/app/public/
   ls -la storage/app/public/profiles/
   ls -la storage/app/public/candidatures/
   ```

2. **Vérifier le lien symbolique :**
   ```bash
   ls -la public/storage
   # Doit afficher : public/storage -> ../storage/app/public
   ```

3. **Tester l'accès à une image :**
   - Ouvrez dans votre navigateur : `https://iesc.osmoseconsulting.fr/storage/profiles/nom-fichier.jpg`
   - Si ça fonctionne, les images sont bien accessibles !

## 📊 Structure complète à uploader

```
storage/
└── app/
    └── public/
        ├── profiles/          ✅ Uploader
        ├── candidatures/      ✅ Uploader
        ├── ressources/        ✅ Uploader
        ├── actualites/        ✅ Uploader (si vous avez des images d'actualités)
        └── evenements/        ✅ Uploader (si vous avez des images d'événements)
```

## ⚠️ Points importants

1. **Permissions** : Assurez-vous que les permissions sont correctes :
   ```bash
   chmod -R 755 storage/app/public
   find storage/app/public -type f -exec chmod 644 {} \;
   ```

2. **Lien symbolique** : N'oubliez pas d'exécuter `php artisan storage:link` après l'upload

3. **Taille des fichiers** : Si vous avez beaucoup d'images, l'upload peut prendre du temps

4. **Sauvegarde** : Faites une sauvegarde des fichiers existants sur le serveur avant de les remplacer

## 🚀 Script automatique

Vous pouvez aussi créer un script `upload-images.sh` :

```bash
#!/bin/bash
# Upload des images vers le serveur

LOCAL_PATH="/Applications/XAMPP/xamppfiles/htdocs/IECS2"
SERVER="u570136219@iesc.osmoseconsulting.fr"
SERVER_PATH="/home/u570136219/domains/iesc.osmoseconsulting.fr/public_html"

echo "📤 Upload des images..."
rsync -avz --progress "$LOCAL_PATH/storage/app/public/" "$SERVER:$SERVER_PATH/storage/app/public/"

echo "✅ Upload terminé !"
echo "🔗 Création du lien symbolique..."
ssh "$SERVER" "cd $SERVER_PATH && php artisan storage:link"

echo "✨ Terminé !"
```


# Guide de Déploiement IECS2

## Script de Déploiement Automatique

Ce script automatise le déploiement complet de l'application sur le serveur de production.

## Prérequis

- Accès SSH au serveur
- PHP 8.2+ avec extensions : pdo_mysql, pdo_sqlite, mbstring, xml, curl, zip
- Composer (ou possibilité de le télécharger)
- MySQL/MariaDB avec les identifiants configurés
- Git installé
- Node.js et npm (optionnel, pour la compilation des assets)

## Configuration de la Base de Données

Les identifiants MySQL sont configurés dans le script :
- **Utilisateur** : `u570136219_uni`
- **Base de données** : `u570136219_uni`
- **Mot de passe** : `Harajuku1993@`
- **Hôte** : `localhost`

## Utilisation

### 1. Se connecter en SSH au serveur

```bash
ssh votre-utilisateur@votre-serveur.com
```

### 2. Naviguer vers le répertoire du projet

```bash
cd /home/u570136219/public_html
```

### 3. Télécharger le script (si pas encore présent)

Si le dépôt Git n'est pas encore cloné :

```bash
git clone https://github.com/Harajuku13z/iecs2.git .
```

### 4. Rendre le script exécutable

```bash
chmod +x deploy.sh
```

### 5. Exécuter le script

```bash
./deploy.sh
```

## Ce que fait le script

1. ✅ **Mise à jour du code** : Pull depuis GitHub
2. ✅ **Installation des dépendances** : Composer et NPM
3. ✅ **Compilation des assets** : Build des fichiers CSS/JS
4. ✅ **Configuration .env** : Configuration automatique de MySQL et production
5. ✅ **Génération de la clé** : APP_KEY si nécessaire
6. ✅ **Migrations** : Création des tables MySQL
7. ✅ **Migration des données** : Transfert SQLite → MySQL (si disponible)
8. ✅ **Seeders** : Données de test si pas de SQLite
9. ✅ **Lien symbolique** : Création du lien storage
10. ✅ **Optimisation** : Cache config, routes, views
11. ✅ **Permissions** : Configuration des permissions fichiers/dossiers
12. ✅ **Nettoyage** : Suppression des fichiers de debug

## Migration des Données SQLite → MySQL

Le script détecte automatiquement si un fichier `database/database.sqlite` existe :

- **Si présent** : Les données sont migrées automatiquement vers MySQL
- **Si absent** : Les seeders sont exécutés pour créer des données de test

### Commandes de migration manuelle

Si vous voulez migrer manuellement :

```bash
php artisan migrate:sqlite-to-mysql --force
```

## Vérifications Post-Déploiement

### 1. Vérifier le DocumentRoot

Le DocumentRoot d'Apache doit pointer vers :
```
/home/u570136219/public_html/public
```

**OU** si le DocumentRoot pointe vers `/home/u570136219/public_html`, le `.htaccess` à la racine redirigera automatiquement.

### 2. Vérifier mod_rewrite

```bash
php -i | grep -i rewrite
```

Ou dans votre configuration Apache, vérifiez que `mod_rewrite` est activé.

### 3. Vérifier les permissions

```bash
ls -la storage/
ls -la bootstrap/cache/
```

Les dossiers doivent être en `775` et les fichiers en `664`.

### 4. Tester l'application

Visitez votre site et vérifiez :
- ✅ La page d'accueil s'affiche
- ✅ Les connexions fonctionnent
- ✅ Les images/ressources se chargent
- ✅ Les formulaires fonctionnent

## Dépannage

### Erreur 403 Forbidden

1. Vérifiez que le DocumentRoot pointe vers `public/`
2. Vérifiez que `mod_rewrite` est activé
3. Vérifiez les permissions du `.htaccess`

### Page blanche

1. Activez temporairement le debug : `APP_DEBUG=true` dans `.env`
2. Vérifiez les logs : `storage/logs/laravel.log`
3. Vérifiez les erreurs PHP du serveur

### Erreur de connexion MySQL

1. Vérifiez les identifiants dans `.env`
2. Vérifiez que la base de données existe
3. Testez la connexion : `php artisan db:show`

### Erreur lors de la migration des données

Le script basculera automatiquement sur les seeders si la migration échoue.

## Mise à jour Ultérieure

Pour mettre à jour le site après des modifications :

```bash
cd /home/u570136219/public_html
./deploy.sh
```

Ou simplement :

```bash
cd /home/u570136219/public_html
git pull origin main
composer install --no-dev --optimize-autoloader
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Accès par Défaut

Après le déploiement avec les seeders :

- **Email** : `admin@iesca.com`
- **Mot de passe** : `password`

⚠️ **Changez ces identifiants immédiatement en production !**


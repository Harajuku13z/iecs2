# 🔧 Correction Rapide - Page Blanche

## Problème identifié
Le fichier `vendor/autoload.php` est manquant, ce qui empêche Laravel de démarrer.

## Solution immédiate

Sur votre serveur SSH, exécutez :

```bash
cd /home/u570136219/domains/iesc.osmoseconsulting.fr/public_html

# Option 1: Utiliser le script de correction
git pull origin main
chmod +x fix-vendor.sh
./fix-vendor.sh

# Option 2: Installation manuelle
composer install --no-dev --optimize-autoloader

# Si composer n'est pas installé globalement
curl -sS https://getcomposer.org/installer | php
php composer.phar install --no-dev --optimize-autoloader
```

## Vérification

Après l'installation, vérifiez :

```bash
ls -lh vendor/autoload.php
```

Le fichier doit exister et faire environ quelques centaines de KB.

## Après correction

Une fois `vendor/autoload.php` créé, votre site devrait fonctionner.

Si vous avez encore des problèmes, vérifiez :
1. Les logs : `tail -f storage/logs/laravel.log`
2. Les permissions : `chmod -R 775 storage bootstrap/cache`
3. Le cache : `php artisan config:clear`


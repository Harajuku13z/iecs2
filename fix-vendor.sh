#!/bin/bash

# Script de correction rapide pour installer les dépendances
# Usage: ./fix-vendor.sh

echo "Installation des dépendances Composer..."

# Vérifier si composer est disponible
if command -v composer &> /dev/null; then
    COMPOSER_CMD="composer"
elif [ -f "composer.phar" ]; then
    COMPOSER_CMD="php composer.phar"
else
    echo "Téléchargement de Composer..."
    curl -sS https://getcomposer.org/installer | php
    COMPOSER_CMD="php composer.phar"
fi

# Installer les dépendances
echo "Installation des dépendances (cela peut prendre quelques minutes)..."
$COMPOSER_CMD install --no-dev --optimize-autoloader --no-interaction

# Vérifier que vendor/autoload.php existe maintenant
if [ -f "vendor/autoload.php" ]; then
    echo "✅ Dépendances installées avec succès !"
    echo ""
    echo "Vérification:"
    ls -lh vendor/autoload.php
else
    echo "❌ Erreur: vendor/autoload.php n'a pas été créé"
    echo "Vérifiez les erreurs ci-dessus"
    exit 1
fi


#!/bin/bash

# Script pour créer le lien symbolique storage manuellement
# Usage: ./create-storage-link.sh

echo "🔗 Création du lien symbolique storage..."

# Vérifier si on est dans le bon répertoire
if [ ! -f "artisan" ]; then
    echo "❌ Erreur: Vous devez être dans la racine du projet Laravel"
    exit 1
fi

# Chemin vers storage/app/public
STORAGE_PATH="storage/app/public"
PUBLIC_STORAGE="public/storage"

# Vérifier que storage/app/public existe
if [ ! -d "$STORAGE_PATH" ]; then
    echo "❌ Erreur: Le dossier $STORAGE_PATH n'existe pas"
    echo "   Créez-le d'abord: mkdir -p $STORAGE_PATH"
    exit 1
fi

# Supprimer le lien existant s'il existe
if [ -L "$PUBLIC_STORAGE" ]; then
    echo "📦 Suppression de l'ancien lien..."
    rm "$PUBLIC_STORAGE"
elif [ -e "$PUBLIC_STORAGE" ]; then
    echo "⚠️  Attention: $PUBLIC_STORAGE existe déjà (ce n'est pas un lien)"
    echo "   Voulez-vous le supprimer ? (y/n)"
    read -r response
    if [ "$response" = "y" ]; then
        rm -rf "$PUBLIC_STORAGE"
    else
        echo "❌ Opération annulée"
        exit 1
    fi
fi

# Créer le lien symbolique
echo "🔗 Création du lien symbolique..."
ln -s "../$STORAGE_PATH" "$PUBLIC_STORAGE"

if [ $? -eq 0 ]; then
    echo "✅ Lien symbolique créé avec succès !"
    echo ""
    echo "📁 Vérification:"
    ls -la "$PUBLIC_STORAGE"
    echo ""
    echo "✨ Le lien pointe vers: $(readlink $PUBLIC_STORAGE)"
else
    echo "❌ Erreur lors de la création du lien"
    exit 1
fi


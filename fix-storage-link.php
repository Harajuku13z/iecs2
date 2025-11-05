<?php
/**
 * Script PHP pour créer le lien symbolique storage
 * Alternative à php artisan storage:link quand exec() est désactivé
 * 
 * Usage: php fix-storage-link.php
 */

$storagePath = __DIR__ . '/storage/app/public';
$publicStorage = __DIR__ . '/public/storage';

echo "🔗 Création du lien symbolique storage...\n\n";

// Vérifier que storage/app/public existe
if (!is_dir($storagePath)) {
    echo "❌ Erreur: Le dossier storage/app/public n'existe pas\n";
    echo "   Créez-le d'abord: mkdir -p storage/app/public\n";
    exit(1);
}

// Supprimer l'ancien lien s'il existe
if (is_link($publicStorage)) {
    echo "📦 Suppression de l'ancien lien...\n";
    unlink($publicStorage);
} elseif (file_exists($publicStorage)) {
    echo "⚠️  Attention: public/storage existe déjà (ce n'est pas un lien)\n";
    echo "   Suppression...\n";
    if (is_dir($publicStorage)) {
        rmdir($publicStorage);
    } else {
        unlink($publicStorage);
    }
}

// Créer le lien symbolique
echo "🔗 Création du lien symbolique...\n";

// Utiliser symlink() directement
$target = '../storage/app/public';
$link = 'public/storage';

if (symlink($target, $link)) {
    echo "✅ Lien symbolique créé avec succès !\n\n";
    echo "📁 Vérification:\n";
    if (is_link($link)) {
        echo "   ✅ Lien créé: $link -> " . readlink($link) . "\n";
    } else {
        echo "   ⚠️  Le lien semble exister mais n'est pas détecté comme lien symbolique\n";
    }
} else {
    echo "❌ Erreur: Impossible de créer le lien symbolique\n";
    echo "   Vérifiez les permissions du dossier public/\n";
    echo "   Essayez: chmod 755 public\n";
    exit(1);
}

echo "\n✨ Terminé !\n";


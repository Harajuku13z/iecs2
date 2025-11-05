<?php
/**
 * Script de diagnostic serveur
 * Accédez à ce fichier via votre navigateur pour diagnostiquer les problèmes
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Diagnostic Serveur - IECS2</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        h1 { color: #333; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        .info { background: #e3f2fd; padding: 10px; margin: 10px 0; border-left: 4px solid #2196F3; }
        .success-box { background: #e8f5e9; padding: 10px; margin: 10px 0; border-left: 4px solid #4CAF50; }
        .error-box { background: #ffebee; padding: 10px; margin: 10px 0; border-left: 4px solid #f44336; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Diagnostic Serveur - IECS2</h1>
        
        <h2>1. Informations PHP</h2>
        <table>
            <tr><th>Propriété</th><th>Valeur</th></tr>
            <tr><td>Version PHP</td><td><?php echo phpversion(); ?></td></tr>
            <tr><td>Serveur</td><td><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'N/A'; ?></td></tr>
            <tr><td>Document Root</td><td><?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'N/A'; ?></td></tr>
            <tr><td>Script Filename</td><td><?php echo __FILE__; ?></td></tr>
            <tr><td>Current Directory</td><td><?php echo __DIR__; ?></td></tr>
        </table>

        <h2>2. Vérification des Modules Apache</h2>
        <?php
        $modules = ['mod_rewrite', 'mod_headers'];
        foreach ($modules as $module) {
            if (function_exists('apache_get_modules')) {
                $loaded = in_array($module, apache_get_modules());
                echo '<div class="' . ($loaded ? 'success-box' : 'error-box') . '">';
                echo $module . ': ' . ($loaded ? '✅ Activé' : '❌ Non activé');
                echo '</div>';
            } else {
                echo '<div class="info">Fonction apache_get_modules() non disponible</div>';
            }
        }
        ?>

        <h2>3. Vérification des Fichiers</h2>
        <?php
        $files = [
            '.htaccess (racine)' => __DIR__ . '/.htaccess',
            'public/.htaccess' => __DIR__ . '/public/.htaccess',
            'public/index.php' => __DIR__ . '/public/index.php',
            '.env' => __DIR__ . '/.env',
            'vendor/autoload.php' => __DIR__ . '/vendor/autoload.php',
        ];

        foreach ($files as $name => $path) {
            $exists = file_exists($path);
            $readable = $exists && is_readable($path);
            echo '<div class="' . ($exists && $readable ? 'success-box' : 'error-box') . '">';
            echo $name . ': ';
            if ($exists && $readable) {
                echo '✅ Existe et lisible';
                if ($name === '.htaccess (racine)' || $name === 'public/.htaccess') {
                    $content = file_get_contents($path);
                    echo ' (' . strlen($content) . ' octets)';
                }
            } else {
                echo '❌ ' . ($exists ? 'Non lisible' : 'N\'existe pas');
            }
            echo '</div>';
        }
        ?>

        <h2>4. Vérification des Permissions</h2>
        <?php
        $dirs = [
            'storage' => __DIR__ . '/storage',
            'bootstrap/cache' => __DIR__ . '/bootstrap/cache',
            'public' => __DIR__ . '/public',
        ];

        foreach ($dirs as $name => $path) {
            if (file_exists($path)) {
                $perms = substr(sprintf('%o', fileperms($path)), -4);
                $writable = is_writable($path);
                echo '<div class="' . ($writable ? 'success-box' : 'error-box') . '">';
                echo $name . ': Permissions ' . $perms . ' - ' . ($writable ? '✅ Écriture possible' : '❌ Écriture impossible');
                echo '</div>';
            }
        }
        ?>

        <h2>5. Test de Redirection</h2>
        <div class="info">
            <strong>URL actuelle:</strong> <?php echo $_SERVER['REQUEST_URI']; ?><br>
            <strong>Chemin vers public:</strong> <a href="/public/">/public/</a><br>
            <strong>Test index.php public:</strong> <a href="/public/index.php">/public/index.php</a>
        </div>

        <h2>6. Configuration Laravel</h2>
        <?php
        $envPath = __DIR__ . '/.env';
        if (file_exists($envPath)) {
            $envContent = file_get_contents($envPath);
            $dbConfig = [];
            foreach (['DB_CONNECTION', 'DB_HOST', 'DB_DATABASE', 'DB_USERNAME'] as $key) {
                if (preg_match("/^{$key}=(.+)$/m", $envContent, $matches)) {
                    $dbConfig[$key] = trim($matches[1]);
                }
            }
            
            echo '<table>';
            foreach ($dbConfig as $key => $value) {
                echo "<tr><td>{$key}</td><td>{$value}</td></tr>";
            }
            echo '</table>';
        } else {
            echo '<div class="error-box">Fichier .env non trouvé</div>';
        }
        ?>

        <h2>7. Recommandations</h2>
        <div class="info">
            <strong>Si vous avez une erreur 403:</strong><br>
            1. Vérifiez que mod_rewrite est activé<br>
            2. Vérifiez que le DocumentRoot pointe vers <code>/home/u570136219/public_html/public</code><br>
            3. Si le DocumentRoot pointe vers la racine, le fichier <code>index.php</code> à la racine devrait rediriger<br>
            4. Vérifiez les permissions: <code>chmod 755 public</code> et <code>chmod 644 .htaccess</code>
        </div>

        <h2>8. Actions</h2>
        <div class="info">
            <a href="/public/">→ Accéder à /public/</a><br>
            <a href="/public/index.php">→ Accéder à /public/index.php</a><br>
            <a href="/">→ Tester la racine (devrait rediriger)</a>
        </div>
    </div>
</body>
</html>


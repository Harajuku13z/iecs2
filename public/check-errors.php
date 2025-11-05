<?php
/**
 * Script de diagnostic des erreurs 500
 * Affiche les erreurs PHP et Laravel
 * Accessible directement depuis /public/check-errors.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Diagnostic Erreurs - IECS2</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        h1 { color: #333; }
        .error { color: red; background: #ffebee; padding: 10px; margin: 10px 0; border-left: 4px solid #f44336; }
        .success { color: green; background: #e8f5e9; padding: 10px; margin: 10px 0; border-left: 4px solid #4CAF50; }
        .warning { color: orange; background: #fff3e0; padding: 10px; margin: 10px 0; border-left: 4px solid #ff9800; }
        .info { background: #e3f2fd; padding: 10px; margin: 10px 0; border-left: 4px solid #2196F3; }
        pre { background: #f5f5f5; padding: 10px; overflow-x: auto; border: 1px solid #ddd; max-height: 400px; overflow-y: auto; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Diagnostic Erreurs 500</h1>

        <h2>1. Vérification PHP</h2>
        <?php
        echo '<div class="success">Version PHP: ' . phpversion() . '</div>';
        
        $required = ['pdo', 'pdo_mysql', 'mbstring', 'xml', 'openssl', 'curl', 'zip'];
        foreach ($required as $ext) {
            $loaded = extension_loaded($ext);
            echo '<div class="' . ($loaded ? 'success' : 'error') . '">';
            echo $ext . ': ' . ($loaded ? '✅ Chargé' : '❌ Non chargé');
            echo '</div>';
        }
        ?>

        <h2>2. Vérification des Fichiers Essentiels</h2>
        <?php
        $baseDir = dirname(__DIR__);
        $files = [
            'vendor/autoload.php' => $baseDir . '/vendor/autoload.php',
            'bootstrap/app.php' => $baseDir . '/bootstrap/app.php',
            '.env' => $baseDir . '/.env',
            'storage/logs/laravel.log' => $baseDir . '/storage/logs/laravel.log',
        ];

        foreach ($files as $name => $path) {
            $exists = file_exists($path);
            $readable = $exists && is_readable($path);
            echo '<div class="' . ($exists && $readable ? 'success' : 'error') . '">';
            echo $name . ': ';
            if ($exists && $readable) {
                echo '✅ OK';
                if ($name === 'vendor/autoload.php') {
                    echo ' (' . number_format(filesize($path)) . ' octets)';
                }
            } else {
                echo '❌ ' . ($exists ? 'Non lisible' : 'N\'existe pas');
                echo ' <small>Chemin: ' . htmlspecialchars($path) . '</small>';
            }
            echo '</div>';
        }
        ?>

        <h2>3. Test de Chargement Laravel</h2>
        <?php
        try {
            $vendorPath = $baseDir . '/vendor/autoload.php';
            if (!file_exists($vendorPath)) {
                throw new Exception('vendor/autoload.php n\'existe pas');
            }
            
            require $vendorPath;
            echo '<div class="success">✅ vendor/autoload.php chargé avec succès</div>';
            
            $appPath = $baseDir . '/bootstrap/app.php';
            if (!file_exists($appPath)) {
                throw new Exception('bootstrap/app.php n\'existe pas');
            }
            
            $app = require_once $appPath;
            echo '<div class="success">✅ Laravel app initialisée</div>';
        } catch (Throwable $e) {
            echo '<div class="error">';
            echo '<strong>❌ Erreur lors du chargement de Laravel:</strong><br>';
            echo 'Message: ' . htmlspecialchars($e->getMessage()) . '<br>';
            echo 'Fichier: ' . $e->getFile() . '<br>';
            echo 'Ligne: ' . $e->getLine() . '<br>';
            echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
            echo '</div>';
        }
        ?>

        <h2>4. Configuration .env</h2>
        <?php
        $envPath = $baseDir . '/.env';
        if (file_exists($envPath)) {
            $envContent = file_get_contents($envPath);
            $important = ['APP_ENV', 'APP_DEBUG', 'DB_CONNECTION', 'DB_HOST', 'DB_DATABASE', 'DB_USERNAME', 'APP_KEY'];
            
            echo '<table>';
            echo '<tr><th>Variable</th><th>Valeur</th></tr>';
            foreach ($important as $key) {
                if (preg_match("/^{$key}=(.+)$/m", $envContent, $matches)) {
                    $value = trim($matches[1]);
                    if ($key === 'APP_KEY') {
                        $value = substr($value, 0, 30) . '...';
                    }
                    echo "<tr><td>{$key}</td><td>{$value}</td></tr>";
                } else {
                    echo "<tr><td>{$key}</td><td style='color:red;'>❌ Non défini</td></tr>";
                }
            }
            echo '</table>';
        } else {
            echo '<div class="error">Fichier .env non trouvé à: ' . htmlspecialchars($envPath) . '</div>';
        }
        ?>

        <h2>5. Dernières Erreurs du Log Laravel</h2>
        <?php
        $logPath = $baseDir . '/storage/logs/laravel.log';
        if (file_exists($logPath)) {
            $lines = file($logPath);
            $lastLines = array_slice($lines, -100);
            echo '<pre>';
            echo htmlspecialchars(implode('', $lastLines));
            echo '</pre>';
        } else {
            echo '<div class="warning">Fichier de log non trouvé à: ' . htmlspecialchars($logPath) . '</div>';
        }
        ?>

        <h2>6. Test de Connexion Base de Données</h2>
        <?php
        if (file_exists($envPath)) {
            $envContent = file_get_contents($envPath);
            if (preg_match("/^DB_CONNECTION=(.+)$/m", $envContent, $matches)) {
                $dbConnection = trim($matches[1]);
                if ($dbConnection === 'mysql') {
                    try {
                        require $baseDir . '/vendor/autoload.php';
                        $app = require_once $baseDir . '/bootstrap/app.php';
                        $db = \Illuminate\Support\Facades\DB::connection();
                        $db->getPdo();
                        echo '<div class="success">✅ Connexion à la base de données réussie</div>';
                    } catch (Exception $e) {
                        echo '<div class="error">';
                        echo '❌ Erreur de connexion: ' . htmlspecialchars($e->getMessage());
                        echo '</div>';
                    }
                }
            }
        }
        ?>

        <h2>7. Informations Serveur</h2>
        <table>
            <tr><th>Propriété</th><th>Valeur</th></tr>
            <tr><td>Document Root</td><td><?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'N/A'; ?></td></tr>
            <tr><td>Script Filename</td><td><?php echo __FILE__; ?></td></tr>
            <tr><td>Base Directory</td><td><?php echo $baseDir; ?></td></tr>
            <tr><td>Public Directory</td><td><?php echo __DIR__; ?></td></tr>
        </table>

        <h2>8. Actions Recommandées</h2>
        <div class="info">
            <strong>Si vous avez une erreur 500:</strong><br>
            1. Vérifiez les logs ci-dessus<br>
            2. Exécutez en SSH: <code>php artisan config:clear</code><br>
            3. Vérifiez les permissions: <code>chmod -R 775 storage bootstrap/cache</code><br>
            4. Vérifiez que APP_KEY est défini dans .env<br>
            5. Testez la connexion à la base de données<br>
        </div>
    </div>
</body>
</html>


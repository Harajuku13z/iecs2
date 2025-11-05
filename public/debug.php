<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "PHP Version: " . phpversion() . "<br>";
echo "Current directory: " . __DIR__ . "<br>";
echo "Parent directory: " . dirname(__DIR__) . "<br>";
echo "<br>";

// Check if vendor exists
$vendorPath = dirname(__DIR__) . '/vendor/autoload.php';
echo "Vendor path: " . $vendorPath . "<br>";
echo "Vendor exists: " . (file_exists($vendorPath) ? 'YES' : 'NO') . "<br>";
echo "<br>";

// Check if bootstrap exists
$bootstrapPath = dirname(__DIR__) . '/bootstrap/app.php';
echo "Bootstrap path: " . $bootstrapPath . "<br>";
echo "Bootstrap exists: " . (file_exists($bootstrapPath) ? 'YES' : 'NO') . "<br>";
echo "<br>";

// Check if .env exists
$envPath = dirname(__DIR__) . '/.env';
echo ".env exists: " . (file_exists($envPath) ? 'YES' : 'NO') . "<br>";
echo "<br>";

// Try to load Laravel
try {
    require $vendorPath;
    echo "Vendor autoload loaded successfully<br>";
    
    $app = require_once $bootstrapPath;
    echo "Laravel app loaded successfully<br>";
    
    echo "App name: " . config('app.name') . "<br>";
    echo "App env: " . config('app.env') . "<br>";
    echo "App debug: " . (config('app.debug') ? 'true' : 'false') . "<br>";
    
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>

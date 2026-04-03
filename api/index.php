<?php

/**
 * TypeRush Vercel Diagnostic Center
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

echo "<html><head><title>TypeRush Diagnostic</title><style>body{font-family:monospace;background:#1a202c;color:#e2e8f0;padding:20px;}h1{color:#63b3ed;}b{color:#f6ad55;}</style></head><body>";
echo "<h1>📡 TypeRush Diagnostic Mission Control</h1>";

// 1. Check Key Environment Variables
echo "<h3>1. Environment Check</h3>";
echo "VERCEL: " . (getenv('VERCEL') ? "✅ Detected" : "❌ NOT Detected") . "<br>";
echo "APP_KEY: " . (getenv('APP_KEY') ? "✅ Set (".substr(getenv('APP_KEY'),0,10)."...)" : "❌ MISSING") . "<br>";
echo "PHP VERSION: " . PHP_VERSION . "<br>";

// 2. Check File Structure
echo "<h3>2. File System Check</h3>";
$critical_files = [
    '../vendor/autoload.php',
    '../bootstrap/app.php',
    '../public/index.php',
    '../bootstrap/cache/services.php',
    '../bootstrap/cache/packages.php'
];

foreach ($critical_files as $file) {
    $exists = file_exists(__DIR__ . '/' . $file);
    echo ($exists ? "✅ Found" : "❌ MISSING") . ": $file<br>";
}

// 3. Prepare writable storage
echo "<h3>3. Storage Preparation</h3>";
$storagePath = '/tmp/storage';
echo "Target: $storagePath<br>";
try {
    $dirs = [
        $storagePath . '/framework/views',
        $storagePath . '/framework/cache',
        $storagePath . '/framework/sessions',
        $storagePath . '/bootstrap/cache',
    ];
    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
            echo "✅ Created: $dir<br>";
        } else {
            echo "ℹ️ Exists: $dir<br>";
        }
    }
} catch (\Exception $e) {
    echo "❌ FAILED: " . $e->getMessage() . "<br>";
}

// 4. Attempt to Boot
echo "<h3>4. Boot Attempt</h3>";
try {
    echo "Attempting to require public/index.php...<br>";
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    echo "<hr><h2 style='color:#fc8181;'>🚨 BOOT CRASHED!</h2>";
    echo "<b>MESSAGE:</b> " . $e->getMessage() . "<br>";
    echo "<b>FILE:</b> " . $e->getFile() . " (Line " . $e->getLine() . ")<br>";
    echo "<b>STACK TRACE:</b><pre style='background:#2d3748;padding:10px;'>" . $e->getTraceAsString() . "</pre>";
}

echo "</body></html>";

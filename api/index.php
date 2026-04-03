<?php

/**
 * TypeRush Vercel Serverless Bridge - "True Error" Reveal Mode
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

// 1. Storage Preparation
$storagePath = '/tmp/storage';
if (getenv('VERCEL')) {
    $dirs = [
        $storagePath . '/framework/views',
        $storagePath . '/framework/cache',
        $storagePath . '/framework/sessions',
        $storagePath . '/bootstrap/cache',
    ];
    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
    putenv("LARAVEL_SERVICES_CACHE={$storagePath}/bootstrap/cache/services.php");
    putenv("LARAVEL_PACKAGES_CACHE={$storagePath}/bootstrap/cache/packages.php");
    putenv("LARAVEL_CONFIG_CACHE={$storagePath}/bootstrap/cache/config.php");
    putenv("LARAVEL_ROUTES_CACHE={$storagePath}/bootstrap/cache/routes.php");
}

// 2. Proactive Database Connection Test
try {
    $host = getenv('DB_HOST');
    $port = getenv('DB_PORT');
    $db   = getenv('DB_DATABASE');
    $user = getenv('DB_USERNAME');
    $pass = getenv('DB_PASSWORD');
    
    if ($host) {
        $dsn = "pgsql:host=$host;port=$port;dbname=$db";
        $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    }
} catch (\Exception $e) {
    die("<h1>🚨 Database Connection Failure</h1><p>The server could not connect to Supabase.</p><pre>" . $e->getMessage() . "</pre>");
}

// 3. Check for Inertia Manifest
$manifestPath = __DIR__ . '/../public/build/manifest.json';
if (!file_exists($manifestPath)) {
    echo "<h3>⚠️ Warning: public/build/manifest.json not found.</h3>";
}

// 4. Boot Laravel with First-Exception Catch
try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    // If it's the BindingResolutionException, we might have caught it too late.
    // But this catch block will see the ORIGINAL error if it bubbles up.
    echo "<h1>TypeRush Boot Error</h1>";
    echo "<h3>Original Exception: " . get_class($e) . "</h3>";
    echo "<pre>" . $e->getMessage() . "</pre>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
    exit(1);
}

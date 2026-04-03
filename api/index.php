<?php

/**
 * TypeRush Vercel Serverless Bridge
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

// 1. Prepare writable storage in /tmp
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

    // 2. Redirect Internal Laravel Maps to /tmp (CRITICAL for Vercel)
    putenv("LARAVEL_SERVICES_CACHE={$storagePath}/bootstrap/cache/services.php");
    putenv("LARAVEL_PACKAGES_CACHE={$storagePath}/bootstrap/cache/packages.php");
    putenv("LARAVEL_CONFIG_CACHE={$storagePath}/bootstrap/cache/config.php");
    putenv("LARAVEL_ROUTES_CACHE={$storagePath}/bootstrap/cache/routes.php");
}

// 3. Boot Laravel
try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    echo "<h1>TypeRush Boot Error</h1>";
    echo "<pre>" . $e->getMessage() . "</pre>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
    exit(1);
}

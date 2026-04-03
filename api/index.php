<?php

/**
 * Vercel Serverless Function Bridge
 */

// 1. Force raw error reporting (Disable pretty pages to see the REAL error)
error_reporting(E_ALL);
ini_set('display_errors', '1');

// 2. Prepare writable storage in /tmp
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
}

// 3. Boot Laravel
try {
    require __DIR__ . '/../public/index.php';
} catch (\Exception $e) {
    // If it crashes, show the RAW error instead of the BindingResolutionException
    echo "<h1>TypeRush Boot Error</h1>";
    echo "<pre>" . $e->getMessage() . "</pre>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
    exit(1);
}

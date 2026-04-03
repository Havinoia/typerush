<?php

/**
 * TypeRush Vercel Serverless Bridge - Final Version
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

// 1. Mandatory Autoloader (The "Pipes")
require __DIR__ . '/../vendor/autoload.php';

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

    // Redirect internal maps to /tmp
    putenv("LARAVEL_SERVICES_CACHE={$storagePath}/bootstrap/cache/services.php");
    putenv("LARAVEL_PACKAGES_CACHE={$storagePath}/bootstrap/cache/packages.php");
    putenv("LARAVEL_CONFIG_CACHE={$storagePath}/bootstrap/cache/config.php");
    putenv("LARAVEL_ROUTES_CACHE={$storagePath}/bootstrap/cache/routes.php");
}

// 3. Boot Laravel and Handle the Request
try {
    // We use the application's bootstrap/app.php which we already 
    // configured to use /tmp/storage if VERCEL is detected.
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    
    // Handle the request manually to ensure we are using the correct context
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    );
    $response->send();
    $kernel->terminate($request, $response);
    
} catch (\Throwable $e) {
    echo "<h1>TypeRush Boot Error</h1>";
    echo "<h3>" . get_class($e) . "</h3>";
    echo "<p><b>Message:</b> " . $e->getMessage() . "</p>";
    echo "<p><b>File:</b> " . $e->getFile() . " (Line " . $e->getLine() . ")</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
    exit(1);
}

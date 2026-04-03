<?php

/**
 * TypeRush Vercel Serverless Bridge - "Deep Surgery" Mode
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

// 1. Storage & Map Preparation
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
    // Force redirect internal maps to /tmp
    putenv("LARAVEL_SERVICES_CACHE={$storagePath}/bootstrap/cache/services.php");
    putenv("LARAVEL_PACKAGES_CACHE={$storagePath}/bootstrap/cache/packages.php");
    putenv("LARAVEL_CONFIG_CACHE={$storagePath}/bootstrap/cache/config.php");
    putenv("LARAVEL_ROUTES_CACHE={$storagePath}/bootstrap/cache/routes.php");
}

// 2. Load the App without executing yet
try {
    echo "<!-- Booting Laravel... -->";
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    
    echo "<h1>TypeRush Manual Resolution Test</h1>";
    
    // Check if the base 'view' engine can be resolved manually
    echo "Attempting to resolve [view] service...<br>";
    if ($app->bound('view')) {
        echo "✅ Service [view] is bound.<br>";
        $view = $app->make('view');
        echo "✅ Service [view] resolved successfully!<br>";
    } else {
        echo "❌ Service [view] is NOT bound to the container.<br>";
        
        echo "<h3>Checking Core Service Providers...</h3>";
        // Diagnostic: list registered providers if possible
        // (Internal Laravel detail, but let's see if we can trigger a boot)
        $app->boot();
        echo "✅ Application booted manually.<br>";
        
        if ($app->bound('view')) {
            echo "✅ Service [view] resolved <b>after</b> manual boot!<br>";
        } else {
            echo "❌ Service [view] <b>STILL</b> not found after manual boot.<br>";
        }
    }
    
    // 3. Final Hand-off
    echo "<hr>Attempting Request Capture...<br>";
    require __DIR__ . '/../public/index.php';
    
} catch (\Throwable $e) {
    echo "<h1 style='color:red;'>🚨 Deep Surgery Crash!</h1>";
    echo "<b>Original Exception:</b> " . get_class($e) . "<br>";
    echo "<b>Message:</b> " . $e->getMessage() . "<br>";
    echo "<b>File:</b> " . $e->getFile() . " (Line " . $e->getLine() . ")<br>";
    echo "<b>Full Stack Trace:</b><pre style='background:#f4f4f4;padding:10px;'>" . $e->getTraceAsString() . "</pre>";
    exit(1);
}

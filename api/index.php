<?php

/**
 * TypeRush Vercel Serverless Bridge - Standard Secure Version
 */

// 1. Mandatory Autoloader
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

    // Redirect internal Laravel discovery maps to /tmp
    putenv("LARAVEL_SERVICES_CACHE={$storagePath}/bootstrap/cache/services.php");
    putenv("LARAVEL_PACKAGES_CACHE={$storagePath}/bootstrap/cache/packages.php");
    putenv("LARAVEL_CONFIG_CACHE={$storagePath}/bootstrap/cache/config.php");
    putenv("LARAVEL_ROUTES_CACHE={$storagePath}/bootstrap/cache/routes.php");
}

// 3. Boot & Handle Request
try {
    // Get the application instance from the standard bootstrap
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    
    // Check for APP_KEY immediately as it's the most common failure point
    if (empty(getenv('APP_KEY')) && empty($_ENV['APP_KEY'])) {
        die("<h1>TypeRush Configuration Error</h1><p>APP_KEY is missing in Vercel Settings.</p>");
    }

    // Standard Request Handling through the Kernel
    // This allows Laravel to boot its own providers in the correct order
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    );
    $response->send();
    $kernel->terminate($request, $response);
    
} catch (\Throwable $e) {
    http_response_code(500);
    if (getenv('APP_DEBUG') === 'true') {
        echo "<h1>TypeRush Boot Error</h1>";
        echo "<h3>" . get_class($e) . "</h3>";
        echo "<p><b>Message:</b> " . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    } else {
        echo "<h1>500 Internal Server Error</h1><p>Please check the Vercel logs for more details.</p>";
    }
    exit(1);
}

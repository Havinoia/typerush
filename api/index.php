<?php

/**
 * TypeRush Vercel Serverless Bridge - Production Ready
 * Deployment Trigger: 2026-04-03 12:44
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

// 1. Load Autoloader
require __DIR__ . '/../vendor/autoload.php';

// 2. Storage & Cache Redirects
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

// 3. Boot Application
try {
    $app = require_once __DIR__ . '/../bootstrap/app.php';

    // Memaksa pemuatan provider yang terlewat (Membangunkan Pondasi Dasar)
    $coreProviders = [
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Log\LogServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
    ];

    foreach ($coreProviders as $provider) {
        if (!$app->getProvider($provider)) {
            $app->register($provider);
        }
    }

    if (method_exists($app, 'loadDeferredProviders')) {
        $app->loadDeferredProviders();
    }

    // 4. Handle Request
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    );
    $response->send();
    $kernel->terminate($request, $response);

} catch (\Throwable $e) {
    http_response_code(500);
    echo "<h1>TypeRush Boot Error</h1>";
    echo "<p><b>Original Exception:</b> " . get_class($e) . "</p>";
    echo "<p><b>Message:</b> " . $e->getMessage() . "</p>";
    
    if (getenv('APP_DEBUG') === 'true' || getenv('VERCEL')) {
        echo "<pre style='background:#eee;padding:10px;overflow:auto;'>" . $e->getTraceAsString() . "</pre>";
    }
    exit(1);
}

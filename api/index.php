<?php

/**
 * TypeRush Vercel Serverless Bridge - Full Foundation Wake-up (Hardened)
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

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

    // Redirect internal maps to /tmp
    putenv("LARAVEL_SERVICES_CACHE={$storagePath}/bootstrap/cache/services.php");
    putenv("LARAVEL_PACKAGES_CACHE={$storagePath}/bootstrap/cache/packages.php");
    putenv("LARAVEL_CONFIG_CACHE={$storagePath}/bootstrap/cache/config.php");
    putenv("LARAVEL_ROUTES_CACHE={$storagePath}/bootstrap/cache/routes.php");
}

// 3. Boot Laravel
try {
    $app = require_once __DIR__ . '/../bootstrap/app.php';

    // Membangunkan SELURUH Pondasi Inti (Hardened Registration)
    $coreProviders = [
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Log\LogServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
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
    // Emergency Display
    http_response_code(500);
    echo "<h1>🚨 TypeRush Foundation Error</h1>";
    echo "<h3>" . get_class($e) . "</h3>";
    echo "<p><b>Message:</b> " . $e->getMessage() . "</p>";
    echo "<p><b>File:</b> " . $e->getFile() . " (Line " . $e->getLine() . ")</p>";
    echo "<hr><pre style='background:#f4f4f4;padding:10px;overflow:auto;'>" . $e->getTraceAsString() . "</pre>";
    exit(1);
}

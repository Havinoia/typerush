<?php

/**
 * Vercel Serverless Function Bridge
 * This file serves as the entry point for Vercel.
 */

// If running on Vercel, prepare the writable storage directory in /tmp
if (env('VERCEL')) {
    $storagePath = '/tmp/storage';
    $dirs = [
        $storagePath . '/framework/views',
        $storagePath . '/framework/cache',
        $storagePath . '/framework/sessions',
        $storagePath . '/bootstrap/cache',
        $storagePath . '/logs',
    ];

    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
}

// Boot Laravel
require __DIR__ . '/../public/index.php';

<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Load Autoloader
require __DIR__ . '/../vendor/autoload.php';

// 2. Load Aplikasi Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';

// === PERBAIKAN VERCEL (ULTIMATE FIX) ===

// Daftar folder yang WAJIB ada di /tmp agar Laravel jalan
// Folder ini dibuat di runtime karena /var/task (root) bersifat Read-Only
$dirs = [
    '/tmp/storage/bootstrap/cache',
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/logs',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        // Buat folder secara rekursif (0777 agar bisa ditulis)
        mkdir($dir, 0777, true);
    }
}

// A. Arahkan Storage Utama ke /tmp
$app->useStoragePath('/tmp/storage');

// B. Arahkan Cache Bootstrap ke /tmp
// Ini memberitahu Laravel untuk menyimpan file cache (services, packages, routes) di /tmp
$app->useEnvironmentPath('/tmp');
$tmpCachePath = '/tmp/storage/bootstrap/cache';

// Set path khusus untuk file-file cache agar tidak ditulis ke folder asli
$_ENV['APP_PACKAGES_CACHE'] = $tmpCachePath . '/packages.php';
$_ENV['APP_SERVICES_CACHE'] = $tmpCachePath . '/services.php';
$_ENV['APP_ROUTES_CACHE']   = $tmpCachePath . '/routes-v7.php';
$_ENV['APP_EVENTS_CACHE']   = $tmpCachePath . '/events.php';

// Pastikan environment variable terbaca oleh helper env() dan sistem
putenv('APP_PACKAGES_CACHE=' . $_ENV['APP_PACKAGES_CACHE']);
putenv('APP_SERVICES_CACHE=' . $_ENV['APP_SERVICES_CACHE']);
putenv('APP_ROUTES_CACHE=' . $_ENV['APP_ROUTES_CACHE']);
putenv('APP_EVENTS_CACHE=' . $_ENV['APP_EVENTS_CACHE']);

// ==================================

// 3. Jalankan Aplikasi
$app->handleRequest(Request::capture());

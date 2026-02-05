<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Load Autoloader
require __DIR__ . '/../vendor/autoload.php';

// 2. Load Aplikasi Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';

// === PERBAIKAN VERCEL (FULL) ===

// A. Buat struktur folder di /tmp (satu-satunya tempat yang bisa ditulis)
$tmpPath = '/tmp/storage/bootstrap/cache';
if (!is_dir($tmpPath)) {
    mkdir($tmpPath, 0777, true);
}

// B. Arahkan Storage ke /tmp
$app->useStoragePath('/tmp/storage');

// C. Arahkan File Cache (Bootstrap) ke /tmp
// Ini memindahkan file packages.php, services.php, dll ke folder temp
$app->useEnvironmentPath('/tmp'); 

$_ENV['APP_PACKAGES_CACHE'] = $tmpPath . '/packages.php';
$_ENV['APP_SERVICES_CACHE'] = $tmpPath . '/services.php';
$_ENV['APP_ROUTES_CACHE']   = $tmpPath . '/routes-v7.php';
$_ENV['APP_EVENTS_CACHE']   = $tmpPath . '/events.php';

// Pastikan env terbaca oleh fungsi helper env()
putenv('APP_PACKAGES_CACHE=' . $_ENV['APP_PACKAGES_CACHE']);
putenv('APP_SERVICES_CACHE=' . $_ENV['APP_SERVICES_CACHE']);
putenv('APP_ROUTES_CACHE=' . $_ENV['APP_ROUTES_CACHE']);
putenv('APP_EVENTS_CACHE=' . $_ENV['APP_EVENTS_CACHE']);

// ==================================

// 3. Jalankan Aplikasi
$app->handleRequest(Request::capture());

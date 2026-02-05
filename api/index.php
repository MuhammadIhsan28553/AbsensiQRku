<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Load Autoloader
require __DIR__ . '/../vendor/autoload.php';

// 2. Load Aplikasi Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';

// === PERBAIKAN VERCEL (PENTING) ===
// Mengalihkan folder storage ke /tmp karena Vercel Read-Only
$app->useStoragePath('/tmp/storage');
// ==================================

// 3. Jalankan Aplikasi
$app->handleRequest(Request::capture());

<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

ini_set('log_errors', '1');
ini_set('error_log', 'php://stderr');

require __DIR__ . '/../vendor/autoload.php';

$storagePath = '/tmp/storage';
if (! is_dir($storagePath)) {
    @mkdir($storagePath . '/framework/views', 0775, true);
    @mkdir($storagePath . '/framework/cache/data', 0775, true);
    @mkdir($storagePath . '/framework/sessions', 0775, true);
}

$dbFile = __DIR__ . '/../database/database.sqlite';
if (! file_exists('/tmp/database.sqlite') && is_file($dbFile)) {
    @copy($dbFile, '/tmp/database.sqlite');
    @chmod('/tmp/database.sqlite', 0664);
}
if (file_exists('/tmp/database.sqlite')) {
    $_ENV['DB_DATABASE'] = '/tmp/database.sqlite';
}

$app = require_once __DIR__ . '/../bootstrap/app.php';

$app->useStoragePath($storagePath);

$app->handleRequest(Request::capture());
<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $r = Illuminate\Support\Facades\Http::timeout(10)->get('http://185.237.97.69:8080/');
    echo 'OK: ' . $r->body() . PHP_EOL;
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
}

try {
    $r = Illuminate\Support\Facades\Http::timeout(10)->post('http://185.237.97.69:8080/auth/register', []);
    echo 'OK REGISTER: ' . $r->body() . PHP_EOL;
} catch (Exception $e) {
    echo 'ERROR REGISTER: ' . $e->getMessage() . PHP_EOL;
}

<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Http;

$baseUrl = 'http://185.237.97.69:8080/';

echo "Testing with Laravel Http::get...\n";
try {
    $response = Http::timeout(10)->get($baseUrl);
    echo "OK: HTTP " . $response->status() . "\n";
    echo "Body: " . substr($response->body(), 0, 200) . "\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\nTesting with raw cURL...\n";
$ch = curl_init($baseUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
$r = curl_exec($ch);
if ($r === false) {
    echo 'ERROR: ' . curl_error($ch) . "\n";
} else {
    echo 'OK: HTTP ' . curl_getinfo($ch, CURLINFO_HTTP_CODE) . "\n";
}
curl_close($ch);

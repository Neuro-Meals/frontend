<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$baseUrl = 'http://185.237.97.69:8080';
$email = 'test_' . time() . '@example.com';

$payload = [
    'first_name' => 'Test',
    'last_name' => 'User',
    'email' => $email,
    'phone' => '+966551234567',
    'password' => 'password123',
    'password_confirmation' => 'password123',
];

echo "Testing direct API register: {$baseUrl}/auth/register\n";
echo "Email: {$email}\n\n";

try {
    $response = Illuminate\Support\Facades\Http::withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ])->timeout(30)->post("{$baseUrl}/auth/register", $payload);

    echo "Status: " . $response->status() . "\n";
    echo "Body: " . $response->body() . "\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

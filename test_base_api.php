<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$authApi = app(\App\Services\Api\AuthApiService::class);

$response = $authApi->register([
    'first_name' => 'Test',
    'last_name' => 'User',
    'email' => 'test_' . time() . '@example.com',
    'phone' => '+966551234567',
    'password' => 'password123',
    'location' => 'Riyadh',
    'address' => 'King Fahd Road',
    'gender' => 'male',
    'age' => 30,
    'height_cm' => 175,
    'weight_kg' => 70,
    'fitness_goal' => 'weight_loss',
    'dietary_preference' => 'standard',
    'allergies' => [],
]);

echo "Response:\n";
print_r($response);

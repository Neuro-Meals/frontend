<?php
$baseUrl = 'http://185.237.97.69:8080';
$email = 'test_' . time() . '@example.com';

$payload = [
    'first_name' => 'Test',
    'last_name' => 'User',
    'email' => $email,
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
    'allergies' => ['nuts'],
];

$ch = curl_init("{$baseUrl}/auth/register");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Content-Type: application/json',
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_HEADER, true);

echo "Testing: {$baseUrl}/auth/register\n";
$response = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "Status: {$status}\n";
echo "cURL error: {$error}\n";
echo "Response:\n{$response}\n";

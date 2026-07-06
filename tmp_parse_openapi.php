<?php
$json = json_decode(file_get_contents('f:\\Nitromeals\\tmp_openapi.json'), true);
$schema = $json['paths']['/auth/register']['post']['requestBody']['content']['application/json']['schema'] ?? null;
if ($schema) {
    echo json_encode($schema, JSON_PRETTY_PRINT);
} else {
    echo "Schema not found\n";
}

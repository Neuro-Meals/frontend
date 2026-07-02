<?php
$en = json_decode(file_get_contents('lang/en.json'), true);
if ($en === null) {
    echo "en.json INVALID: " . json_last_error_msg() . "\n";
} else {
    echo "en.json OK: " . count($en) . " keys\n";
}

$ar = json_decode(file_get_contents('lang/ar.json'), true);
if ($ar === null) {
    echo "ar.json INVALID: " . json_last_error_msg() . "\n";
} else {
    echo "ar.json OK: " . count($ar) . " keys\n";
}

// Check for duplicate keys in en.json
$raw = file_get_contents('lang/en.json');
preg_match_all('/"([^"]+)":/', $raw, $matches);
$counts = array_count_values($matches[1]);
$dups = array_filter($counts, function($c) { return $c > 1; });
if (!empty($dups)) {
    echo "Duplicate keys in en.json: " . implode(', ', array_keys($dups)) . "\n";
}

// Check for duplicate keys in ar.json
$raw = file_get_contents('lang/ar.json');
preg_match_all('/"([^"]+)":/', $raw, $matches);
$counts = array_count_values($matches[1]);
$dups = array_filter($counts, function($c) { return $c > 1; });
if (!empty($dups)) {
    echo "Duplicate keys in ar.json: " . implode(', ', array_keys($dups)) . "\n";
}

// Check for missing keys (in en but not in ar)
$missing = array_diff_key($en, $ar);
if (!empty($missing)) {
    echo "Missing in ar.json (" . count($missing) . "): " . implode(', ', array_keys($missing)) . "\n";
}

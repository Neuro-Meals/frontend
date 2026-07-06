<?php
$ch = curl_init('http://185.237.97.69:8080/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
$r = curl_exec($ch);
if ($r === false) {
    echo 'ERROR: ' . curl_error($ch) . PHP_EOL;
} else {
    echo 'OK: ' . substr($r, 0, 100) . PHP_EOL;
}
curl_close($ch);

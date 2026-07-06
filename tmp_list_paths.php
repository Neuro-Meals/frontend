<?php
$j = json_decode(file_get_contents('f:\Nitromeals\tmp_openapi.json'), true);
foreach ($j['paths'] as $p => $d) {
    echo $p . PHP_EOL;
}

<?php
$lines = file('storage/logs/laravel.log');
$errors = [];
foreach ($lines as $line) {
    if (strpos($line, 'local.ERROR:') !== false) {
        $errors[] = $line;
    }
}
if (empty($errors)) {
    echo "No local.ERROR found. Let's dump the last 50 lines:\n";
    $last_lines = array_slice($lines, -50);
    echo implode("", $last_lines);
} else {
    echo "LAST ERROR:\n" . end($errors);
}

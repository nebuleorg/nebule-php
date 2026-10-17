<?php
/**
 * Simple syntax check for crypto library files
 */

echo "Checking PHP syntax for modified crypto files...\n\n";

$filesToCheck = [
    '/home/bachue/workspace/nebule-php/lib_nebule/201_crypto.php',
    '/home/bachue/workspace/nebule-php/lib_nebule/204_crypto_sodium.php',
];

foreach ($filesToCheck as $file) {
    echo "Checking $file... ";
    $command = "php -l " . escapeshellarg($file) . " 2>&1";
    $output = shell_exec($command);
    
    if (strpos($output, 'No syntax errors detected') !== false) {
        echo "✓ PASS\n";
    } else {
        echo "✗ FAIL\n";
        echo "  Output: $output\n";
    }
}

echo "\nSyntax check completed!\n";

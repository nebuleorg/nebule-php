<?php
require_once __DIR__ . '/lib_nebule/000_init.php';
require_once __DIR__ . '/lib_nebule/001_nebule.php';
require_once __DIR__ . '/lib_nebule/002_references.php';
require_once __DIR__ . '/lib_nebule/003_functions.php';
require_once __DIR__ . '/lib_nebule/004_metrology.php';
require_once __DIR__ . '/lib_nebule/005_configuration.php';
require_once __DIR__ . '/lib_nebule/200_crypto_interface.php';
require_once __DIR__ . '/lib_nebule/201_crypto.php';
require_once __DIR__ . '/lib_nebule/203_crypto_software.php';

use Nebule\Library\RSASoftware;

$rsa = new RSASoftware();

echo "Testing individual components...\n";

try {
    echo "Testing generateRandomNumber(64)...\n";
    $start = microtime(true);
    $rand = $rsa->generateRandomNumber(64);
    $end = microtime(true);
    echo "Result: " . substr($rand, 0, 10) . "... (" . round($end - $start, 3) . "s)\n";
    
    echo "Testing isPrime...\n";
    $start = microtime(true);
    $isPrime = $rsa->isPrime($rand, 1);
    $end = microtime(true);
    echo "Result: " . ($isPrime ? "true" : "false") . " (" . round($end - $start, 3) . "s)\n";
    
    echo "Testing generatePrime(64)...\n";
    $start = microtime(true);
    $prime = $rsa->generatePrime(64);
    $end = microtime(true);
    echo "Result: " . substr($prime, 0, 10) . "... (" . round($end - $start, 3) . "s)\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
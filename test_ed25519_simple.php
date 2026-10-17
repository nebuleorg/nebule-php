<?php
declare(strict_types=1);

// Simple test script for ED25519
$LIB_NEBULE_DIR = __DIR__ . '/lib_nebule';

// Load required files
require_once $LIB_NEBULE_DIR . '/000_init.php';
require_once $LIB_NEBULE_DIR . '/001_nebule.php';
require_once $LIB_NEBULE_DIR . '/002_references.php';
require_once $LIB_NEBULE_DIR . '/003_functions.php';
require_once $LIB_NEBULE_DIR . '/004_metrology.php';
require_once $LIB_NEBULE_DIR . '/005_configuration.php';
require_once $LIB_NEBULE_DIR . '/200_crypto_interface.php';
require_once $LIB_NEBULE_DIR . '/201_crypto.php';
require_once $LIB_NEBULE_DIR . '/203_crypto_software.php';

use Nebule\Library\ED25519Software;

set_time_limit(30);

echo "Testing ED25519 basic operations...\n";

try {
    $ed25519 = new ED25519Software();
    echo "ED25519Software initialized.\n";

    $testSeed = 'ad258128049c8c2d00f0e76778c396cb3112f6d49c18d3b17c2250e650f5d59f';
    echo "Starting key generation from seed...\n";
    
    $start = microtime(true);
    $keys = $ed25519->generateKeyPairFromSeed($testSeed);
    $end = microtime(true);
    
    echo "Key generation completed in " . round($end - $start, 2) . " seconds.\n";
    echo "Private key length: " . strlen($keys['private']) . "\n";
    echo "Public key length: " . strlen($keys['public']) . "\n";
    
    $testData = 'Test ED25519';
    echo "Signing data: $testData\n";
    
    $start = microtime(true);
    $signature = $ed25519->sign($testData, $keys['private'], '');
    $end = microtime(true);
    echo "Signing completed in " . round($end - $start, 2) . " seconds.\n";
    echo "Signature length: " . strlen($signature) . "\n";
    
    $start = microtime(true);
    $verified = $ed25519->verify($testData, $signature, $keys['public']);
    $end = microtime(true);
    echo "Verification completed in " . round($end - $start, 2) . " seconds.\n";
    echo "Verification: " . ($verified ? 'PASS' : 'FAIL') . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
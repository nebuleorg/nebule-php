<?php
// Simple test to debug signing issue

require_once __DIR__ . '/lib_nebule/000_init.php';
require_once __DIR__ . '/lib_nebule/001_nebule.php';
require_once __DIR__ . '/lib_nebule/002_references.php';
require_once __DIR__ . '/lib_nebule/003_functions.php';
require_once __DIR__ . '/lib_nebule/004_metrology.php';
require_once __DIR__ . '/lib_nebule/005_configuration.php';
require_once __DIR__ . '/lib_nebule/200_crypto_interface.php';
require_once __DIR__ . '/lib_nebule/201_crypto.php';
require_once __DIR__ . '/lib_nebule/203_crypto_software.php';

use Nebule\Library\CryptoSoftware;

echo "Signing Debug Test\n";
echo str_repeat("=", 40) . "\n\n";

try {
    $crypto = new CryptoSoftware();
    
    // Generate keys
    echo "Generating rsa.64 keys...\n";
    $keys = $crypto->newAsymmetricKeys('', 'rsa.64', 0);
    
    if (empty($keys) || !isset($keys['private']) || !isset($keys['public'])) {
        throw new Exception('Failed to generate keys');
    }
    
    echo "Keys generated\n\n";
    
    // Test messages
    $messages = [
        'Sign',
        'Test sign'
    ];
    
    foreach ($messages as $testData) {
        echo "Testing message: '$testData'\n";
        
        // Sign
        $signature = $crypto->sign($testData, $keys['private'], '');
        if ($signature === '') {
            echo "  Signing failed\n";
            continue;
        }
        echo "  Signature: " . substr($signature, 0, 20) . "...\n";
        
        // Verify
        $verified = $crypto->verify($testData, $signature, $keys['public'], 'rsa.64');
        echo "  Verified: " . ($verified ? "PASS" : "FAIL") . "\n";
        
        // Get hash info
        $hash = hash('sha256', $testData);
        echo "  Hash: $hash (" . strlen($hash) . " chars)\n";
        
        echo "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
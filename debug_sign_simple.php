<?php
// Simple debug script without full Nebule dependencies

// Manually include only what we need
require_once __DIR__ . '/lib_nebule/200_crypto_interface.php';
require_once __DIR__ . '/lib_nebule/201_crypto.php';
require_once __DIR__ . '/lib_nebule/203_crypto_software.php';

// Mock the dependencies
class Nebule {}
class References {}
class Functions {
    public function __construct($nebule) {}
}
class Metrology {
    public function addLog() {}
}

use Nebule\Library\CryptoSoftware;

echo "Simple Signing Debug\n";
echo str_repeat("=", 50) . "\n\n";

try {
    $crypto = new CryptoSoftware();
    
    // Generate keys
    echo "Generating rsa.64 keys...\n";
    $keys = $crypto->newAsymmetricKeys('', 'rsa.64', 0);
    
    if (empty($keys) || !isset($keys['private']) || !isset($keys['public'])) {
        throw new Exception('Failed to generate keys');
    }
    
    echo "Keys generated successfully\n\n";
    
    // Test messages
    $messages = [
        'short_message' => 'Sign',
        'standard_message' => 'Test sign'
    ];
    
    foreach ($messages as $testName => $testData) {
        echo "Testing: $testName - '$testData'\n";
        
        // Sign
        echo "  Signing...\n";
        $signature = $crypto->sign($testData, $keys['private'], '');
        if ($signature === '') {
            echo "  ERROR: Signing failed\n";
            continue;
        }
        echo "  Signature: " . substr($signature, 0, 20) . "...\n";
        
        // Verify
        echo "  Verifying...\n";
        $verified = $crypto->verify($testData, $signature, $keys['public'], 'rsa.64');
        echo "  Verified: " . ($verified ? "PASS" : "FAIL") . "\n";
        
        // Debug hash info
        $hash = hash('sha256', $testData);
        echo "  SHA256 hash: $hash\n";
        echo "  Hash length: " . strlen($hash) . " hex chars = " . (strlen($hash) / 2) . " bytes\n";
        
        echo "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
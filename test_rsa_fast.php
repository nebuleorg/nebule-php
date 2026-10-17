<?php
// Test the RSASoftware implementation with very small key sizes

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

echo "Testing RSASoftware with very small key sizes\n";
echo str_repeat("=", 50) . "\n\n";

$rsa = new RSASoftware();

// Test with progressively larger key sizes
$keySizes = [32, 64, 96];

foreach ($keySizes as $keySize) {
    echo "Testing with $keySize-bit key generation...\n";
    
    try {
        $start = microtime(true);
        $keys = $rsa->generateKeyPair($keySize);
        $end = microtime(true);
        
        if (empty($keys) || !isset($keys['private']) || !isset($keys['public'])) {
            throw new Exception('Key generation failed');
        }
        
        $time = round($end - $start, 2);
        echo "Key generation successful in $time seconds!\n";
        
        // Test with a small message
        $testMessage = 'Hi';
        echo "Testing encryption/decryption with message: '$testMessage'\n";
        
        $encrypted = $rsa->encrypt($testMessage, $keys['public']);
        echo "Encrypted: " . substr($encrypted, 0, 30) . (strlen($encrypted) > 30 ? '...' : '') . "\n";
        
        $decrypted = $rsa->decrypt($encrypted, $keys['private']);
        echo "Decrypted: '$decrypted'\n";
        echo "Decryption test: " . ($testMessage === $decrypted ? "PASS" : "FAIL") . "\n";
        
        if ($testMessage !== $decrypted) {
            echo "  Expected: '$testMessage'\n";
            echo "  Got: '$decrypted'\n";
        }
        
        break; // Stop after first success
        
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
    echo "\n";
}

echo "Test completed.\n";
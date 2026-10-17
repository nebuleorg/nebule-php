<?php
// Test optimized RSA generation

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

echo "Testing Optimized RSA Prime Generation\n";
echo str_repeat("=", 40) . "\n\n";

// Test with different key sizes
$keySizes = [64, 128, 256];

foreach ($keySizes as $keySize) {
    echo "Testing $keySize-bit key generation...\n";
    
    try {
        $start = microtime(true);
        $keys = $rsa->generateKeyPair($keySize);
        $end = microtime(true);
        
        $time = round($end - $start, 2);
        echo "  Success: $time seconds\n";
        
        // Test encryption/decryption
        $message = 'Test';
        $encrypted = $rsa->encrypt($message, $keys['public']);
        $decrypted = $rsa->decrypt($encrypted, $keys['private']);
        
        $success = ($message === $decrypted);
        echo "  Encrypt/Decrypt: " . ($success ? "PASS" : "FAIL") . "\n";
        
    } catch (Exception $e) {
        echo "  Error: " . $e->getMessage() . "\n";
    }
    
    echo "\n";
}

echo "Test completed.\n";
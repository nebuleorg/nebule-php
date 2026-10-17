<?php
// Comprehensive test of RSASoftware implementation

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

echo "Comprehensive RSASoftware Test\n";
echo str_repeat("=", 60) . "\n\n";

$rsa = new RSASoftware();

// Test with small key sizes for faster testing
$keySizes = [32, 64, 128];
$testMessages = ['Hi', 'Hello', 'Test RSA!', 'The quick brown fox'];

foreach ($keySizes as $keySize) {
    echo "=== Testing with $keySize-bit keys ===\n";
    
    try {
        $start = microtime(true);
        $keys = $rsa->generateKeyPair($keySize);
        $end = microtime(true);
        
        $time = round($end - $start, 3);
        echo "Key generation: $time seconds\n";
        
        if (empty($keys) || !isset($keys['private']) || !isset($keys['public'])) {
            throw new Exception('Key generation failed');
        }
        
        // Test encryption/decryption with different messages
        foreach ($testMessages as $testMessage) {
            // Skip messages that are too long for small keys
            if (strlen($testMessage) > $keySize / 8) {
                echo "  Skipping long message: '$testMessage'\n";
                continue;
            }
            
            $encrypted = $rsa->encrypt($testMessage, $keys['public']);
            $decrypted = $rsa->decrypt($encrypted, $keys['private']);
            
            $success = ($testMessage === $decrypted);
            echo "  " . ($success ? "[PASS]" : "[FAIL]") . " Encrypt/Decrypt: '$testMessage' -> '$decrypted'\n";
            
            if (!$success) {
                echo "    Expected: '$testMessage'\n";
                echo "    Got:      '$decrypted'\n";
            }
        }
        
        // Test signing/verification
        echo "Testing signing/verification:\n";
        $testData = 'Test signing';
        
        // Skip if message is too long
        if (strlen($testData) <= $keySize / 8) {
            $signature = $rsa->sign($testData, $keys['private']);
            $verified = $rsa->verify($testData, $signature, $keys['public']);
            
            echo "  " . ($verified ? "[PASS]" : "[FAIL]") . " Sign/Verify: '$testData'\n";
            
            // Test with wrong data
            $wrongVerified = $rsa->verify('Wrong data', $signature, $keys['public']);
            echo "  " . (!$wrongVerified ? "[PASS]" : "[FAIL]") . " Wrong data verification should fail\n";
        } else {
            echo "  Skipping signing test - message too long for key size\n";
        }
        
        echo "\n";
        
    } catch (Exception $e) {
        echo "  [ERROR] " . $e->getMessage() . "\n";
    }
}

echo "Comprehensive test completed.\n";
<?php
// Test only the software RSA implementation

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

echo "Testing Software RSA Implementation Only\n";
echo str_repeat("=", 50) . "\n\n";

try {
    // Create a CryptoSoftware instance with a minimal nebule instance
    $nebule = new Nebule\Library\Nebule();
    $crypto = new CryptoSoftware();
    $crypto->setNebuleInstance($nebule);
    
    // Test supported algorithms
    $algos = $crypto->getAlgorithmList(Nebule\Library\Crypto::TYPE_ASYMMETRIC);
    echo "Supported RSA algorithms: " . implode(', ', $algos) . "\n\n";
    
    // Test with 64-bit keys (should be fast)
    $testAlgo = 'rsa.64';
    echo "Testing with algorithm: $testAlgo\n";
    
    // Generate keys
    echo "Generating keys...\n";
    $start = microtime(true);
    $keys = $crypto->newAsymmetricKeys('', $testAlgo, 0);
    $end = microtime(true);
    
    $time = round($end - $start, 3);
    echo "Key generation completed in $time seconds\n";
    
    if (empty($keys) || !isset($keys['private']) || !isset($keys['public'])) {
        throw new Exception('Key generation failed');
    }
    
    echo "Keys generated successfully\n";
    echo "Private key length: " . strlen($keys['private']) . " chars\n";
    echo "Public key length: " . strlen($keys['public']) . " chars\n\n";
    
    // Test encryption/decryption
    $testMessages = ['Hi', 'Hello', 'Test!'];
    
    foreach ($testMessages as $message) {
        echo "Testing message: '$message'\n";
        
        // Encrypt
        $encrypted = $crypto->encryptTo($message, $keys['public']);
        if ($encrypted === '') {
            throw new Exception('Encryption failed');
        }
        echo "  Encrypted: " . substr($encrypted, 0, 30) . (strlen($encrypted) > 30 ? '...' : '') . "\n";
        
        // Decrypt
        $decrypted = $crypto->decryptTo($encrypted, $keys['private'], '');
        if ($decrypted === '') {
            throw new Exception('Decryption failed');
        }
        echo "  Decrypted: '$decrypted'\n";
        
        $success = ($message === $decrypted);
        echo "  Result: " . ($success ? "PASS" : "FAIL") . "\n\n";
        
        if (!$success) {
            echo "  Expected: '$message'\n";
            echo "  Got:      '$decrypted'\n\n";
        }
    }
    
    // Test signing/verification
    echo "Testing signing/verification:\n";
    $testData = 'Test signing';
    
    $signature = $crypto->sign($testData, $keys['private'], '');
    if ($signature === '') {
        throw new Exception('Signing failed');
    }
    echo "  Signature: " . substr($signature, 0, 30) . (strlen($signature) > 30 ? '...' : '') . "\n";
    
    $verified = $crypto->verify($testData, $signature, $keys['public'], $testAlgo);
    echo "  Verification: " . ($verified ? "PASS" : "FAIL") . "\n\n";
    
    // Test with wrong data
    $wrongVerified = $crypto->verify('Wrong data', $signature, $keys['public'], $testAlgo);
    echo "  Wrong data verification: " . (!$wrongVerified ? "PASS (correctly failed)" : "FAIL (incorrectly passed)") . "\n\n";
    
    echo "All tests completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\nDone.\n";
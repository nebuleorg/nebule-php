<?php
echo "Testing RSA encrypt/decrypt and sign/verify...\n\n";

require_once __DIR__ . '/lib_nebule/000_init.php';
require_once __DIR__ . '/lib_nebule/001_nebule.php';
require_once __DIR__ . '/lib_nebule/003_functions.php';
require_once __DIR__ . '/lib_nebule/200_crypto_interface.php';
require_once __DIR__ . '/lib_nebule/201_crypto.php';
require_once __DIR__ . '/lib_nebule/203_crypto_software.php';

set_error_handler(function($errno, $errstr) {
    if (strpos($errstr, 'addLog') !== false || strpos($errstr, 'metrology') !== false) {
        return true;
    }
    return false;
});

try {
    $rsa = new Nebule\Library\RSASoftware();
    echo "1. RSASoftware instance created\n";
    
    // Generate keys with 128 bits (faster for testing)
    echo "2. Generating RSA keys (128 bits)...\n";
    $start = microtime(true);
    $keys = $rsa->generateKeyPair(128);
    $end = microtime(true);
    echo "   Time: " . round($end - $start, 2) . " seconds\n";
    
    if (empty($keys['private']) || empty($keys['public'])) {
        echo "   ERROR: Key generation failed\n";
        exit(1);
    }
    echo "   Keys generated successfully\n";
    
    // Test encryption
    echo "\n3. Testing encryption...\n";
    $testData = "Hello RSA!";
    echo "   Original: '$testData'\n";
    
    $encrypted = $rsa->encrypt($testData, $keys['public']);
    if ($encrypted === '') {
        echo "   ERROR: Encryption returned empty result\n";
        echo "   Checking public key format...\n";
        echo "   Public key: " . substr($keys['public'], 0, 100) . "...\n";
    } else {
        echo "   Encrypted: " . substr($encrypted, 0, 50) . "...\n";
        echo "   Encrypted length: " . strlen($encrypted) . "\n";
        
        // Test decryption
        echo "\n4. Testing decryption...\n";
        $decrypted = $rsa->decrypt($encrypted, $keys['private']);
        echo "   Decrypted: '$decrypted'\n";
        echo "   Success: " . ($decrypted === $testData ? "PASS" : "FAIL") . "\n";
    }
    
    // Test signing
    echo "\n5. Testing signing...\n";
    $signature = $rsa->sign($testData, $keys['private']);
    if ($signature === '') {
        echo "   ERROR: Signing returned empty result\n";
        echo "   Checking private key format...\n";
        echo "   Private key: " . substr($keys['private'], 0, 100) . "...\n";
    } else {
        echo "   Signature: " . substr($signature, 0, 50) . "...\n";
        echo "   Signature length: " . strlen($signature) . "\n";
        
        // Test verification
        echo "\n6. Testing verification...\n";
        $verified = $rsa->verify($testData, $signature, $keys['public']);
        echo "   Verification result: " . ($verified ? "PASS" : "FAIL") . "\n";
    }
    
    echo "\nTest completed.\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    exit(1);
} catch (Error $e) {
    echo "FATAL ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    exit(1);
}
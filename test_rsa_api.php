<?php
// Test the RSASoftware implementation using public API only

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

echo "Testing RSASoftware with public API\n";
echo str_repeat("=", 50) . "\n\n";

$rsa = new RSASoftware();

// Test with the known small RSA case manually by creating keys
// We know p=61, q=53 should give n=3233, phi=3120, e=17, d=2753

try {
    // Test key generation with a very small size (128 bits = 16 bytes)
    echo "Testing with 128-bit key generation...\n";
    
    $keys = $rsa->generateKeyPair(128);
    
    if (empty($keys) || !isset($keys['private']) || !isset($keys['public'])) {
        throw new Exception('Key generation failed');
    }
    
    echo "Key generation successful!\n";
    
    // Test with a small message
    $testMessage = 'Hello!';
    echo "Testing encryption/decryption with message: '$testMessage'\n";
    
    $encrypted = $rsa->encrypt($testMessage, $keys['public']);
    echo "Encrypted: " . substr($encrypted, 0, 50) . (strlen($encrypted) > 50 ? '...' : '') . "\n";
    
    $decrypted = $rsa->decrypt($encrypted, $keys['private']);
    echo "Decrypted: '$decrypted'\n";
    echo "Decryption test: " . ($testMessage === $decrypted ? "PASS" : "FAIL") . "\n";
    
    if ($testMessage !== $decrypted) {
        echo "  Expected: '$testMessage'\n";
        echo "  Got: '$decrypted'\n";
    }
    
    // Test signing
    echo "\nTesting signing/verification:\n";
    $signature = $rsa->sign($testMessage, $keys['private']);
    echo "Signature: " . substr($signature, 0, 50) . (strlen($signature) > 50 ? '...' : '') . "\n";
    
    $verified = $rsa->verify($testMessage, $signature, $keys['public']);
    echo "Verification: " . ($verified ? "PASS" : "FAIL") . "\n";
    
    // Test with wrong message
    $wrongVerified = $rsa->verify('Wrong message', $signature, $keys['public']);
    echo "Wrong message verification (should be FAIL): " . ($wrongVerified ? "PASS (but this is wrong!)" : "PASS") . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\nTest completed.\n";
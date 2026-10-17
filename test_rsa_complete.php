<?php
// Test the complete RSASoftware implementation with the known small RSA case

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

echo "Testing RSASoftware with known small RSA case\n";
echo str_repeat("=", 50) . "\n\n";

$rsa = new RSASoftware();

// Test the arithmetic functions first
echo "Testing basic arithmetic:\n";
echo "add('10', '20'): " . $rsa->add('10', '20') . "\n";
echo "subtract('3120', '17'): " . $rsa->subtract('3120', '17') . "\n";
echo "multiply('17', '2753'): " . $rsa->multiply('17', '2753') . "\n";
echo "modInverse('17', '3120'): " . $rsa->modInverse('17', '3120') . " (expected: 2753)\n";
echo "modPow('65', '17', '3233'): " . $rsa->modPow('65', '17', '3233') . " (expected: 2790)\n\n";

// Test the known RSA case manually
$n = '3233';
$e = '17';
$d = '2753';
$message = '65';

echo "Testing RSA encryption/decryption manually:\n";
echo "Message: $message\n";

// Encrypt: c = m^e mod n
$encrypted = $rsa->modPow($message, $e, $n);
echo "Encrypted: $encrypted\n";

// Decrypt: m = c^d mod n
$decrypted = $rsa->modPow($encrypted, $d, $n);
echo "Decrypted: $decrypted\n";
echo "Test: " . ($message === $decrypted ? "PASS" : "FAIL") . "\n\n";

// Now test with the full key generation and encryption/decryption
echo "Testing with key generation and full API:\n";

try {
    // Generate a small key pair for testing
    $keySize = 128; // Small size for fast testing
    echo "Generating RSA key pair with size $keySize...\n";
    
    $keys = $rsa->generateKeyPair($keySize);
    
    if (empty($keys) || !isset($keys['private']) || !isset($keys['public'])) {
        throw new Exception('Key generation failed');
    }
    
    echo "Key generation successful!\n";
    echo "Private key length: " . strlen($keys['private']) . " characters\n";
    echo "Public key length: " . strlen($keys['public']) . " characters\n";
    
    // Test with a small message
    $testMessage = 'Hello RSA!';
    echo "\nTesting encryption/decryption with message: '$testMessage'\n";
    
    $encrypted = $rsa->encrypt($testMessage, $keys['public']);
    echo "Encrypted: " . substr($encrypted, 0, 50) . (strlen($encrypted) > 50 ? '...' : '') . "\n";
    
    $decrypted = $rsa->decrypt($encrypted, $keys['private']);
    echo "Decrypted: '$decrypted'\n";
    echo "Decryption test: " . ($testMessage === $decrypted ? "PASS" : "FAIL") . "\n";
    
    // Test signing
    echo "\nTesting signing/verification:\n";
    $signature = $rsa->sign($testMessage, $keys['private']);
    echo "Signature: " . substr($signature, 0, 50) . (strlen($signature) > 50 ? '...' : '') . "\n";
    
    $verified = $rsa->verify($testMessage, $signature, $keys['public']);
    echo "Verification: " . ($verified ? "PASS" : "FAIL") . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\nTest completed.\n";
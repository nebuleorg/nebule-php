<?php
// Simple test to check if RSASoftware type errors are fixed

echo "Simple RSA Test\n";
echo "================\n\n";

// Extract and test only RSASoftware class
require_once __DIR__ . '/lib_nebule/203_crypto_software.php';

try {
    // Try to create RSASoftware instance directly (no dependencies)
    $rsa = new Nebule\Library\RSASoftware();
    echo "1. RSASoftware instance created: SUCCESS\n";
    
    // Test with very small key size for speed
    echo "2. Testing key generation with 128 bits...\n";
    $start = microtime(true);
    $keys = $rsa->generateKeyPair(128);
    $end = microtime(true);
    
    $time = round($end - $start, 2);
    echo "   Time: $time seconds\n";
    
    if (!empty($keys['private']) && !empty($keys['public'])) {
        echo "   Key generation: SUCCESS\n";
        echo "   Private key length: " . strlen($keys['private']) . " bytes\n";
        echo "   Public key length: " . strlen($keys['public']) . " bytes\n";
    } else {
        echo "   Key generation: FAILED\n";
    }
    
    // Test basic math operations
    echo "\n3. Testing basic math operations...\n";
    
    $sum = $rsa->add('123', '456');
    echo "   Add (123+456): " . ($sum === '579' ? "PASS" : "FAIL (got: $sum)") . "\n";
    
    $diff = $rsa->subtract('456', '123');
    echo "   Subtract (456-123): " . ($diff === '333' ? "PASS" : "FAIL (got: $diff)") . "\n";
    
    $prod = $rsa->multiply('12', '12');
    echo "   Multiply (12*12): " . ($prod === '144' ? "PASS" : "FAIL (got: $prod)") . "\n";
    
    $mod = $rsa->modulo('100', '17');
    echo "   Modulo (100%17): " . ($mod === '15' ? "PASS" : "FAIL (got: $mod)") . "\n";
    
    echo "\nTest completed successfully!\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
    exit(1);
} catch (Error $e) {
    echo "FATAL ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}
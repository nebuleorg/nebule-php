<?php
require_once __DIR__ . '/lib_nebule/203_crypto_software.php';

use Nebule\Library\RSASoftware;

echo "Testing basic RSA operations...\n\n";

$rsa = new RSASoftware();

// Test with very small key for quick testing (not secure, but for testing)
echo "1. Testing with small key size...\n";
try {
    // Generate a small key (for testing only)
    $start = microtime(true);
    $keys = $rsa->generateKeyPair(128); // 128-bit key for testing
    $end = microtime(true);
    
    if (!empty($keys['private']) && !empty($keys['public'])) {
        echo "   Key generation: SUCCESS (took " . round($end - $start, 2) . " seconds)\n";
        
        // Test encryption
        $testData = "Test";
        $encrypted = $rsa->encrypt($testData, $keys['public']);
        echo "   Encryption: " . ($encrypted !== '' ? "SUCCESS" : "FAILED") . "\n";
        
        if ($encrypted !== '') {
            $decrypted = $rsa->decrypt($encrypted, $keys['private']);
            echo "   Decryption: " . ($decrypted === $testData ? "SUCCESS" : "FAILED") . "\n";
            echo "   Original: '$testData', Decrypted: '$decrypted'\n";
        }
        
        // Test signing
        $signature = $rsa->sign($testData, $keys['private']);
        echo "   Signing: " . ($signature !== '' ? "SUCCESS" : "FAILED") . "\n";
        
        if ($signature !== '') {
            $verified = $rsa->verify($testData, $signature, $keys['public']);
            echo "   Verification: " . ($verified ? "SUCCESS" : "FAILED") . "\n";
            
            // Test with wrong data
            $verifiedWrong = $rsa->verify("Wrong", $signature, $keys['public']);
            echo "   Wrong data verification: " . (!$verifiedWrong ? "SUCCESS (correctly failed)" : "FAILED") . "\n";
        }
    } else {
        echo "   Key generation: FAILED\n";
    }
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}

echo "\n2. Testing basic math operations...\n";
try {
    // Test basic arithmetic
    $a = "12345678901234567890";
    $b = "98765432109876543210";
    
    $sum = $rsa->add($a, $b);
    echo "   Addition: " . ($sum === "111111111011111111100" ? "SUCCESS" : "FAILED (got: $sum)") . "\n";
    
    $diff = $rsa->subtract($b, $a);
    echo "   Subtraction: " . ($diff === "86419753208641975320" ? "SUCCESS" : "FAILED (got: $diff)") . "\n";
    
    $prod = $rsa->multiply($a, $b);
    echo "   Multiplication: " . ($prod !== "" ? "SUCCESS" : "FAILED") . "\n";
    
    $mod = $rsa->modulo($b, $a);
    echo "   Modulo: " . ($mod !== "" ? "SUCCESS" : "FAILED") . "\n";
    
} catch (Exception $e) {
    echo "   Math test error: " . $e->getMessage() . "\n";
}

echo "\nTest completed.\n";
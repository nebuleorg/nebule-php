<?php
echo "Testing RSA with single byte message...\n\n";

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
    
    // Generate keys with 128 bits
    echo "2. Generating RSA keys (128 bits)...\n";
    $start = microtime(true);
    $keys = $rsa->generateKeyPair(128);
    $end = microtime(true);
    echo "   Time: " . round($end - $start, 2) . " seconds\n";
    
    // Test with single byte
    $testData = "A"; // Single byte
    echo "\n3. Testing encryption with data: '$testData' (ASCII " . ord($testData) . ")\n";
    
    $encrypted = $rsa->encrypt($testData, $keys['public']);
    if ($encrypted === '') {
        echo "   ERROR: Encryption failed\n";
    } else {
        echo "   Encrypted: " . $encrypted . "\n";
        echo "   Encrypted length: " . strlen($encrypted) . " bytes\n";
        
        // Test decryption
        echo "\n4. Testing decryption...\n";
        $decrypted = $rsa->decrypt($encrypted, $keys['private']);
        echo "   Decrypted: '$decrypted' (ASCII " . (strlen($decrypted) > 0 ? ord($decrypted[0]) : 'empty') . ")\n";
        echo "   Success: " . ($decrypted === $testData ? "PASS" : "FAIL") . "\n";
        echo "   Decrypted length: " . strlen($decrypted) . " bytes\n";
        
        // Debug: show key components
        $reflection = new ReflectionClass($rsa);
        $parsePEM = $reflection->getMethod('parsePEM');
        $parsePEM->setAccessible(true);
        
        echo "\n5. Debug key components...\n";
        $publicComponents = $parsePEM->invoke($rsa, $keys['public']);
        echo "   Public key n: " . substr($publicComponents['n'], 0, 20) . "... (length: " . strlen($publicComponents['n']) . " digits)\n";
        echo "   Public key e: " . $publicComponents['e'] . "\n";
        
        $privateComponents = $parsePEM->invoke($rsa, $keys['private']);
        echo "   Private key n: " . substr($privateComponents['n'], 0, 20) . "... (length: " . strlen($privateComponents['n']) . " digits)\n";
        echo "   Private key d: " . substr($privateComponents['d'], 0, 20) . "... (length: " . strlen($privateComponents['d']) . " digits)\n";
        
        // Test direct modPow
        echo "\n6. Testing direct modPow...\n";
        $modPow = $reflection->getMethod('modPow');
        $modPow->setAccessible(true);
        
        $n = $publicComponents['n'];
        $e = $publicComponents['e'];
        $dataInt = '65'; // ASCII for 'A'
        
        $encryptedDirect = $modPow->invoke($rsa, $dataInt, $e, $n);
        echo "   Direct encryption result: " . substr($encryptedDirect, 0, 20) . "...\n";
        
        $d = $privateComponents['d'];
        $decryptedDirect = $modPow->invoke($rsa, $encryptedDirect, $d, $n);
        echo "   Direct decryption result: " . $decryptedDirect . "\n";
        echo "   Direct success: " . ($decryptedDirect === '65' ? "PASS" : "FAIL") . "\n";
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
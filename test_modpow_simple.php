<?php
echo "Testing modPow with small RSA example...\n\n";

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
    $rsa = new ReflectionClass('Nebule\Library\RSASoftware');
    $instance = $rsa->newInstance();
    
    // Test with known RSA values
    // p=61, q=53, n=3233, φ(n)=3120, e=17, d=2753
    echo "1. Testing basic math operations...\n";
    
    $add = $rsa->getMethod('add');
    $add->setAccessible(true);
    
    $multiply = $rsa->getMethod('multiply');
    $multiply->setAccessible(true);
    
    $modPow = $rsa->getMethod('modPow');
    $modPow->setAccessible(true);
    
    $n = '3233';
    $e = '17';
    $d = '2753';
    $m = '65';
    
    echo "   n = $n, e = $e, d = $d, m = $m\n";
    
    // Test encryption: c = m^e mod n
    $c = $modPow->invoke($instance, $m, $e, $n);
    echo "   c = m^e mod n = $c\n";
    
    // Expected: 65^17 mod 3233 = 2557
    $expected_c = '2557';
    echo "   Expected c = $expected_c\n";
    echo "   Encryption: " . ($c === $expected_c ? "PASS" : "FAIL (got $c)") . "\n";
    
    // Test decryption: m' = c^d mod n
    $m2 = $modPow->invoke($instance, $c, $d, $n);
    echo "   m' = c^d mod n = $m2\n";
    echo "   Expected m' = $m\n";
    echo "   Decryption: " . ($m2 === $m ? "PASS" : "FAIL (got $m2)") . "\n";
    
    // Test modInverse
    $modInverse = $rsa->getMethod('modInverse');
    $modInverse->setAccessible(true);
    
    $inv = $modInverse->invoke($instance, $e, '3120'); // φ(n) = 3120
    echo "\n2. Testing modInverse...\n";
    echo "   modInverse($e, 3120) = $inv\n";
    echo "   Expected: 2753\n";
    echo "   Result: " . ($inv === '2753' ? "PASS" : "FAIL (got $inv)") . "\n";
    
    // Test bytes conversion
    echo "\n3. Testing bytes conversion...\n";
    
    $bytesToBigInt = $rsa->getMethod('bytesToBigInt');
    $bytesToBigInt->setAccessible(true);
    
    $bigIntToBytes = $rsa->getMethod('bigIntToBytes');
    $bigIntToBytes->setAccessible(true);
    
    $testStr = "Hello";
    $bigInt = $bytesToBigInt->invoke($instance, $testStr);
    echo "   '$testStr' -> bigint: $bigInt\n";
    
    $backToBytes = $bigIntToBytes->invoke($instance, $bigInt);
    echo "   bigint -> bytes: '$backToBytes'\n";
    echo "   Conversion: " . ($backToBytes === $testStr ? "PASS" : "FAIL") . "\n";
    
    echo "\nTest completed.\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
} catch (Error $e) {
    echo "FATAL ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
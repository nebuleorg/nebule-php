<?php
echo "Debugging PEM parsing...\n\n";

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
    
    echo "1. Creating RSA keys...\n";
    $keys = $rsa->getMethod('generateKeyPair')->invoke($instance, 128);
    
    echo "Public key: " . $keys['public'] . "\n";
    echo "Private key: " . $keys['private'] . "\n";
    
    // Try to debug parsePEM
    $parsePEM = $rsa->getMethod('parsePEM');
    $parsePEM->setAccessible(true);
    
    echo "\n2. Parsing public key...\n";
    $publicComponents = $parsePEM->invoke($instance, $keys['public']);
    echo "Public key components: " . print_r($publicComponents, true) . "\n";
    
    echo "\n3. Parsing private key...\n";
    $privateComponents = $parsePEM->invoke($instance, $keys['private']);
    echo "Private key components: " . print_r($privateComponents, true) . "\n";
    
    // Try to test encrypt with reflection
    echo "\n4. Testing encrypt...\n";
    $encryptMethod = $rsa->getMethod('encrypt');
    $encryptMethod->setAccessible(true);
    
    $testData = "Hello";
    $result = $encryptMethod->invoke($instance, $testData, $keys['public']);
    echo "Encrypt result: '$result'\n";
    echo "Encrypt result length: " . strlen($result) . "\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
} catch (Error $e) {
    echo "FATAL ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
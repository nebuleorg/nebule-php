<?php
echo "Debugging modInverse...\n\n";

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
    
    $modInverse = $rsa->getMethod('modInverse');
    $modInverse->setAccessible(true);
    
    $compare = $rsa->getMethod('compare');
    $compare->setAccessible(true);
    
    $add = $rsa->getMethod('add');
    $add->setAccessible(true);
    
    $result = $modInverse->invoke($instance, '17', '3120');
    echo "modInverse(17, 3120) = $result\n";
    
    $cmp = $compare->invoke($instance, $result, '0');
    echo "compare($result, 0) = $cmp\n";
    
    if ($cmp < 0) {
        $positive = $add->invoke($instance, $result, '3120');
        echo "$result + 3120 = $positive\n";
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
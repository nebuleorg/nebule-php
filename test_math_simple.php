<?php
echo "Testing basic math operations with small numbers...\n\n";

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
    
    $add = $rsa->getMethod('add');
    $add->setAccessible(true);
    
    $subtract = $rsa->getMethod('subtract');
    $subtract->setAccessible(true);
    
    $multiply = $rsa->getMethod('multiply');
    $multiply->setAccessible(true);
    
    $divide = $rsa->getMethod('divide');
    $divide->setAccessible(true);
    
    $modulo = $rsa->getMethod('modulo');
    $modulo->setAccessible(true);
    
    $compare = $rsa->getMethod('compare');
    $compare->setAccessible(true);
    
    echo "Testing with small numbers...\n";
    
    // Test 3120 - 183*17 = 9
    $q = '183';
    $a = '17';
    $product = $multiply->invoke($instance, $q, $a);
    echo "183 * 17 = $product (expected: 3111)\n";
    
    $r2 = $subtract->invoke($instance, '3120', $product);
    echo "3120 - 3111 = $r2 (expected: 9)\n";
    
    // Test 0 - 183*1 = -183
    $q = '1';
    $t1 = '1';
    $product = $multiply->invoke($instance, $q, $t1);
    echo "\n1 * 1 = $product (expected: 1)\n";
    
    $t2 = $subtract->invoke($instance, '0', $product);
    echo "0 - 1 = $t2 (expected: -1)\n";
    
    // Test -1 - 1*184 = -185
    $t2 = $subtract->invoke($instance, '-1', $multiply->invoke($instance, '1', '184'));
    echo "-1 - 184 = $t2 (expected: -185)\n";
    
    // Test division
    $div = $divide->invoke($instance, '3120', '17');
    echo "\n3120 / 17 = quotient: " . $div['quotient'] . ", remainder: " . $div['remainder'] . " (expected: 183, 9)\n";
    
    $div = $divide->invoke($instance, '17', '9');
    echo "17 / 9 = quotient: " . $div['quotient'] . ", remainder: " . $div['remainder'] . " (expected: 1, 8)\n";
    
    $div = $divide->invoke($instance, '9', '8');
    echo "9 / 8 = quotient: " . $div['quotient'] . ", remainder: " . $div['remainder'] . " (expected: 1, 1)\n";
    
    $div = $divide->invoke($instance, '8', '1');
    echo "8 / 1 = quotient: " . $div['quotient'] . ", remainder: " . $div['remainder'] . " (expected: 8, 0)\n";
    
    echo "\nTest completed.\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
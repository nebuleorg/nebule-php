<?php
echo "Testing modInverse step by step...\n\n";

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
    
    $divide = $rsa->getMethod('divide');
    $divide->setAccessible(true);
    
    $subtract = $rsa->getMethod('subtract');
    $subtract->setAccessible(true);
    
    $multiply = $rsa->getMethod('multiply');
    $multiply->setAccessible(true);
    
    $compare = $rsa->getMethod('compare');
    $compare->setAccessible(true);
    
    // Trace modInverse(17, 3120) manually
    $t0 = '0';
    $t1 = '1';
    $r0 = '3120';
    $r1 = '17';
    $m = '3120';
    
    $iteration = 0;
    while ($r1 !== '0' && $iteration < 10) {
        $iteration++;
        echo "Iteration $iteration: t0=$t0, t1=$t1, r0=$r0, r1=$r1\n";
        
        $q = $divide->invoke($instance, $r0, $r1)['quotient'];
        echo "  q = $q\n";
        
        $r2 = $subtract->invoke($instance, $r0, $multiply->invoke($instance, $q, $r1));
        echo "  r2 = r0 - q*r1 = $r2\n";
        
        $t2 = $subtract->invoke($instance, $t0, $multiply->invoke($instance, $q, $t1));
        echo "  t2 = t0 - q*t1 = $t2\n";
        
        // Shift values
        $t0 = $t1;
        $t1 = $t2;
        $r0 = $r1;
        $r1 = $r2;
    }
    
    echo "\nFinal: t0=$t0, r0=$r0\n";
    
    if ($r0 === '1') {
        if ($compare->invoke($instance, $t0, '0') < 0) {
            $t0 = $t0 . ' + ' . $m;
            echo "t0 is negative, adding m: $t0\n";
        }
        echo "modInverse result: $t0\n";
    } else {
        echo "Error: r0 != 1\n";
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
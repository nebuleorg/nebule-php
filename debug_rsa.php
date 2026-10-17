<?php
// Debug script to trace RSA big integer operations
require_once __DIR__ . '/lib_nebule/203_crypto_software.php';

use Nebule\Library\RSASoftware;

$rsa = new RSASoftware();

echo "Testing basic operations:\n\n";

// Test subtract
echo "subtract('20', '10'): " . $rsa->subtract('20', '10') . "\n";
echo "subtract('10', '20'): " . $rsa->subtract('10', '20') . "\n";
echo "subtract('17', '3120'): " . $rsa->subtract('17', '3120') . "\n";

// Test compare
echo "\ncompare('20', '10'): " . $rsa->compare('20', '10') . "\n";
echo "compare('10', '20'): " . $rsa->compare('10', '20') . "\n";
echo "compare('17', '3120'): " . $rsa->compare('17', '3120') . "\n";

// Test divide
echo "\ndivide('3120', '17'): \n";
$result = $rsa->divide('3120', '17');
echo "  quotient: " . $result['quotient'] . "\n";
echo "  remainder: " . $result['remainder'] . "\n";

// Test modInverse
echo "\nTesting modInverse(17, 3120):\n";
try {
    $result = $rsa->modInverse('17', '3120');
    echo "Result: " . $result . "\n";
    echo "Expected: 2753\n";
    echo "Check: 17 * 2753 = " . $rsa->multiply('17', '2753') . "\n";
    echo "Check: 17 * 2753 mod 3120 = " . $rsa->modulo($rsa->multiply('17', '2753'), '3120') . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Test modPow
echo "\nTesting modPow(65, 17, 3233):\n";
try {
    $result = $rsa->modPow('65', '17', '3233');
    echo "Result: " . $result . "\n";
    echo "Expected: 2557\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Trace through the extended Euclidean algorithm manually
echo "\n=== Manual trace of extended Euclidean algorithm for gcd(17, 3120) ===\n";
echo "Finding x such that: 17 * x ≡ 1 mod 3120\n";

echo "\nStep-by-step calculation:\n";
echo "r0 = 3120, r1 = 17\n";
echo "t0 = 0, t1 = 1\n";

$r0 = '3120';
$r1 = '17';
$t0 = '0';
$t1 = '1';

$step = 0;
while ($r1 !== '0') {
    $step++;
    echo "\nStep $step:\n";
    echo "  r0 = $r0, r1 = $r1\n";
    echo "  t0 = $t0, t1 = $t1\n";
    
    $q = $rsa->divide($r0, $r1)['quotient'];
    echo "  q = r0 / r1 = $q\n";
    
    // t2 = t0 - q * t1
    $q_t1 = $rsa->multiply($q, $t1);
    echo "  q * t1 = $q * $t1 = $q_t1\n";
    $t2 = $rsa->subtract($t0, $q_t1);
    echo "  t2 = t0 - q * t1 = $t0 - $q_t1 = $t2\n";
    
    // r2 = r0 - q * r1
    $q_r1 = $rsa->multiply($q, $r1);
    echo "  q * r1 = $q * $r1 = $q_r1\n";
    $r2 = $rsa->subtract($r0, $q_r1);
    echo "  r2 = r0 - q * r1 = $r0 - $q_r1 = $r2\n";
    
    // Shift values
    $t0 = $t1;
    $t1 = $t2;
    $r0 = $r1;
    $r1 = $r2;
    
    echo "  Next: r0 = $r0, r1 = $r1, t0 = $t0, t1 = $t1\n";
}

echo "\nFinal result:\n";
echo "  r0 = $r0 (should be 1 for coprime numbers)\n";
echo "  t0 = $t0 (this is the modular inverse)\n";

// Verify the result
echo "\nVerification:\n";
echo "17 * $t0 = " . $rsa->multiply('17', $t0) . "\n";
echo "17 * $t0 mod 3120 = " . $rsa->modulo($rsa->multiply('17', $t0), '3120') . "\n";

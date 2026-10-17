<?php
// Verify what 65^17 mod 3233 should be

function modPowSimple($base, $exponent, $modulus) {
    $result = 1;
    $base = $base % $modulus;
    
    for ($i = 0; $i < $exponent; $i++) {
        $result = ($result * $base) % $modulus;
    }
    
    return $result;
}

echo "65^17 mod 3233 using simple method: " . modPowSimple(65, 17, 3233) . "\n";

// Let's also compute it step by step
$value = 65;
echo "65^1 mod 3233 = $value\n";
for ($i = 2; $i <= 17; $i++) {
    $value = ($value * 65) % 3233;
    echo "65^$i mod 3233 = $value\n";
}

echo "\nConclusion: 65^17 mod 3233 = $value\n";

// So the expected value of 2557 in the summary might be wrong.
// Let's also verify the RSA implementation with a known test case

echo "\n=== Testing known RSA values ===\n";

// Small RSA test case: p=61, q=53 (n=3233, phi=3120, e=17, d=2753)
// This is a known small RSA case where:
// - n = 3233
// - e = 17 
// - d = 2753 (modular inverse of 17 mod 3120)

$n = 3233;
$e = 17;
$d = 2753;

$message = 65;
echo "Message: $message\n";

// Encrypt: c = m^e mod n
$cipher = pow($message, $e) % $n;
echo "Encrypted (c = $message^$e mod $n): $cipher\n";

// Decrypt: m = c^d mod n
$decrypted = pow($cipher, $d) % $n;
echo "Decrypted (m = $cipher^$d mod $n): $decrypted\n";

echo "RSA test: " . ($message === $decrypted ? "PASS" : "FAIL") . "\n";

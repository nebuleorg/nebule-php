<?php
// Test the known small RSA case
// p=61, q=53, n=3233, phi=3120, e=17, d=2753

function modPowCorrect($base, $exponent, $modulus) {
    $result = 1;
    $base = $base % $modulus;
    
    while ($exponent > 0) {
        if ($exponent % 2 == 1) {
            $result = ($result * $base) % $modulus;
        }
        $exponent = (int)($exponent / 2);
        $base = ($base * $base) % $modulus;
    }
    
    return $result;
}

$n = 3233;
$e = 17;
$d = 2753;
$message = 65;

echo "Known RSA test case:\n";
echo "n = $n (p=61, q=53)\n";
echo "phi(n) = 3120\n";
echo "e = $e, d = $d\n";
echo "Message m = $message\n\n";

// Encrypt: c = m^e mod n
$cipher = modPowCorrect($message, $e, $n);
echo "Encrypted: c = $message^$e mod $n = $cipher\n";

// Decrypt: m = c^d mod n
$decrypted = modPowCorrect($cipher, $d, $n);
echo "Decrypted: m = $cipher^$d mod $n = $decrypted\n";

echo "Test: " . ($message === $decrypted ? "PASS" : "FAIL") . "\n";

// Verify that e * d ≡ 1 mod phi(n)
$ed = $e * $d;
$ed_mod_phi = $ed % 3120;
echo "\ne * d = $ed\n";
echo "e * d mod phi(n) = $ed_mod_phi (should be 1)\n";

// Verify that 17 * 2753 ≡ 1 mod 3120
$verification = (17 * 2753) % 3120;
echo "17 * 2753 mod 3120 = $verification (should be 1)\n";

// Let's also compute 65^17 mod 3233 using our correct modPow
$test65_17 = modPowCorrect(65, 17, 3233);
echo "\n65^17 mod 3233 = $test65_17\n";

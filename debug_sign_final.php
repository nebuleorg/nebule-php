<?php
// Debug final signing issue

require_once __DIR__ . '/lib_nebule/000_init.php';
require_once __DIR__ . '/lib_nebule/001_nebule.php';
require_once __DIR__ . '/lib_nebule/002_references.php';
require_once __DIR__ . '/lib_nebule/003_functions.php';
require_once __DIR__ . '/lib_nebule/004_metrology.php';
require_once __DIR__ . '/lib_nebule/005_configuration.php';
require_once __DIR__ . '/lib_nebule/200_crypto_interface.php';
require_once __DIR__ . '/lib_nebule/201_crypto.php';
require_once __DIR__ . '/lib_nebule/203_crypto_software.php';

use Nebule\Library\RSASoftware;

$rsa = new RSASoftware();

echo "Debug final signing issue\n";
echo str_repeat("=", 30) . "\n\n";

// Generate a 64-bit key pair
$keys = $rsa->generateKeyPair(64);
echo "Generated 64-bit key pair\n\n";

$testData = 'Hi';
echo "Test data: '$testData'\n";

// Check hash and truncation
$hash = hash('sha256', $testData);
echo "SHA256 hash: $hash\n";
echo "Hash length: " . strlen($hash) . " hex chars = " . (strlen($hash) / 2) . " bytes\n";

// Get modulus size
$keyComponents = $rsa->parsePEM($keys['public']);
$n = $keyComponents['n'];
echo "Modulus n: " . substr($n, 0, 20) . "...\n";

// Estimate n size
$nBytes = $rsa->bigIntToBytes($n);
echo "Modulus size: " . strlen($nBytes) . " bytes\n";

// Truncate hash to modulus size
$hashBytes = hex2bin($hash);
echo "Hash bytes: " . bin2hex($hashBytes) . "\n";
echo "Hash bytes length: " . strlen($hashBytes) . " bytes\n";

if (strlen($hashBytes) > strlen($nBytes)) {
    $truncatedHash = substr($hashBytes, 0, strlen($nBytes));
    echo "Truncated hash: " . bin2hex($truncatedHash) . "\n";
    echo "Truncated hash length: " . strlen($truncatedHash) . " bytes\n";
} else {
    $truncatedHash = $hashBytes;
    echo "Hash already fits in modulus\n";
}

echo "\nNow testing sign/verify:\n";

// Test multiple times to see if it's consistent
for ($i = 0; $i < 3; $i++) {
    $signature = $rsa->sign($testData, $keys['private']);
    $verified = $rsa->verify($testData, $signature, $keys['public']);
    echo "Attempt " . ($i+1) . ": Sign=" . ($signature !== '' ? 'OK' : 'FAIL') . ", Verify=" . ($verified ? 'PASS' : 'FAIL') . "\n";
}

echo "\nDone.\n";
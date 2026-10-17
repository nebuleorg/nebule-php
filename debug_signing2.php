<?php
// Debug signing implementation - check key sizes

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

echo "Debugging RSA signing - checking sizes\n";
echo str_repeat("=", 45) . "\n\n";

// Generate a 32-bit key pair
$keys = $rsa->generateKeyPair(32);

echo "32-bit key pair generated\n\n";

// Check hash length
$testData = 'Test';
$hash = hash('sha256', $testData);
echo "SHA256 hash of '$testData': $hash\n";
echo "Hash length: " . strlen($hash) . " hex chars = " . (strlen($hash) / 2) . " bytes\n";

// For a 32-bit key, n should be about 4 bytes
// But SHA256 is 32 bytes, which is much larger than the modulus
// This is the problem! The hash is larger than the key size.

echo "\nThe problem: SHA256 produces 32-byte hashes, but 32-bit RSA keys can only handle 4-byte messages.\n";

// For small keys, we need to use a smaller hash or truncate the hash
// Let's try with MD5 (16 bytes) or even smaller

$smallHash = hash('md5', $testData);
echo "MD5 hash: $smallHash (16 bytes)\n";

$smallerHash = hash('crc32', $testData);
echo "CRC32 hash: $smallerHash (4 bytes)\n";

echo "\nFor small RSA keys, we need to use a hash that fits in the key size.\n";
echo "For 32-bit keys (4 bytes), we should use a 4-byte hash.\n";
echo "For 64-bit keys (8 bytes), we should use an 8-byte hash.\n";

// Let's try signing with a 64-bit key and MD5 hash
$keys64 = $rsa->generateKeyPair(64);
echo "\nTrying with 64-bit keys and truncated hash...\n";

// Manually create a signature with truncated hash
$truncatedHash = substr($hash, 0, 16); // 16 hex chars = 8 bytes
echo "Truncated hash: $truncatedHash\n";

// But we can't call private methods directly...
// Let's just test with the public API and see if 64-bit keys work better
echo "Testing 64-bit key signing with public API...\n";

$testData = 'Test';
$signature = $rsa->sign($testData, $keys64['private']);
echo "Signature: " . ($signature !== '' ? 'Generated' : 'Failed') . "\n";

$verified = $rsa->verify($testData, $signature, $keys64['public']);
echo "Verified: " . ($verified ? "YES" : "NO") . "\n";
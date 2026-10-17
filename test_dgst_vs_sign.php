<?php
// Test the difference between openssl_sign and openssl dgst -sign

echo "=== Testing difference between openssl_sign and openssl dgst -sign ===\n\n";

$testData = 'Hello, World!';

// Generate a test key pair
$config = [
    'private_key_type' => OPENSSL_KEYTYPE_RSA,
    'private_key_bits' => 2048,
];

$privateKeyResource = openssl_pkey_new($config);
if ($privateKeyResource === false) {
    die('Failed to generate private key: ' . openssl_error_string());
}

openssl_pkey_export($privateKeyResource, $privateKey);
$publicKeyResource = openssl_pkey_get_details($privateKeyResource);
$publicKey = $publicKeyResource['key'];
$publicKeyBin = openssl_pkey_get_public($publicKey);

// Method A: Sign the raw data using openssl_sign
echo "=== Method A: openssl_sign(raw data) ===\n";
$signatureA = '';
openssl_sign($testData, $signatureA, $privateKeyResource, OPENSSL_ALGO_SHA256);
$signatureHexA = bin2hex($signatureA);
echo "Signature: " . substr($signatureHexA, 0, 32) . "...\n";

// Verify with raw data
$resultA1 = openssl_verify($testData, $signatureA, $publicKeyBin, OPENSSL_ALGO_SHA256);
echo "Verify with raw data: " . ($resultA1 == 1 ? 'SUCCESS' : 'FAILED') . "\n";

// Verify with hash of data
$dataHash = hash('sha256', $testData, true);
$resultA2 = openssl_verify($dataHash, $signatureA, $publicKeyBin, OPENSSL_ALGO_SHA256);
echo "Verify with hash: " . ($resultA2 == 1 ? 'SUCCESS' : 'FAILED') . "\n\n";

// Method B: Sign the hash using openssl_sign
echo "=== Method B: openssl_sign(hash of data) ===\n";
$signatureB = '';
openssl_sign($dataHash, $signatureB, $privateKeyResource, OPENSSL_ALGO_SHA256);
$signatureHexB = bin2hex($signatureB);
echo "Signature: " . substr($signatureHexB, 0, 32) . "...\n";

// Verify with raw data
$resultB1 = openssl_verify($testData, $signatureB, $publicKeyBin, OPENSSL_ALGO_SHA256);
echo "Verify with raw data: " . ($resultB1 == 1 ? 'SUCCESS' : 'FAILED') . "\n";

// Verify with hash of data
$resultB2 = openssl_verify($dataHash, $signatureB, $publicKeyBin, OPENSSL_ALGO_SHA256);
echo "Verify with hash: " . ($resultB2 == 1 ? 'SUCCESS' : 'FAILED') . "\n\n";

// Method C: Sign using openssl dgst -sign (signs the hash of the raw data)
echo "=== Method C: openssl dgst -sign (signs hash of raw data) ===\n";
$tempKeyFile = tempnam(sys_get_temp_dir(), 'privkey_');
file_put_contents($tempKeyFile, $privateKey);
chmod($tempKeyFile, 0600);

$command = 'echo -n ' . escapeshellarg($testData) . ' | openssl dgst -hex -sha256 -sign ' . escapeshellarg($tempKeyFile) . ' 2>/dev/null | cut -d " " -f2';
$signatureHexC = trim(shell_exec($command));
$signatureC = pack('H*', $signatureHexC);
echo "Signature: " . substr($signatureHexC, 0, 32) . "...\n";

// Verify with raw data
$resultC1 = openssl_verify($testData, $signatureC, $publicKeyBin, OPENSSL_ALGO_SHA256);
echo "Verify with raw data: " . ($resultC1 == 1 ? 'SUCCESS' : 'FAILED') . "\n";

// Verify with hash of data
$resultC2 = openssl_verify($dataHash, $signatureC, $publicKeyBin, OPENSSL_ALGO_SHA256);
echo "Verify with hash: " . ($resultC2 == 1 ? 'SUCCESS' : 'FAILED') . "\n\n";

// Compare the signatures
$signatureHexB = bin2hex($signatureB);
echo "=== Comparison ===\n";
echo "Method A (sign raw): " . substr($signatureHexA, 0, 32) . "...\n";
echo "Method B (sign hash): " . substr($signatureHexB, 0, 32) . "...\n";
echo "Method C (dgst -sign): " . substr($signatureHexC, 0, 32) . "...\n";
echo "Method A == Method B: " . ($signatureHexA == $signatureHexB ? 'YES' : 'NO') . "\n";
echo "Method B == Method C: " . ($signatureHexB == $signatureHexC ? 'YES' : 'NO') . "\n";
echo "Method A == Method C: " . ($signatureHexA == $signatureHexC ? 'YES' : 'NO') . "\n";

// Clean up
unlink($tempKeyFile);
openssl_pkey_free($privateKeyResource);

echo "\nTest completed.\n";

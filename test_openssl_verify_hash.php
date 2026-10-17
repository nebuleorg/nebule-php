<?php
// Test if openssl_verify works when we pass a hash as the data

echo "=== Testing openssl_verify with hash as data ===\n\n";

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

// Test 1: Sign the raw data and verify with raw data
echo "Test 1: Sign raw data, verify with raw data\n";
$signatureBin1 = '';
openssl_sign($testData, $signatureBin1, $privateKeyResource, OPENSSL_ALGO_SHA256);
$result1 = openssl_verify($testData, $signatureBin1, $publicKeyBin, OPENSSL_ALGO_SHA256);
echo "Result: " . ($result1 == 1 ? 'SUCCESS' : 'FAILED') . "\n\n";

// Test 2: Sign the hash of the data and verify with hash of the data
echo "Test 2: Sign hash of data, verify with hash of data\n";
$dataHash = hash('sha256', $testData, true);
$signatureBin2 = '';
openssl_sign($dataHash, $signatureBin2, $privateKeyResource, OPENSSL_ALGO_SHA256);
$result2 = openssl_verify($dataHash, $signatureBin2, $publicKeyBin, OPENSSL_ALGO_SHA256);
echo "Result: " . ($result2 == 1 ? 'SUCCESS' : 'FAILED') . "\n\n";

// Test 3: Sign the raw data and verify with hash of the data
echo "Test 3: Sign raw data, verify with hash of data\n";
$result3 = openssl_verify($dataHash, $signatureBin1, $publicKeyBin, OPENSSL_ALGO_SHA256);
echo "Result: " . ($result3 == 1 ? 'SUCCESS' : 'FAILED (expected)') . "\n\n";

// Test 4: Sign the hash of the data and verify with raw data
echo "Test 4: Sign hash of data, verify with raw data\n";
$result4 = openssl_verify($testData, $signatureBin2, $publicKeyBin, OPENSSL_ALGO_SHA256);
echo "Result: " . ($result4 == 1 ? 'SUCCESS (unexpected)' : 'FAILED (expected)') . "\n\n";

// Test 5: Create signature using openssl dgst -sign and verify
echo "Test 5: Sign with openssl dgst -sign, verify with raw data\n";
$tempKeyFile = tempnam(sys_get_temp_dir(), 'privkey_');
file_put_contents($tempKeyFile, $privateKey);
chmod($tempKeyFile, 0600);

// This creates a signature of the hash of the data (similar to bash script)
$command = 'echo -n ' . escapeshellarg($testData) . ' | openssl dgst -hex -sha256 -sign ' . escapeshellarg($tempKeyFile) . ' 2>/dev/null | cut -d " " -f2';
$signatureHex5 = trim(shell_exec($command));
$signatureBin5 = pack('H*', $signatureHex5);

$result5a = openssl_verify($testData, $signatureBin5, $publicKeyBin, OPENSSL_ALGO_SHA256);
echo "Verify with raw data: " . ($result5a == 1 ? 'SUCCESS' : 'FAILED') . "\n";

$result5b = openssl_verify($dataHash, $signatureBin5, $publicKeyBin, OPENSSL_ALGO_SHA256);
echo "Verify with hash: " . ($result5b == 1 ? 'SUCCESS' : 'FAILED') . "\n\n";

// Test 6: Try with openssl_public_decrypt
$decrypted5 = '';
$decryptResult5 = openssl_public_decrypt($signatureBin5, $decrypted5, $publicKeyBin, OPENSSL_PKCS1_PADDING);
echo "Test 6: openssl_public_decrypt result: " . ($decryptResult5 ? 'SUCCESS' : 'FAILED') . "\n";
if ($decryptResult5) {
    echo "Decrypted length: " . strlen($decrypted5) . " bytes\n";
    echo "Decrypted (hex): " . bin2hex($decrypted5) . "\n";
    echo "Expected hash (hex): " . hash('sha256', $testData) . "\n";
    echo "Decrypted matches hash: " . (hash_equals($decrypted5, $dataHash) ? 'YES' : 'NO') . "\n";
}

// Clean up
unlink($tempKeyFile);
openssl_pkey_free($privateKeyResource);

echo "\nTest completed.\n";

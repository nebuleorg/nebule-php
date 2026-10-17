<?php
// Simple test script to verify the signature verification fix

echo "=== Simple OpenSSL Signature Verification Test ===\n\n";

// Test data
$testData = 'nebule:link/2:0_0>20260714000000/l>abc123def456>sha2.256>type';

// Generate a test key pair
$config = [
    'private_key_type' => OPENSSL_KEYTYPE_RSA,
    'private_key_bits' => 2048,
];

$privateKeyResource = openssl_pkey_new($config);
if ($privateKeyResource === false) {
    die('Failed to generate private key: ' . openssl_error_string());
}

// Get private key as PEM
openssl_pkey_export($privateKeyResource, $privateKey);

// Get public key as PEM
$publicKeyResource = openssl_pkey_get_details($privateKeyResource);
$publicKey = $publicKeyResource['key'];

echo "Generated RSA key pair\n\n";

// Method 1: Create signature using openssl_sign (standard method)
echo "=== Method 1: openssl_sign (standard method) ===\n";
$hashAlgo = OPENSSL_ALGO_SHA256;
$signatureBin = '';
$result = openssl_sign($testData, $signatureBin, $privateKeyResource, $hashAlgo);
if ($result === false) {
    echo "openssl_sign failed: " . openssl_error_string() . "\n";
} else {
    $signatureHex = bin2hex($signatureBin);
    echo "Signature (hex): " . substr($signatureHex, 0, 32) . "...\n";
    
    // Test verification using openssl_verify
    $publicKeyBin = openssl_pkey_get_public($publicKey);
    $verifyResult = openssl_verify($testData, $signatureBin, $publicKeyBin, $hashAlgo);
    echo "openssl_verify result: " . $verifyResult . " (1 = success)\n";
    
    // Test verification using openssl_public_decrypt
    $decrypted = '';
    $decryptResult = openssl_public_decrypt($signatureBin, $decrypted, $publicKeyBin, OPENSSL_PKCS1_PADDING);
    echo "openssl_public_decrypt result: " . ($decryptResult ? 'success' : 'failed') . "\n";
    if ($decryptResult) {
        $decryptedHash = bin2hex($decrypted);
        $expectedHash = hash('sha256', $testData);
        echo "Decrypted signature (hex): " . substr($decryptedHash, 0, 32) . "...\n";
        echo "Expected hash (hex): " . substr($expectedHash, 0, 32) . "...\n";
        echo "Hashes match: " . (hash_equals($decrypted, hash('sha256', $testData, true)) ? 'YES' : 'NO') . "\n";
    }
}

// Method 2: Create signature by signing the hash of the data (legacy method)
echo "\n=== Method 2: Sign the hash of data (legacy method) ===\n";
$dataHash = hash('sha256', $testData, true);
$signatureBin2 = '';
$result2 = openssl_sign($dataHash, $signatureBin2, $privateKeyResource, $hashAlgo);
if ($result2 === false) {
    echo "openssl_sign of hash failed: " . openssl_error_string() . "\n";
} else {
    $signatureHex2 = bin2hex($signatureBin2);
    echo "Signature (hex): " . substr($signatureHex2, 0, 32) . "...\n";
    
    // Test verification using openssl_public_decrypt
    $decrypted2 = '';
    $decryptResult2 = openssl_public_decrypt($signatureBin2, $decrypted2, $publicKeyBin, OPENSSL_PKCS1_PADDING);
    echo "openssl_public_decrypt result: " . ($decryptResult2 ? 'success' : 'failed') . "\n";
    if ($decryptResult2) {
        echo "Decrypted signature matches data hash: " . (hash_equals($decrypted2, $dataHash) ? 'YES' : 'NO') . "\n";
    }
    
    // This is how BlocLink calls verify: with the hash as the data
    echo "\nTesting with hash as data parameter (BlocLink style):\n";
    echo "Data (hash): " . substr(bin2hex($dataHash), 0, 32) . "...\n";
    echo "Signature: " . substr($signatureHex2, 0, 32) . "...\n";
    
    // Method 2a: Try openssl_verify with the hash as data
    $verifyResult2a = openssl_verify($dataHash, $signatureBin2, $publicKeyBin, $hashAlgo);
    echo "openssl_verify(hash, signature): " . $verifyResult2a . " (1 = success, but likely 0 because it hashes the hash)\n";
    
    // Method 2b: Try openssl_public_decrypt and compare with data (which is the hash)
    $decrypted2b = '';
    $decryptResult2b = openssl_public_decrypt($signatureBin2, $decrypted2b, $publicKeyBin, OPENSSL_PKCS1_PADDING);
    if ($decryptResult2b) {
        echo "openssl_public_decrypt + compare with hash: " . (hash_equals($decrypted2b, $dataHash) ? 'SUCCESS' : 'FAILED') . "\n";
    } else {
        echo "openssl_public_decrypt failed: " . openssl_error_string() . "\n";
    }
}

echo "\n=== Testing signature format from bash script ===\n";

// Simulate the bash script command: echo -n "$testData" | openssl dgst -hex -sha256 -sign private_key.pem | cut -d ' ' -f2
// This creates a hex-encoded signature of the hash of the data

// We need to save the private key to a temp file for the openssl command
$tempKeyFile = tempnam(sys_get_temp_dir(), 'privkey_');
file_put_contents($tempKeyFile, $privateKey);
chmod($tempKeyFile, 0600);

$command = 'echo -n ' . escapeshellarg($testData) . ' | openssl dgst -hex -sha256 -sign ' . escapeshellarg($tempKeyFile) . ' 2>/dev/null | cut -d " " -f2';
$signatureHex3 = trim(shell_exec($command));

if (!empty($signatureHex3)) {
    echo "Signature from openssl dgst -sign (hex): " . substr($signatureHex3, 0, 32) . "...\n";
    
    // Convert hex signature to binary
    $signatureBin3 = pack('H*', $signatureHex3);
    
    // Verify using openssl_public_decrypt
    $decrypted3 = '';
    $decryptResult3 = openssl_public_decrypt($signatureBin3, $decrypted3, $publicKeyBin, OPENSSL_PKCS1_PADDING);
    
    if ($decryptResult3) {
        echo "openssl_public_decrypt result: success\n";
        $decryptedHash3 = bin2hex($decrypted3);
        $expectedHash3 = hash('sha256', $testData);
        echo "Decrypted signature (hex): " . substr($decryptedHash3, 0, 32) . "...\n";
        echo "Expected hash (hex): " . substr($expectedHash3, 0, 32) . "...\n";
        echo "Decrypted signature matches expected hash: " . (hash_equals($decrypted3, $dataHash) ? 'YES' : 'NO') . "\n";
        
        // This is the verification that should work for BlocLink
        echo "\nBlocLink-style verification (data is hash, signature is of hash):\n";
        echo "If we pass the hash as data and compare decrypted signature with data: " . (hash_equals($decrypted3, $dataHash) ? 'SUCCESS' : 'FAILED') . "\n";
    } else {
        echo "openssl_public_decrypt failed: " . openssl_error_string() . "\n";
    }
} else {
    echo "Failed to create signature with openssl dgst -sign\n";
}

// Clean up
unlink($tempKeyFile);
openssl_pkey_free($privateKeyResource);

echo "\nTest completed.\n";

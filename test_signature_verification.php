<?php
// Test script to verify the signature verification fix

require_once __DIR__ . '/bootstrap.php';

use Nebule\Library\CryptoOpenssl;

// Test data - this would be a link in the actual system
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
openssl_pkey_export($privateKeyResource, $privateKey, null, []);

// Get public key as PEM
$publicKeyResource = openssl_pkey_get_details($privateKeyResource);
$publicKey = $publicKeyResource['key'];

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
    
    // Test verification
    $publicKeyBin = openssl_pkey_get_public($publicKey);
    $verifyResult = openssl_verify($testData, $signatureBin, $publicKeyBin, $hashAlgo);
    echo "openssl_verify result: " . $verifyResult . " (1 = success)\n";
}

// Method 2: Create signature using openssl dgst -sign (legacy method from bash script)
echo "\n=== Method 2: openssl dgst -sign (legacy method) ===\n";
// This simulates: echo -n "$testData" | openssl dgst -hex -sha256 -sign private_key.pem | cut -d ' ' -f2
$command = 'echo -n ' . escapeshellarg($testData) . ' | openssl dgst -hex -sha256 -sign - -passin pass:' . escapeshellarg('') . ' 2>/dev/null | cut -d " " -f2';
$signatureHex = shell_exec($command);
if (empty($signatureHex)) {
    // Fallback: use PHP to create similar signature
    // openssl dgst -sign signs the hash of the data, not the data itself
    $dataHash = hash('sha256', $testData, true);
    $signatureBin = '';
    $result = openssl_sign($dataHash, $signatureBin, $privateKeyResource, $hashAlgo);
    if ($result === false) {
        echo "openssl_sign of hash failed: " . openssl_error_string() . "\n";
    } else {
        $signatureHex = bin2hex($signatureBin);
        echo "Signature (hex): " . substr($signatureHex, 0, 32) . "...\n";
    }
} else {
    $signatureHex = trim($signatureHex);
    echo "Signature (hex): " . substr($signatureHex, 0, 32) . "...\n";
}

echo "\n=== Testing verification with CryptoOpenssl ===\n";

// Create a mock Nebule instance (minimal)
class MockMetrology {
    public function addLog($message, $level, $method, $id) {
        // echo "LOG: $method - $id - $message\n";
    }
}

class MockConfiguration {
    public function getOptionAsString($name) {
        return $name === 'cryptoHashAlgorithm' ? 'sha2.256' : '';
    }
}

class MockNebule {
    private $metrology;
    private $configuration;
    
    public function __construct() {
        $this->metrology = new MockMetrology();
        $this->configuration = new MockConfiguration();
    }
    
    public function getMetrologyInstance() {
        return $this->metrology;
    }
    
    public function getConfigurationInstance() {
        return $this->configuration;
    }
}

// Create CryptoOpenssl instance
$mockNebule = new MockNebule();
$crypto = new CryptoOpenssl();

// Set up the crypto instance with dependencies
$reflection = new ReflectionClass($crypto);
$metrologyProperty = $reflection->getProperty('_metrologyInstance');
$metrologyProperty->setAccessible(true);
$metrologyProperty->setValue($crypto, $mockNebule->getMetrologyInstance());

$configurationProperty = $reflection->getProperty('_configurationInstance');
$configurationProperty->setAccessible(true);
$configurationProperty->setValue($crypto, $mockNebule->getConfigurationInstance());

// Test verification with the signature created by openssl_sign (method 1)
echo "Test 1: Verify signature from openssl_sign\n";
if (isset($signatureBin) && !empty($signatureBin)) {
    $signatureHex1 = bin2hex($signatureBin);
    $result1 = $crypto->verify($testData, $signatureHex1, $publicKey, 'sha2.256');
    echo "Result: " . ($result1 ? 'SUCCESS' : 'FAILED') . "\n";
}

// Test verification with the signature created by signing the hash (method 2)
echo "\nTest 2: Verify signature from signing hash (simulating openssl dgst -sign)\n";
if (isset($signatureHex) && !empty($signatureHex)) {
    // For this test, we need to pass the hash of the data as the "data" parameter
    $dataHash = hash('sha256', $testData, true);
    $dataHashHex = bin2hex($dataHash);
    $result2 = $crypto->verify($dataHash, $signatureHex, $publicKey, 'sha2.256');
    echo "Result with hash as data: " . ($result2 ? 'SUCCESS' : 'FAILED') . "\n";
    
    // Also try with raw data
    $result3 = $crypto->verify($testData, $signatureHex, $publicKey, 'sha2.256');
    echo "Result with raw data: " . ($result3 ? 'SUCCESS' : 'FAILED') . "\n";
}

echo "\nTest completed.\n";

// Clean up
openssl_pkey_free($privateKeyResource);

<?php
// Debug signing issue in detail

require_once __DIR__ . '/lib_nebule/000_init.php';
require_once __DIR__ . '/lib_nebule/001_nebule.php';
require_once __DIR__ . '/lib_nebule/002_references.php';
require_once __DIR__ . '/lib_nebule/003_functions.php';
require_once __DIR__ . '/lib_nebule/004_metrology.php';
require_once __DIR__ . '/lib_nebule/005_configuration.php';
require_once __DIR__ . '/lib_nebule/200_crypto_interface.php';
require_once __DIR__ . '/lib_nebule/201_crypto.php';
require_once __DIR__ . '/lib_nebule/203_crypto_software.php';

use Nebule\Library\CryptoSoftware;

echo "Detailed Signing Debug\n";
echo str_repeat("=", 50) . "\n\n";

try {
    $crypto = new CryptoSoftware();
    
    // Generate keys
    echo "Generating rsa.64 keys...\n";
    $keys = $crypto->newAsymmetricKeys('', 'rsa.64', 0);
    
    if (empty($keys) || !isset($keys['private']) || !isset($keys['public'])) {
        throw new Exception('Failed to generate keys');
    }
    
    echo "Keys generated successfully\n\n";
    
    // Test messages
    $messages = [
        'short_message' => 'Sign',
        'standard_message' => 'Test sign'
    ];
    
    foreach ($messages as $testName => $testData) {
        echo "Testing: $testName - '$testData'\n";
        
        // Sign
        echo "  Signing...\n";
        $signature = $crypto->sign($testData, $keys['private'], '');
        if ($signature === '') {
            echo "  ERROR: Signing failed\n";
            continue;
        }
        echo "  Signature: " . substr($signature, 0, 20) . "...\n";
        
        // Verify
        echo "  Verifying...\n";
        $verified = $crypto->verify($testData, $signature, $keys['public'], 'rsa.64');
        echo "  Verified: " . ($verified ? "PASS" : "FAIL") . "\n";
        
        // Debug hash info
        $hash = hash('sha256', $testData);
        echo "  SHA256 hash: $hash\n";
        echo "  Hash length: " . strlen($hash) . " hex chars = " . (strlen($hash) / 2) . " bytes\n";
        
        // Check modulus size
        $n = '';
        if (preg_match('/-----BEGIN RSA PUBLIC KEY-----.*?n.*?(\d+).*?-----END RSA PUBLIC KEY-----/s', $keys['public'], $matches)) {
            $n = $matches[1];
        } else {
            // Try to parse the PEM manually
            $pem = str_replace("-----BEGIN RSA PUBLIC KEY-----", '', $keys['public']);
            $pem = str_replace("-----END RSA PUBLIC KEY-----", '', $pem);
            $pem = preg_replace('/\s+/', '', $pem);
            $decoded = base64_decode($pem);
            if ($decoded) {
                $json = json_decode($decoded, true);
                if ($json && isset($json['n'])) {
                    $n = $json['n'];
                }
            }
        }
        
        if ($n !== '') {
            echo "  Modulus n: " . substr($n, 0, 20) . "... (" . strlen($n) . " digits)\n";
            $nBytes = strlen($n) / 2; // Approximate
            echo "  Approximate modulus size: $nBytes bytes\n";
        }
        
        echo "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
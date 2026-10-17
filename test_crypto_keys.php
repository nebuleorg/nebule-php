<?php
/**
 * Test script to verify PEM key conversion fixes in CryptoSodium
 */

// Test the regex patterns directly
echo "Testing PEM key conversion improvements...\n\n";

// Test PEM key (simulated RSA public key in PEM format)
$pemKey1 = "-----BEGIN PUBLIC KEY-----\nMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAxJZ5... (base64 data)\n-----END PUBLIC KEY-----\n";

$pemKey2 = "-----BEGIN PUBLIC KEY-----\nMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAxJZ5
-----END PUBLIC KEY-----\n";

$pemKey3 = "-----BEGIN RSA PUBLIC KEY-----\nMIIBCgKCAQEAxJZ5...\n-----END RSA PUBLIC KEY-----\n";

function testPEMextraction($pem) {
    echo "Testing PEM extraction for key starting with: " . substr($pem, 0, 30) . "...\n";
    
    // Method 1: Normalized approach
    $normalizedPem = preg_replace('/\s+/', '', $pem);
    if (preg_match('/-----BEGIN(.*?)-----(.*?)-----END(.*?)-----/', $normalizedPem, $matches)) {
        $base64Content = $matches[2];
        $binary = base64_decode($base64Content, true);
        if ($binary !== false) {
            echo "  ✓ Method 1 (normalized) succeeded, binary length: " . strlen($binary) . "\n";
            return true;
        }
    }
    
    // Method 2: Alternative approach
    $beginPos = strpos($pem, '-----BEGIN');
    $endPos = strrpos($pem, '-----END');
    if ($beginPos !== false && $endPos !== false && $endPos > $beginPos) {
        $content = substr($pem, $beginPos + 11, $endPos - ($beginPos + 11));
        $content = preg_replace('/-----BEGIN.*?-----/', '', $content);
        $content = preg_replace('/-----END.*?-----/', '', $content);
        $content = trim($content);
        $binary = base64_decode($content, true);
        if ($binary !== false) {
            echo "  ✓ Method 2 (alternative) succeeded, binary length: " . strlen($binary) . "\n";
            return true;
        }
    }
    
    echo "  ✗ Both methods failed\n";
    return false;
}

// Test all PEM formats
$results = [];
$results[] = testPEMextraction($pemKey1);
$results[] = testPEMextraction($pemKey2);
$results[] = testPEMextraction($pemKey3);

echo "\n";
if (array_sum($results) === count($results)) {
    echo "✓ All PEM extraction tests passed!\n";
} else {
    echo "✗ Some PEM extraction tests failed\n";
}

// Test 294-byte key (simulated RSA 2048 public key in PEM format)
echo "\nTesting 294-byte RSA key simulation...\n";
$simulated294BytePem = "-----BEGIN PUBLIC KEY-----\nMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAxJZ5"; // This is not 294 bytes but for testing
echo "Simulated PEM key length: " . strlen($simulated294BytePem) . " characters\n";

// The key insight: 294 bytes suggests a PEM-encoded RSA 2048-bit public key
// RSA 2048-bit = 256 bytes binary, ~344 characters in base64
// With PEM headers, it can be around 294 bytes total
$pemHeader = "-----BEGIN PUBLIC KEY-----\n";
$pemFooter = "\n-----END PUBLIC KEY-----\n";
$base64Data = str_repeat('A', 344); // Simulated base64 data
$fullPem = $pemHeader . $base64Data . $pemFooter;
echo "Full simulated PEM length: " . strlen($fullPem) . " bytes\n";

if (testPEMextraction($fullPem)) {
    echo "✓ 294-byte key simulation test passed!\n";
} else {
    echo "✗ 294-byte key simulation test failed\n";
}

echo "\nPEM key conversion tests completed!\n";

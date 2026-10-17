<?php
// Debug signing implementation

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

echo "Debugging RSA signing\n";
echo str_repeat("=", 30) . "\n\n";

try {
    // Generate a small key pair
    $keys = $rsa->generateKeyPair(32);
    echo "Generated 32-bit key pair\n";
    
    $testData = 'Test';
    echo "Test data: '$testData'\n";
    
    // Check hash function
    $hash = hash('sha256', $testData);
    echo "PHP hash('sha256', '$testData'): $hash\n";
    
    // Manually trace through sign method
    $hashInt = $rsa->bytesToBigInt($hash);
    echo "Hash as bigint: $hashInt\n";
    
    // Parse private key
    $keyComponents = $rsa->parsePEM($keys['private']);
    echo "Private key components: " . implode(', ', array_keys($keyComponents)) . "\n";
    
    if (!isset($keyComponents['n']) || !isset($keyComponents['d'])) {
        echo "Missing n or d in private key\n";
        print_r($keyComponents);
    } else {
        echo "n: " . substr($keyComponents['n'], 0, 20) . "...\n";
        echo "d: " . substr($keyComponents['d'], 0, 20) . "...\n";
        
        // Sign: s = hash^d mod n
        $n = $keyComponents['n'];
        $d = $keyComponents['d'];
        
        echo "Computing signature: hash^d mod n\n";
        $signature = $rsa->modPow($hashInt, $d, $n);
        echo "Signature: $signature\n";
        
        // Verify: hash' = signature^e mod n
        $e = $keyComponents['e'];
        echo "Verifying: signature^e mod n\n";
        $recoveredHash = $rsa->modPow($signature, $e, $n);
        echo "Recovered hash: $recoveredHash\n";
        
        $recoveredHashBytes = $rsa->bigIntToBytes($recoveredHash);
        echo "Recovered hash as bytes: " . bin2hex($recoveredHashBytes) . "\n";
        
        // Convert the original hash to bytes for comparison
        $originalHashBytes = hex2bin($hash);
        echo "Original hash as bytes: " . bin2hex($originalHashBytes) . "\n";
        
        // Check if they match
        $match = ($recoveredHashBytes === $originalHashBytes);
        echo "Hashes match: " . ($match ? "YES" : "NO") . "\n";
        
        // If they don't match, check lengths
        echo "Original hash length: " . strlen($originalHashBytes) . "\n";
        echo "Recovered hash length: " . strlen($recoveredHashBytes) . "\n";
    }
    
    // Now test with the actual methods
    echo "\nTesting with actual methods:\n";
    $signature = $rsa->sign($testData, $keys['private']);
    echo "Signature: " . substr($signature, 0, 30) . "...\n";
    
    $verified = $rsa->verify($testData, $signature, $keys['public']);
    echo "Verified: " . ($verified ? "YES" : "NO") . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}